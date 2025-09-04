<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST['contact_name']);
    $email   = filter_var($_POST['contact_email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['contact_subject']);
    $message = htmlspecialchars($_POST['contact_message']);

    // Validate
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        header("Location: contact.php?error=invalid_request");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // die("Invalid email format.");
         header("Location: contact.php?error=invalid_email");
        exit();
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP settings - === 1. Send email to site owner ===
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['EMAIL_USERNAME'];       // Your email
        $mail->Password   = $_ENV['EMAIL_PASSWORD'];         // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email headers - Sender and recipient
        $mail->setFrom($email, $name);                    // From sender
        $mail->addAddress($_ENV['EMAIL_USERNAME']);         // To your email

//         $mail->setFrom($_ENV['EMAIL_USERNAME'], 'Website Contact Form');
// $mail->addReplyTo($email, $name); // So you can reply to user


        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "<strong>Name:</strong> $name<br>
                          <strong>Email:</strong> $email<br>
                          <strong>Subject:</strong> $subject<br>
                          <strong>Message:</strong><br>" . nl2br($message);

        $mail->send(); // Send to site owner


        // === 2. Auto-reply to user ===
        $autoReply = new PHPMailer(true);
        $autoReply->isSMTP();
        $autoReply->Host       = 'smtp.gmail.com';
        $autoReply->SMTPAuth   = true;
        $autoReply->Username   = $_ENV['EMAIL_USERNAME'];
        $autoReply->Password   = $_ENV['EMAIL_PASSWORD'];
        $autoReply->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $autoReply->Port       = 587;

        // From and to
        $autoReply->setFrom($_ENV['EMAIL_USERNAME'], 'User Profile Application');
        $autoReply->addAddress($email, $name); // Send back to user

        // Email content
        $autoReply->isHTML(true);
        $autoReply->Subject = "Thank you for contacting us!";

        $autoReply->Body = "
            Hi <strong>$name</strong>,<br><br>
            Thank you for reaching out. We've received your message and will get back to you as soon as possible.<br><br>
            <strong>Your message details:</strong><br>
            <strong>Subject:</strong> $subject<br>
            <strong>Message:</strong><br>" . nl2br($message) . "<br><br>
            Best regards,<br>
            Owner @ User Profile Application
        ";

        try{
        $autoReply->send(); // Send auto-reply to user - Try to send auto-reply
        // Auto-reply sent successfully
            header("Location: contact.php?success=1");
            exit();    
        }catch (Exception $e) {
            // Auto-reply failed (invalid or fake email)
            header("Location: contact.php?success=1&warning=auto_reply_failed");
            exit();
        }

        // echo "Message sent successfully!";
        // Redirect to form with success flag


        // header("Location: contact.php?success=1");
        // exit();
    } catch (Exception $e) {
        // echo "Mailer Error: {$mail->ErrorInfo}";
        // Redirect with error flag

         // Mailer to site owner failed
        header("Location: contact.php?error=mailer");
        exit();
    }
} else {
    // echo "Invalid request.";
    header("Location: contact.php?error=invalid_request");
    exit();
}
