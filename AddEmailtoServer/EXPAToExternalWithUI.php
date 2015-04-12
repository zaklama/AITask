	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN""http://www.w3.org/TR/html4/strict.dtd"><!--[if IE 7 ]>
	<html lang="en" lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" class="ie7" lang="en"><!--<![endif]--><![endif]--><!--[if lte IE 8 ]>
	<html lang="en" lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" class="ie8" lang="en"> <![endif]--><!--[if (gte IE 9)|!(IE)]><!-->
	<html class="ie9" lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head><!-- Basic Page Needs
	================================================== -->
	<meta charset="utf-8">
	<title id='Description'>Sending Emails to EPs</title>
	<meta name="description" content="">
	<meta name="author" content="Rolas"><!-- Mobile Specific Metas
	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"><!-- CSS
	================================================== -->
	<link rel="stylesheet" media="all" href="../../css/base.css">
	<link rel="stylesheet" media="all" href="../../css/styles.css"><!-- Fonts
	================================================== -->
	<link href="http://fonts.googleapis.com/css?family=Economica:400,700" rel="stylesheet" type="text/css"><!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="../../../../favicon.ico" type="image/x-icon"><!-- JavaScript
	================================================== -->
	<script type="text/javascript" src="../../js/jquery.min.js"></script>
	<script type="text/javascript" src="../../js/scripts.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {               
                // Create a jqxDateTimeInput
				$("#SendMail").click(function () {
					 var Content=document.getElementById("Content").value;
					 var token = document.getElementById("token").value;
					 window.location="?Content="+Content+" &token="+token;
                });
            });
	</script>
	<?php
if(isset($_GET['Content'])){
require_once('SimpleEmailService.php');
require_once('SimpleEmailServiceMessage.php');
require_once('SimpleEmailServiceRequest.php');
include('connect.php');		
$conn = new mysqli($hostname, $username, $password, $database);


if(!function_exists("curl_init")) die("cURL extension is not installed");
// temporarily access token from the system to retrieve all the people from Egypt using it with the API url
//$token="43a75487c59054e14384d0faa8815b2ab4929c2b6ab7b090529b2d12f03209d0";
$Content=$_GET['Content'];
$token=$_GET['token'];
$url = 'https://gis-api.aiesec.org/v1/people.json?access_token='.$token.'&page=1&per_page=1000';

 $curl_options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_FOLLOWLOCATION => TRUE,
                    CURLOPT_ENCODING => 'gzip,deflate',
            );

            $ch = curl_init();
            curl_setopt_array( $ch, $curl_options );
            $output = curl_exec( $ch );
            curl_close($ch);

$arr = json_decode($output,true);
foreach($arr['data'] as $val)
{
       // echo $val['email'].'<br>';  

$query = "INSERT INTO Emails (Email) VALUES ('{$val['email']}')";
$result = $conn->query($query);		
}
$i=2;
// loop to get all the people from all the pages automatically 
while($i<=$arr['paging']['total_pages'])
{
	$url = 'https://gis-api.aiesec.org/v1/people.json?access_token='.$token.'&page='.$i.'&per_page=1000';

 $curl_options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_FOLLOWLOCATION => TRUE,
                    CURLOPT_ENCODING => 'gzip,deflate',
            );

            $ch = curl_init();
            curl_setopt_array( $ch, $curl_options );
            $output = curl_exec( $ch );
            curl_close($ch);

	$arr = json_decode($output,true);
	foreach($arr['data'] as $val)
	{
			//echo $val['email'].'<br>';       
			$query = "INSERT INTO Emails (Email) VALUES ('{$val['email']}')";
	$result = $conn->query($query);	
	}
$i=$i+1;
}
	// Sending Mail to the people on the database
		$query = "SELECT * FROM Emails";
		$Email="";
		$ses = new SimpleEmailService('AKIAITPQIF7MGPQAUE2Q', 'tsY8WV+PGVPqhtCisYw3N10e/xvpCrFPKXDiLsZp');
		$m = new SimpleEmailServiceMessage();

		$result = $conn->query($query);
		// need to change from sandbox in order to send to unverified email addresses
		// Loops on all the emails that we currently have on the database and sending them an Email
		foreach($result as $Email ) 
		{
		// verifying email address as before my account to be able to send to unverified email addresses but it wont send unless the user verifies the email so this is not an efficient solution Waiting for the CS to change the account out of the sandbox stage in order to be able to send to unverified email addresses 
			$ses->verifyEmailAddress($Email['Email']);
			
			$m->addTo($Email['Email']);
			$m->setFrom('maged.zaklama@aiesec.net');
			$m->setSubject('testing');
			$text="";
			$m->setMessageFromString($text, $content);
			$ses->sendEmail($m);
			//$m->setMessageFromString('This is the message body.');
			//print_r($ses->sendEmail($m));
			//$Email=$Email['Email'];
			//echo nl2br($message);
		}}
		
	?>
</head>
<body class="page">
	<div id="header"><!-- Start Main Menu
	================================================== -->
	<div id="breadcrumbs">
	<div class="breadcrumbs"></div></div><!-- End Breadcrumbs
	================================================== --></div>
	<div id="subcontent"><!-- Start Page Title
	================================================== -->
	<div class="page-title abs">
	<h3>AIESEC <span><u>International Task</u></span></h3></div><!-- End Page Title
	================================================== --></div>
	<div id="content">
	<div class="content" style="height:700px;">
	<table id="myForm" width="800" border="0" cellpadding="1" cellspacing="1">
	<td colspan = "2">Content:</td>
	<td ><textarea style="resize:none; width: 600px; height: 150px;"  id="Content"></textarea><br><br></td>
	</tr>
	<tr><td colspan = "2">Access Token:</td><td width="80"><input type="text" id="token"></td></tr>
	<tr><td width="80"><input type="Button" class="box" id="SendMail" value="Send Mail"></td></tr>

	</table>
</div></div>
	<script src="../js/respond.min.js"></script>
	</body>
	</html>
