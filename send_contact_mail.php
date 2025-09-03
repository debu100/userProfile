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
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['EMAIL_USERNAME'];       // Your email
        $mail->Password   = $_ENV['EMAIL_PASSWORD'];         // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email headers
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

        $mail->send();
        // echo "Message sent successfully!";
        // Redirect to form with success flag
        header("Location: contact.php?success=1");
        exit();
    } catch (Exception $e) {
        // echo "Mailer Error: {$mail->ErrorInfo}";
        // Redirect with error flag
        header("Location: contact.php?error=mailer");
        exit();
    }
} else {
    // echo "Invalid request.";
    header("Location: contact.php?error=invalid_request");
    exit();
}
