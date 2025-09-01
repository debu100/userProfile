<?php
$pageTitle = "Your Profile";
require("./functions.php");
require("./header.php");
checkLogin();
// Sanitize session output before displaying
$user_name = htmlspecialchars($_SESSION['logged']['user_name'], ENT_QUOTES, 'UTF-8');
$user_email = htmlspecialchars($_SESSION['logged']['user_email'], ENT_QUOTES, 'UTF-8');
?>
<h1>Profile</h1>
<div style="text-align: center;;margin-top: 1.5rem;"><h3>Profile Image:</h3><img src="img.jpg"></div>
<div style="text-align: center;;margin: 1.5rem 0;">
	<h3>User Name: <strong><?php echo $user_name;?></strong></h3>
	<h3>User Email: <strong><?php echo $user_email;?></strong></h3>
</div>
<form method="post">
	<div style="text-align: center">
	<textarea rows="9" cols="50" placeholder="What's on your mind!!!..."></textarea>
	</div>
	<div>
	<button type="submit">Post</button>
	</div>
</form>
<?php
require("./footer.php");
?>