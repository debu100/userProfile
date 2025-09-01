<?php
$pageTitle = "Users List";
require("./functions.php");
require("./header.php");

// Check if the user is logged in
checkLogin();

// Prepare and execute query securely
$stmt = $con->prepare("SELECT user_name, user_email, reg_date FROM users ORDER BY reg_date DESC");
$stmt->execute();
$result = $stmt->get_result();

// Fetch all users from the database, including the registration date
// $query = "SELECT user_name, user_email, reg_date FROM users";
// $result = mysqli_query($con, $query);

?>

<h1>Users List</h1>

<?php if($result && $result->num_rows > 0):?>
    <table border="1" cellspacing="0" cellpadding="10" style="width: 80%; margin: 20px auto; text-align: center;">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>Registration Date and Time</th>  <!-- Add Registration Date Column -->
            </tr>
        </thead>
        <tbody>
            <?php 
            $sr_no = 1;  // Start serial number at 1
            while($row = mysqli_fetch_assoc($result)):
                // Sanitize output
                $name = htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8');
                $email = htmlspecialchars($row['user_email'], ENT_QUOTES, 'UTF-8');
                $reg_date = htmlspecialchars(date('l, F j, Y h:i A', strtotime($row['reg_date'])), ENT_QUOTES, 'UTF-8');
            ?>
                <tr>
                    <td><?php echo $sr_no++; ?></td>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $reg_date; ?></td> <!-- Format the registration date -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else:?>
    <p>No users found.</p>
<?php endif; ?>

<?php
require("./footer.php");
?>
