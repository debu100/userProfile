<?php
$pageTitle = "Edit Your Profile";
require("./functions.php");
require("./header.php");
checkLogin();

$user_id = $_SESSION['logged']['user_id'];

// Fetch current user data from DB
$query = "SELECT user_name, user_email, profile_image FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $current_name, $current_email, $current_image);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['editusername']);
    $new_email = trim($_POST['editemail']);
    $new_password = trim($_POST['editpassword']);
    $image_path = $current_image;

  // Backend validation

    if (!preg_match('/^[a-zA-Z\s]{3,}$/', $new_name)) {
        $errors[] = "Username must be at least 3 characters and contain only letters and spaces.";
    }

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $new_email)) {
        $errors[] = "Invalid email format.";
    }else {
    // ✅ Check for duplicate email (excluding current user)
    $check_email = "SELECT id FROM users WHERE user_email = ? AND id != ?";
    $stmt = mysqli_prepare($con, $check_email);
    mysqli_stmt_bind_param($stmt, "si", $new_email, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "This email is already registered with another account.";
    }
    mysqli_stmt_close($stmt);
}

    // Validate password only if filled
    if (!empty($new_password)) {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$&*]).{8,}$/', $new_password)) {
            $errors[] = "Password must be at least 8 characters, include one uppercase, one lowercase, one number, and one special character (!@#$&*).";
        }
    }

    // Handle profile image upload
    if (isset($_FILES['editprofileimage']) && $_FILES['editprofileimage']['error'] === 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['editprofileimage']['name'];
        $file_tmp = $_FILES['editprofileimage']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

if (in_array($file_ext, $allowed_ext)) {
    // ✅ Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file_tmp);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime, $allowed_mimes)) {
        $errors[] = "Invalid image MIME type.";
    } else {
        $new_file_name = uniqid("profile_", true) . "." . $file_ext;
        $upload_path = "uploads/" . $new_file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $image_path = $upload_path;
        } else {
            $errors[] = "Failed to upload profile image.";
        }
    }
}   else {
            $errors[] = "Invalid image format. Allowed types: jpg, jpeg, png, gif.";
        }

    }

    // If no errors, update user info
    if (empty($errors)) {
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET user_name = ?, user_email = ?, user_passowrd = ?, profile_image = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $update_sql);
            mysqli_stmt_bind_param($stmt, "ssssi", $new_name, $new_email, $hashed_password, $image_path, $user_id);
        } else {
            $update_sql = "UPDATE users SET user_name = ?, user_email = ?, profile_image = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $update_sql);
            mysqli_stmt_bind_param($stmt, "sssi", $new_name, $new_email, $image_path, $user_id);
        }

        if (mysqli_stmt_execute($stmt)) {
            // Update session
            $_SESSION['logged']['user_name'] = $new_name;
            $_SESSION['logged']['user_email'] = $new_email;
            $_SESSION['logged']['profile_image'] = $image_path;

            // Redirect back to profile page with success flag
            header("Location: profile.php?updated=1");
            exit;
        } else {
            $errors[] = "Error updating profile. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<h1>Edit Your Profile</h1>

<?php if (!empty($errors)): ?>
    <div style="color: red; margin: 1rem 0; padding: 10px; border: 1px solid red; background: #ffecec; text-align: center;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" style="max-width: 500px; margin: auto; padding-bottom: 2.75rem;">
    <label for="editusername">Username:</label><br>
    <input type="text" name="editusername" id="editusername" value="<?= htmlspecialchars($current_name) ?>" required><br><br>

    <label for="editemail">Email:</label><br>
    <input type="email" name="editemail" id="editemail" value="<?= htmlspecialchars($current_email) ?>" required><br><br>

    <label for="editpassword">New Password (leave blank to keep current):</label><br>
    <input type="password" name="editpassword" id="editpassword"><br><br>

    <label for="editprofileimage">Profile Image:</label><br>
    <?php if ($current_image): ?>
        <img src="<?= htmlspecialchars($current_image) ?>" width="150" alt="Current Image"><br>
    <?php endif; ?>
    <input type="file" name="editprofileimage" id="editprofileimage" accept="image/*"><br><br>

    <button type="submit">Update Profile</button>
</form>

<?php require("./footer.php"); ?>
