<?php

require("class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->Host = "103.21.59.208";  /*SMTP server*/

$mail->SMTPAuth = true;
//$mail->SMTPSecure = "ssl";
$mail->Port = 587;
$mail->Username = "no-reply@gomart.in";  /*Username*/
$mail->Password = "Gkw*Z*@1vIH%";    /**Password**/

$mail->From = "no-reply@gomart.in";    /*From address required*/
$mail->FromName = "Test from Info";
$mail->AddAddress("debasish.1911@gmail.com");
//$mail->AddReplyTo("mail@mail.com");

$mail->IsHTML(true);

$mail->Subject = "Test message from server";
$mail->Body = "Test Mail<b>in bold!</b>";
//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
echo "Message could not be sent. <p>";
echo "Mailer Error: " . $mail->ErrorInfo;
exit;
}

echo "Message has been sent";

?>