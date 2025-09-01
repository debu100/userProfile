<?php
$pageTitle = "Signup";
require("./functions.php");
require("./header.php");

if ($_SERVER['REQUEST_METHOD']==="POST") {
	// $user_name = trim(addslashes($_POST['user_name']));
	// $user_email = trim(addslashes($_POST['user_email']));
	// $user_password = md5(trim(addslashes($_POST['user_password'])));
	// $date = date('Y/m/d H:i:s');

    // Sanitize inputs
    $user_name = htmlspecialchars(trim($_POST['user_name']), ENT_QUOTES, 'UTF-8');
    $user_email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $user_password_raw = $_POST['user_password']; // raw password input
    $user_password = password_hash(trim($user_password_raw), PASSWORD_DEFAULT); // securely hashed
    $date = date('Y/m/d H:i:s');

    // === BACKEND VALIDATION ===
    // Name: letters and spaces only, min 3 characters
    if (!preg_match('/^[A-Za-z\s]{3,}$/', $user_name)) {
        $errorMsg = "Name must be at least 3 letters and contain only letters and spaces.";
    }
    // Email: proper format
    elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email address.";
    }
    // Password: at least 6 characters, with upper, lower, number, special
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $user_password_raw)) {
        $errorMsg = "Password must be at least 6 characters and include uppercase, lowercase, number, and special character.";
    }else{
        $user_password = password_hash($user_password_raw, PASSWORD_DEFAULT);



	// Check if email already exists
    // $check_query = "SELECT * FROM users WHERE user_email = '$user_email' LIMIT 1";
    // $check_result = mysqli_query($con, $check_query);

    // Check if email already exists (using prepared statement)
    $stmt = $con->prepare("SELECT * FROM users WHERE user_email = ? LIMIT 1");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
        // If the email already exists, show an error message
        $errorMsg = "Email is already taken. Please choose another one.";
    } else {
        // If email doesn't exist, insert the new user
        // $insert_query = "INSERT INTO users(user_name, user_email, user_passowrd, reg_date) VALUES ('$user_name','$user_email','$user_password','$date')";
        // $result = mysqli_query($con, $insert_query);

        // Insert the new user securely
        $stmt = $con->prepare("INSERT INTO users (user_name, user_email, user_passowrd, reg_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $user_name, $user_email, $user_password, $date);
        $result = $stmt->execute();

        if ($result) {
            // header('Location: ./login.php');
              $success = true;
            // exit();
        } else {
            $errorMsg = "Something went wrong. Please try again later.";
        }
    }
}
	// $insert_query = "INSERT INTO users(user_name, user_email, user_passowrd, reg_date) VALUES ('$user_name','$user_email','$user_password','$date')";
	// $result = mysqli_query($con,$insert_query);
	// header('Location:./login.php');
	// exit();
}
?>
<h1>Signup</h1>
<?php if (isset($errorMsg)) { ?>
    <div style="text-align:center; margin: 1rem 0; color: red;"><?php echo $errorMsg; ?></div>
<?php } ?>
<?php if (isset($success) && $success) { ?>
    <script>
        window.onload = function() {
            // Show the success modal
            document.getElementById('successModal').style.display = 'block';
            setTimeout(function() {
                window.location.href = './login.php'; // Redirect to login after 2 seconds
            }, 2000); // 3000ms = 3 seconds
        }
    </script>
<?php } ?>
<form method="post">
	<div><input type="text" name="user_name" placeholder="Enter User Name" required></div>
	<div><input type="email" name="user_email" placeholder="Enter Email ID" required></div>
	<div><input type="password" name="user_password" placeholder="Enter Password" required></div>
	<div><button type="submit">Signup</button></div>
</form>

<!-- Modal -->
<div id="successModal" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #4CAF50; color: white; padding: 20px; border-radius: 10px; text-align: center;">
    <h2>Registration Successful!</h2>
    <p>You will be redirected to the login page shortly.</p>
</div>

<?php
require("./footer.php");
?>