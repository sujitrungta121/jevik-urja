<?php

require("class.phpmailer.php");
/*
$subject=$_POST["subject"];
$content=$_POST["content"];
$receiver=$_POST["receiver"];*/


$subject="Lorem ipsum email";
$content="lorem ipsum content";
$receiver="debasish.1911@mailinator.com";

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->Host = "mail.jevikurja.com";  /*SMTP server*/

$mail->SMTPAuth = true;
//$mail->SMTPSecure = "ssl";
$mail->Port = 25;
$mail->Username = "sales@jevikurja.com";  /*Username*/
$mail->Password = "NorT4521*";    /**Password**/

$mail->From = "sales@jevikurja.com";    /*From address required*/
$mail->FromName = "Jevik Urja";
$mail->AddAddress($receiver);
//$mail->AddReplyTo("mail@mail.com");

$mail->IsHTML(true);

$mail->Subject = $subject;
$mail->Body = $content;
//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
/*echo "Message could not be sent. <p>";
echo "Mailer Error: " . $mail->ErrorInfo;*/
exit;
}

//echo "Message has been sent";

?>