<?php
	require_once('send_email.php');
	$php_mailer = new email();
	$to 		= 'ralatie@pricon.ph';
	// $to 		= 'ojtsoftware@pricon.ph';
	$from 		= 'issinfoservice@pricon.ph';
	$cc			= '';
	$subject 	= 'Test';
	$body 		= 'The device Test has reached the 80% of the maintenance cycle. Encoded by: ';
	$attachment = '';
	$msg = $php_mailer->send_email($to, $from, $cc, $subject, $body,$attachment);
?>