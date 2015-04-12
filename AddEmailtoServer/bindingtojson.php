<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>This example shows how to create a Grid from JSON data.</title>
    <link rel="stylesheet" href="../../../scrolling/jqwidgets/styles/jqx.base.css" type="text/css" />
</head>
<?php

require_once('SimpleEmailService.php');
require_once('SimpleEmailServiceMessage.php');
require_once('SimpleEmailServiceRequest.php');


if(!function_exists("curl_init")) die("cURL extension is not installed");
// temporarily access token from the system to retrieve all the people from Egypt using it with the API url
$url = 'https://gis-api.aiesec.org/v1/people.json?access_token=43a75487c59054e14384d0faa8815b2ab4929c2b6ab7b090529b2d12f03209d0&page=1&per_page=1000';

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
include('connect.php');		$conn = new mysqli($hostname, $username, $password, $database);
foreach($arr['data'] as $val)
{
       // echo $val['email'].'<br>';  

$query = "INSERT INTO Emails (Email) VALUES ('{$val['email']}')";
$result = $conn->query($query);		
}
$i=1;
// loop to get all the people from all the pages automatically 
while($i<=$arr['paging']['total_pages'])
{
	$url = 'https://gis-api.aiesec.org/v1/people.json?access_token=43a75487c59054e14384d0faa8815b2ab4929c2b6ab7b090529b2d12f03209d0&page='.$i.'&per_page=1000';

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
			$html="<b>Dear friend</b> <br> I hope you are doing well.<br> BestRegards,<br> Maged Zaklama";
			$text="";
			$m->setMessageFromString($text, $html);
			$ses->sendEmail($m);
			//$m->setMessageFromString('This is the message body.');
			//print_r($ses->sendEmail($m));
			//$Email=$Email['Email'];
			//echo nl2br($message);
		}
		
	?>
<body class='default'>
<div id="id01"></div>
</body>
</html>
