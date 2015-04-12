<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>This example shows how to create a Grid from JSON data.</title>
    <link rel="stylesheet" href="../../../scrolling/jqwidgets/styles/jqx.base.css" type="text/css" />
</head>

<?php

$username = 'maged.zaklama@aiesec.net';
$password = 'shabab2011';
$loginUrl = 'https://auth.aiesec.org/users/sign_in';
 $fields = array(
						'user[email]' => urlencode('maged.zaklama@aiesec.net'),
						'user[Password]' => urlencode('shabab2011')
				);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');
 $ch = curl_init();
$curlConfig = array(
    CURLOPT_URL            => "https://auth.aiesec.org/users/sign_in",
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
	CURLOPT_COOKIEJAR =>'cooki2e.txt',
    CURLOPT_POSTFIELDS     => $fields_string,
    )
);
curl_setopt_array($ch, $curlConfig);
$result = curl_exec($ch);
curl_close($ch);

if(!function_exists("curl_init")) die("cURL extension is not installed");

$url = 'https://gis-api.aiesec.org/v1/people.json?access_token=a1db4c66dfa700783bb922a7e4ce006c5675be7ec75aa2a975cba84a80d76f1b&page=1&per_page=1000';

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
        echo $val['email'].'<br>';       
}
		
	?>
<body class='default'>
<div id="id01"></div>
</body>
</html>
