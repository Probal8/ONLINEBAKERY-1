<?php
include '../Model/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Deleting from users table
    $sql = "DELETE FROM users WHERE id = '$id' AND role = 'Customer'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: admin.php?page=customers");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>