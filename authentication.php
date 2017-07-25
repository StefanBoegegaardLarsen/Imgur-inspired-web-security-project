<?php

include_once("SMSApi.php");

$sAuthentication = $_POST['verification'];
$phone = $_POST['phone'];

$sms=new SMSApi('Group02','54474710B2935EB5');

$digits = 4;
$authentication=rand(pow(10, $digits-1), pow(10, $digits)-1);

$reply=$sms->SendSMSv2($phone, 'Your authentication code: '.$authentication);

if ($reply->Status==="OK"){
	echo '{"status":"smsSent", "authentication":"'.htmlentities($authentication).'"}';
} else {
	echo '{"status":"smsNotSent"}';
}