<?php

// Define recipient, subject, body, and headers of the email
$toEmail = 'webprodev@yandex.ru';
$subject = 'Simple Email Test via PHP';
$body = "Hi,\nThis is a test email sent by a PHP script.";
$headers = 'From: info@crm.mkggroup.ru';

// Send the email
$emailSent = mail($toEmail, $subject, $body, $headers);

// Output based on email sending status
if ($emailSent) {
	echo "Email successfully sent to {$toEmail}...";
} else {
	echo 'Email sending failed...';
}

?>