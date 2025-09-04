<?php
$pageTitle = "Users List";
require("./functions.php");
require("./header.php");
checkLogin();
?>

<h1>Users List</h1>

<!-- 🔍 AJAX Search Box -->
<div style="text-align: center; margin-bottom: 20px;">
    <input type="text" id="searchBox" placeholder="Search by name or email" style="width: 300px; padding: 5px;" />
</div>

<!-- ⬇️ Export to CSV -->
<form method="GET" action="exportUsers.php" style="text-align: center; margin-bottom: 20px;">
    <input type="hidden" name="search" id="exportSearch">
    <button type="submit">⬇️ Export to CSV</button>
</form>

<!-- 🧾 Users Table -->
<table border="1" cellspacing="0" cellpadding="10" style="width: 80%; margin: 0 auto; text-align: center;">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>User Name</th>
            <th>User Email</th>
            <th>Registration Date and Time</th>
        </tr>
    </thead>
    <tbody id="userTableBody">
        <!-- Filled by AJAX -->
    </tbody>
</table>

<script>
    // Function to fetch user data
    function fetchUsers(query = '') {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetchUsers.php?search=' + encodeURIComponent(query), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('userTableBody').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Load all users on page load
    fetchUsers();

    // Search while typing
    // document.getElementById('searchBox').addEventListener('keyup', function() {
    //     const query = this.value;
    //     fetchUsers(query);
    // });

    // Update users while typing
const searchInput = document.getElementById('searchBox');
const exportInput = document.getElementById('exportSearch');

searchInput.addEventListener('input', function () {
    const query = this.value;
    fetchUsers(query);
    exportInput.value = query; // Sync export with search
});
</script>

<?php require("./footer.php"); ?>
