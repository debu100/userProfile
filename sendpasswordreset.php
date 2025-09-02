<?php
$pageTitle = "Password Reset";
require("./functions.php");
require("./header.php");

$email = $_POST['email'];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);
// ✅ Use $con and procedural style
$sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE user_email = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "sss", $token_hash, $expiry, $email);
mysqli_stmt_execute($stmt);

if (mysqli_affected_rows($con) > 0) {

    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom($_ENV['EMAIL_USERNAME'], "User Profile App");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="http://localhost/user_profile/reset-password.php?token=$token">here</a>
    to reset your password.

    END;

    try {

        $mail->send();
        $emailSent = true;

    } catch (Exception $e) {
    	$emailSent = false;
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";

    }

}else {
    $emailSent = false;
    $errorMessage = "No user found with that email.";
}

// echo "Message sent, please check your inbox.";
?>


<div style="max-width: 600px; margin: 80px auto; padding: 40px; background: #f4f4f4; border-radius: 10px; text-align: center; font-family: 'Arial', sans-serif;">
    <?php if ($emailSent): ?>
        <h2 style="color: #2ecc71;">✔ Email Sent</h2>
        <p style="font-size: 18px; color: #333;">
            A password reset link has been sent to your email address.
            <br>Please check your inbox and follow the instructions.
        </p>
        <a href="login.php" style="margin-top: 20px; display: inline-block; padding: 10px 20px; background-color: #3498db; color: white; border-radius: 5px; text-decoration: none; font-size: 16px;">
            Back to Login
        </a>
    <?php else: ?>
        <h2 style="color: #e74c3c;">✖ Failed to Send</h2>
        <p style="font-size: 18px; color: #333;">
            <?= htmlspecialchars($errorMessage) ?>
        </p>
        <a href="forgot-password.php" style="margin-top: 20px; display: inline-block; padding: 10px 20px; background-color: #e74c3c; color: white; border-radius: 5px; text-decoration: none; font-size: 16px;">
            Try Again
        </a>
    <?php endif; ?>
</div>

<?php require("./footer.php"); ?>