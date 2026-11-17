<?php
include '../Model/db.php';

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    if ($type == 'user') {
        $sql = "DELETE FROM users WHERE id = '$id' AND role = 'Customer'";
        $redirect = "admin.php?page=customers";
    } else if ($type == 'product') {
        // Soft delete logic: avoids "foreign key constraint fails" error
        $sql = "UPDATE products SET is_deleted = 1 WHERE id = '$id'";
        $redirect = "admin.php?page=products";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: $redirect");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>