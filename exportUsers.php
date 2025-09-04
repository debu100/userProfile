<?php
require("./functions.php");
checkLogin();

// Set headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="users_list.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['User Name', 'User Email', 'Registration Date']);

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

while($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['user_name'], $row['user_email'], $row['reg_date']]);
}

fclose($output);
exit;
