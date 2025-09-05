<?php
$pageTitle = "Your Profile";
require("./functions.php");
require("./header.php");
checkLogin();
// Sanitize session output before displaying
$user_name = htmlspecialchars($_SESSION['logged']['user_name'], ENT_QUOTES, 'UTF-8');
$user_email = htmlspecialchars($_SESSION['logged']['user_email'], ENT_QUOTES, 'UTF-8');
?>

<!-- ✅ Show success message if profile was updated -->
<?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
    <div style="color: green; text-align: center; margin-top: 1rem;">Profile updated successfully!</div>
<?php endif; ?>


<h1>Profile</h1>
<div style="text-align: center;;margin-top: 1.5rem;"><h3>Profile Image:</h3><?php $profile_image = htmlspecialchars($_SESSION['logged']['profile_image'] ?? 'img.jpg', ENT_QUOTES, 'UTF-8');
?>
<img src="<?php echo $profile_image ?>" alt="Profile Image" width="150">
</div>

<div style="text-align: center;;margin: 1.5rem 0;">
	<h3>User Name: <strong><?php echo $user_name;?></strong></h3>
	<h3>User Email: <strong><?php echo $user_email;?></strong></h3>
</div>

<div style="text-align: center; margin-top: 2rem;">
    <form action="edit_profile.php" method="get">
        <button type="submit">Edit Profile</button>
    </form>
</div>

<h2 style="text-align: center;;margin-top: 1.5rem;">Create a Post</h2>
<form method="post" style="	padding-bottom: 2rem;">
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