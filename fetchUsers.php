<?php
require("./functions.php");
checkLogin();

$search = isset($_GET['search']) ? trim($_GET['search']) : "";


if (!empty($search)) {
    $search_term = "%" . $search . "%";
    $stmt = $con->prepare("SELECT user_name, user_email, reg_date FROM users 
                           WHERE user_name LIKE ? OR user_email LIKE ?
                           ORDER BY reg_date DESC");
    $stmt->bind_param("ss", $search_term, $search_term);
} else {
    $stmt = $con->prepare("SELECT user_name, user_email, reg_date FROM users ORDER BY reg_date DESC");
}

$stmt->execute();
$result = $stmt->get_result();

$sr_no = 1;

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $name = htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($row['user_email'], ENT_QUOTES, 'UTF-8');
        $reg_date = htmlspecialchars(date('l, F j, Y h:i A', strtotime($row['reg_date'])), ENT_QUOTES, 'UTF-8');

        echo "<tr>
                <td>{$sr_no}</td>
                <td>{$name}</td>
                <td>{$email}</td>
                <td>{$reg_date}</td>
              </tr>";
        $sr_no++;
    }
} else {
    echo "<tr><td colspan='4'>No users found.</td></tr>";
}
