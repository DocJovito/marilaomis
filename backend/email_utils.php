<?php
function sendOtpEmail($to, $otp)
{
    $subject = "Your OTP Code";
    $message = "Your OTP code is $otp.";
    $headers = "From: marilaomis@rjprint10.com\r\n" .
        "Reply-To: marilaomis@rjprint10.com\r\n" .
        "X-Mailer: PHP/" . phpversion();

    mail($to, $subject, $message, $headers);
}
?>


// <?php
    // // Include PHPMailer classes
    // require 'PHPMailer/src/Exception.php';
    // require 'PHPMailer/src/PHPMailer.php';
    // require 'PHPMailer/src/SMTP.php';

    // use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\Exception;

    // function sendOtpEmail($to, $otp) {
    //     $mail = new PHPMailer(true);

    //     try {
    //         // Server settings
    //         $mail->isSMTP();
    //         $mail->Host = 'mail.rjprint10.com'; // Replace with your Namecheap mail server
    //         $mail->SMTPAuth = true;
    //         $mail->Username = 'marilaomis@rjprint10.com'; // Replace with your email address
    //         $mail->Password = 'marilaomis2024'; // Replace with your email password
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         $mail->Port = 587;

    //         // Recipients
    //         $mail->setFrom('marilaomis@rjprint10.com', 'Marilao MIS'); // Replace with your email and name
    //         $mail->addAddress($to);

    //         // Content
    //         $mail->isHTML(true);
    //         $mail->Subject = 'Your OTP Code';
    //         $mail->Body    = "Your OTP code is <b>$otp</b>.";
    //         $mail->AltBody = "Your OTP code is $otp.";

    //         $mail->send();
    //     } catch (Exception $e) {
    //         error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    //     }
    // }
    // 
    ?>