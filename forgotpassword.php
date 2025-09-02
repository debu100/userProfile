<?php
$pageTitle = "Forgot Password";
require("./functions.php");
require "./header.php";
?>

<h1>Forgot Password</h1>
<form method="post" action="sendpasswordreset.php">
	<label for="email">Email</label>
	<input type="email" name="email" id="email">																
	<button>Send</button>		
</form>

<?php
require "./footer.php";
?>