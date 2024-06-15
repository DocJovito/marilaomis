// <?php
// function sendOtpEmail($to, $otp) {
//     $subject = "One-Time Password";
//     $message = "Your One-Time Password (OTP) code is $otp.";
//     $headers = "From: marilaomis@marilaomis.com\r\n" .
//               "Reply-To: marilaomis@marilaomis.com\r\n" .
//               "X-Mailer: PHP/" . phpversion();

//     mail($to, $subject, $message, $headers);
// }
// ?>


<?php
// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer classes
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpEmail($to, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'server305.web-hosting.com'; // Replace with your correct Namecheap SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'marilaomis@marilaomis.com'; // Replace with your email address
        $mail->Password = 'marilaomis2024'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Optionally disable certificate verification (not recommended for production)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom('marilaomis@marilaomis.com', 'Marilao MIS'); // Replace with your email and name
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your One-Time Password Code';
        $mail->Body    = "Your One-Time Password (OTP) code is <b>$otp</b>.";
        $mail->AltBody = "Your One-Time Password (OTP) code is $otp.";

        // Send email
        $mail->send();
        // error_log("Email sent successfully to $to");
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>