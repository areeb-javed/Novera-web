<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if($_POST){

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $comments = trim($_POST['comments']);
    $verify   = trim($_POST['verify']);

    // Validation
    if($name == '' || $email == '' || $comments == ''){
        echo "<div class='error_message'>Please fill all fields.</div>";
        exit;
    }
    if($verify != '4'){
        echo "<div class='error_message'>Captcha is incorrect.</div>";
        exit;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<div class='error_message'>Invalid email format.</div>";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP settings for Hostinger
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contact@novera.dev'; // your Hostinger email
        $mail->Password   = 'YOUR_EMAIL_PASSWORD'; // your Hostinger email password
        $mail->SMTPSecure = 'ssl'; // use 'tls' if you choose port 587
        $mail->Port       = 465;   // 465 for SSL, 587 for TLS

        // From & To
        $mail->setFrom('contact@novera.dev', 'Novera Contact Form');
        $mail->addAddress('contact@novera.dev'); // you can receive on same email
        $mail->addReplyTo($email, $name); // so you can reply directly

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "You've been contacted by $name";
        $mail->Body    = "
            <h3>New Contact Form Submission</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong><br>$comments</p>
        ";
        $mail->AltBody = "Name: $name\nEmail: $email\nMessage: $comments";

        $mail->send();
        echo "<div id='success_page'><h3>Email Sent Successfully</h3><p>Thank you <strong>$name</strong>, your message has been submitted.</p></div>";

    } catch (Exception $e) {
        echo "<div class='error_message'>Mailer Error: {$mail->ErrorInfo}</div>";
    }
}
