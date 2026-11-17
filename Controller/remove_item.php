<?php
include '../Model/db.php';
session_start();

if(isset($_GET['id'], $_GET['action'], $_SESSION['email'])) {
    $p_id = $_GET['id'];
    $action = $_GET['action'];
    $email = $_SESSION['email'];
    
  
    $user_res = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($user_res);
    $u_id = $user['id'];

    if($action == 'plus') {
      
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$u_id' AND product_id = '$p_id'"); 
    } else if($action == 'minus') {
  
        $check_qty = mysqli_query($conn, "SELECT quantity FROM cart WHERE user_id = '$u_id' AND product_id = '$p_id'");
        $row = mysqli_fetch_assoc($check_qty);
        
        if($row && $row['quantity'] > 1) {
            
            mysqli_query($conn, "UPDATE cart SET quantity = quantity - 1 WHERE user_id = '$u_id' AND product_id = '$p_id'");
        } else {
        
            mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$u_id' AND product_id = '$p_id'");
        }
    }
}
?>