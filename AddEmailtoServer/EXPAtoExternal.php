<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>Sending Emails to EPs</title>
</head>
<?php

require_once('SimpleEmailService.php');
require_once('SimpleEmailServiceMessage.php');
require_once('SimpleEmailServiceRequest.php');
include('connect.php');		
$conn = new mysqli($hostname, $username, $password, $database);


if(!function_exists("curl_init")) die("cURL extension is not installed");
// temporarily access token from the system to retrieve all the people from Egypt using it with the API url
$token="43a75487c59054e14384d0faa8815b2ab4929c2b6ab7b090529b2d12f03209d0";
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
		
	?>
<body class='default'>
<div id="id01"></div>
</body>
</html>
