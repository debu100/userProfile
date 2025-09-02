<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'My Website'; ?></title>
	<link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>
<header>
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="profile.php">Profile</a></li>
			<li><a href="contact.php">Contact</a></li>
			<?php if (empty($_SESSION['logged'])):?>
			<li><a href="login.php">Login</a></li>
			<li><a href="signup.php">Signup</a></li>				
			<?php else:?>
			<li><a href="logout.php">Logout</a></li>
			<?php endif;?>
		</ul>
	</nav>
</header>