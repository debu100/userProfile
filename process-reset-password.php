<?php
$pageTitle = "Process Reset";
require("./functions.php");
require("./header.php");

$errors = [];
$token = $_POST["token"] ?? "";

if (!$token) {
    $errors[] = "Invalid request. Token missing.";
} else {
    $token_hash = hash("sha256", $token);

    $sql = "SELECT * FROM users WHERE reset_token_hash = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token_hash);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        $errors[] = "Invalid or expired reset link.";
    } elseif (strtotime($user["reset_token_expires_at"]) <= time()) {
        $errors[] = "Reset token has expired. Please request a new one.";
    }
}

$password = $_POST["passwordSet"] ?? "";
$password_confirmation = $_POST["password_confirmation"] ?? "";

// Validate password
if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
}
if (!preg_match("/[A-Z]/", $password)) {
    $errors[] = "Password must contain at least one uppercase letter.";
}
if (!preg_match("/[a-z]/", $password)) {
    $errors[] = "Password must contain at least one lowercase letter.";
}
if (!preg_match("/[0-9]/", $password)) {
    $errors[] = "Password must contain at least one digit.";
}
if (!preg_match("/[!@#$&]/", $password)) {
    $errors[] = "Password must contain at least one special character (!, @, #, $, &).";
}
if ($password !== $password_confirmation) {
    $errors[] = "Passwords do not match.";
}

if (!empty($errors)) {
    // Show errors and re-display the form
    echo "<div style='max-width: 600px; margin: 80px auto; padding: 20px; background: #ffe6e6; border-radius: 8px;'>";
    echo "<h2 style='color: #c0392b;'>Error</h2>";
    echo "<ul style='color: #e74c3c;'>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
    echo "<a href='reset-password.php?token=" . urlencode($token) . "' style='display:inline-block; margin-top: 20px; padding: 10px 15px; background-color: #e74c3c; color: #fff; border-radius: 5px; text-decoration: none;'>Go Back</a>";
    echo "</div>";
    require("./footer.php");
    exit;
}

// Update password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET user_passowrd = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "si", $password_hash, $user["id"]);
mysqli_stmt_execute($stmt);
?>

<div style="max-width: 600px; margin: 80px auto; padding: 40px; background: #f0f8ff; border-radius: 10px; text-align: center; font-family: 'Arial', sans-serif;">
    <h2 style="color: #27ae60;">✔ Password Successfully Reset</h2>
    <p style="font-size: 18px; color: #2c3e50;">
        Your password has been updated securely.
        <br>You can login with the newly set Password.
    </p>
    <a href="login.php" style="margin-top: 20px; display: inline-block; padding: 10px 20px; background-color: #2980b9; color: white; border-radius: 5px; text-decoration: none; font-size: 16px;">
        Go to Login Now
    </a>
</div>

<?php require './footer.php'; ?>
