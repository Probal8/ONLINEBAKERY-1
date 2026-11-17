<?php
include '../Model/db.php';
session_start();


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../View/login.php");
    exit();
}

if (isset($_POST['update'])) {
  
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    // Database update query
    $query = "UPDATE orders SET status = '$new_status' WHERE id = '$order_id'";

    if (mysqli_query($conn, $query)) {
      
        header("Location: ../View/admin.php?msg=Status Updated Successfully");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    header("Location: ../View/admin.php");
}
?>