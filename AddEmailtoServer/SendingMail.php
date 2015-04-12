<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>Sending Emails to EPs</title>
</head>
<?php
	include('connect.php');		$conn = new mysqli($hostname, $username, $password, $database);
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