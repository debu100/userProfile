<?php
$pageTitle = "Reset Password";
require("./functions.php");
require("./header.php");

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$sql = "SELECT * FROM users WHERE reset_token_hash = ?";

$stmt = mysqli_prepare($con, $sql); // ✅ changed $mysqli to $con

mysqli_stmt_bind_param($stmt, "s", $token_hash);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt); // ✅ procedural version
$user = mysqli_fetch_assoc($result);     // ✅ procedural version

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>

    <h1>Reset Password</h1>

    <form method="post" action="process-reset-password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">Repeat password</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation">

        <button>Send</button>
    </form>

<?php require("./footer.php");?>