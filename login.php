<?php
$pageTitle = "Login";
require("./functions.php");
require("./header.php");
$errorMsg = '';
$success = false;  // Flag to indicate a successful login
// Prefill values from cookies
$rememberedEmail = isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : '';
// $rememberedPassword = isset($_COOKIE['remember_password']) ? base64_decode($_COOKIE['remember_password']) : '';
$rememberedPassword = isset($_COOKIE['remember_password']) ? decrypt($_COOKIE['remember_password']) : '';

if ($_SERVER['REQUEST_METHOD']==="POST") {
	// $email = trim(addslashes($_POST['email']));
	// $password = md5(trim(addslashes($_POST['password'])));
	 // Sanitize inputs
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);


        // === BACKEND VALIDATION ===
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Please enter a valid email address.";
    }
    // Password: at least 6 chars with upper, lower, digit, special
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $password)) {
        $errorMsg = "Please enter a valid password";
    }else{


    // Use prepared statement to fetch user by email
    $stmt = $con->prepare("SELECT * FROM users WHERE user_email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

	// $select_query = "SELECT * FROM users WHERE user_email = '$email' AND user_passowrd = '$password' LIMIT 1; ";
	// $result = mysqli_query($con,$select_query);
	// if($result){
	// 	print_r($result);
	// 	print_r(mysqli_num_rows($result)); 0 / 1
	// }

	if ($result && mysqli_num_rows($result)>0) {
		// saving the data for the logged in user
		$row = mysqli_fetch_assoc($result);
		// Verify hashed password
        if (password_verify($password, $row['user_passowrd'])) { // Note: check column name
            
            // $_SESSION['logged'] = $row;

            // ✅ EXPLICITLY set session fields
                $_SESSION['logged'] = [
                    'user_id' => $row['id'],
                    'user_name' => $row['user_name'],
                    'user_email' => $row['user_email'],
                    'profile_image' => $row['profile_image'] ?? null
                ];
            $success = true;
                            // Handle Remember Me
                if (isset($_POST['remember_me'])) {
                    // Set cookies for 30 days
                    setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), "/");
                    // setcookie('remember_password', base64_encode($password), time() + (30 * 24 * 60 * 60), "/");
                    setcookie('remember_password', encrypt($password), time() + (30 * 24 * 60 * 60), "/", "", true, true);

                } else {
                    // Clear cookies
                    setcookie('remember_email', '', time() - 3600, "/");
                    setcookie('remember_password', '', time() - 3600, "/");
                }
        } else {
            $errorMsg = 'Wrong Email or Password';
        }
		// print_r($row);
		// saving the above data in every single page we need to use SESSION
		// $_SESSION['logged'] = $row; // logged is just a key
		// $success = true;  // Set success to true to show the modal

		// header('Location:./profile.php');
		// exit();	
	}else{
		$errorMsg = 'Wrong Email or Password';
	}
	
}
}
?>
<h1>Login</h1>

<?php if ($success) { ?>
    <script>
        window.onload = function() {
            // Show the success modal
            document.getElementById('successModal').style.display = 'block';
            setTimeout(function() {
                window.location.href = './profile.php'; // Redirect to profile after 3 seconds
            }, 2000); // 3000ms = 3 seconds
        }
    </script>
<?php } ?>

<form method="post">
	<div><input type="email" name="email" placeholder="Enter Email ID" value="<?php echo htmlspecialchars($rememberedEmail); ?>" required></div>
	<div><input type="password" name="password" placeholder="Enter Password" value="<?php echo htmlspecialchars($rememberedPassword); ?>" required></div>
    <div style="display:flex; justify-content: space-between; align-items: center;">
<div class="remember-me">
    <input type="checkbox" name="remember_me" id="remember_me" <?php if ($rememberedEmail) echo 'checked'; ?>>
    <label for="remember_me">Remember Me</label>
</div>
<a style="color:#333;" href="forgotpassword.php">Forgot Password ?</a>
</div>
	<div><button type="submit">Login</button></div>
</form>
<div style="text-align:center;margin: 1rem 0;color: red;"><?php echo $errorMsg; ?></div>

<!-- Success Modal -->
<div id="successModal" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #4CAF50; color: white; padding: 20px; border-radius: 10px; text-align: center;">
    <h2>Login Successful!</h2>
    <p>You will be redirected to your profile shortly.</p>
</div>
<?php
require("./footer.php");
?>