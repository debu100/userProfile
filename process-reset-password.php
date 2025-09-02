<?php
$pageTitle = "Process Reset";
require("./functions.php");
require("./header.php");

$token = $_POST["token"];

$token_hash = hash("sha256", $token);


$sql = "SELECT * FROM users WHERE reset_token_hash = ?";

$stmt = mysqli_prepare($con, $sql); // ✅

mysqli_stmt_bind_param($stmt, "s", $token_hash);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt); // ✅
$user = mysqli_fetch_assoc($result);     // ✅

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}


$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE users SET user_passowrd = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?";

$stmt = mysqli_prepare($con, $sql); // ✅

mysqli_stmt_bind_param($stmt, "si", $password_hash, $user["id"]); // ✅ changed to 'si' (string, int)
mysqli_stmt_execute($stmt);


// echo "Password updated. You can now login.";
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


<?php require './footer.php' ?>