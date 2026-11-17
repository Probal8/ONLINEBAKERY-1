<?php
include '../Model/db.php';
session_start();

// login check
if(!isset($_SESSION['email'])) { 
    header("Location: ../View/login.php"); 
    exit(); 
}

$u_email = $_SESSION['email'];
$u_res = mysqli_query($conn, "SELECT id FROM users WHERE email='$u_email'");
$u_data = mysqli_fetch_assoc($u_res);
$u_id = $u_data['id'];

// Form data receive
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name   = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone           = mysqli_real_escape_string($conn, $_POST['phone']);
    $address         = mysqli_real_escape_string($conn, $_POST['address']);
    $delivery_charge = intval($_POST['location']); 
    $payment_method  = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $note            = mysqli_real_escape_string($conn, $_POST['note']);
    
    // Transaction ID & Payment Phone receive (Notun add kora hoyeche)
    $trx_id_raw = isset($_POST['trx_id']) ? mysqli_real_escape_string($conn, $_POST['trx_id']) : '';
    $payment_phone = isset($_POST['payment_phone']) ? mysqli_real_escape_string($conn, $_POST['payment_phone']) : '';

    // TrxID er ghore Phone Number + TrxID eksathe rakha hochche jate admin bujhte pare
    $trx_id = "";
    if(!empty($payment_phone) || !empty($trx_id_raw)){
        $trx_id = "Sender: " . $payment_phone . " | Trx: " . $trx_id_raw;
    }

    // Cart details fetch
    $cart_res = mysqli_query($conn, "SELECT p.*, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$u_id'");
    
    $subtotal = 0;
    $items = [];
    while($row = mysqli_fetch_assoc($cart_res)) {
        $subtotal += ($row['price'] * $row['quantity']);
        $items[] = $row; 
    }

    $grand_total = $subtotal + $delivery_charge;

    if(count($items) > 0) {
       

        // 1. Insert into orders table (Including trx_id and sender phone info)
        $order_sql = "INSERT INTO orders (user_id, customer_name, phone, address, total_amount, delivery_charge, payment_method, trx_id, note, status) 
                      VALUES ('$u_id', '$customer_name', '$phone', '$address', '$grand_total', '$delivery_charge', '$payment_method', '$trx_id', '$note', 'Pending')";
        
        if(mysqli_query($conn, $order_sql)) {
            $new_order_id = mysqli_insert_id($conn); 

            // 2. Insert into order_items table
            foreach($items as $item) {
                $p_id = $item['id'];
                $qty = $item['quantity'];
                $price = $item['price'];
                mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                     VALUES ('$new_order_id', '$p_id', '$qty', '$price')");
            }

            // 3. Clear Cart
            mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$u_id'");

            // 4. Redirect to success page
            header("Location: ../View/my_orders.php?order=success");
            exit();
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>