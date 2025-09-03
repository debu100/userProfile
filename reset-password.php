<?php
$pageTitle = "Reset Password";
require("./functions.php");
require("./header.php");

$error = "";
$token = $_GET["token"] ?? "";

if (!$token) {
    $error = "Invalid token.";
} else {
    $token_hash = hash("sha256", $token);

    $sql = "SELECT * FROM users WHERE reset_token_hash = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token_hash);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        $error = "Invalid or expired reset link.";
    } elseif (strtotime($user["reset_token_expires_at"]) <= time()) {
        $error = "Reset token has expired. Please request a new one.";
    }
}
?>

<h1>Reset Password</h1>

<?php if ($error): ?>
    <div style="color: red; margin-bottom: 20px;"><?= htmlspecialchars($error) ?></div>
<?php elseif ($user): ?>
    <form method="post" action="process-reset-password.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New Password</label>
        <input type="password" id="password" name="passwordSet" required>

        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">Reset Password</button>
    </form>
<?php endif; ?>

<?php require("./footer.php"); ?>
