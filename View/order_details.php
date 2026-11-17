<?php 
include '../Model/db.php';
session_start();


if(!isset($_SESSION['email']) || !isset($_GET['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$order_id = $_GET['id'];
$u_email = $_SESSION['email'];


$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id'");
$order_info = mysqli_fetch_assoc($order_query);


$u_res = mysqli_query($conn, "SELECT id FROM users WHERE email='$u_email'");
$u_id = mysqli_fetch_assoc($u_res)['id'];
if($order_info['user_id'] != $u_id) {
    echo "Unauthorized access!";
    exit();
}


$items_res = mysqli_query($conn, "SELECT p.name, p.image, p.price, oi.quantity 
                                 FROM order_items oi 
                                 JOIN products p ON oi.product_id = p.id 
                                 WHERE oi.order_id = '$order_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #fdfaf6; font-family: 'Segoe UI', Tahoma, sans-serif; padding: 20px; color: #333; }
        .details-card { max-width: 800px; margin: 20px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.1); }
        
        .header-flex { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .order-id-tag { background: #d32f2f; color: white; padding: 5px 15px; border-radius: 5px; font-weight: bold; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-box h4 { margin: 0 0 10px 0; color: #d32f2f; text-transform: uppercase; font-size: 14px; }
        .info-box p { margin: 2px 0; font-size: 14px; line-height: 1.5; }

        .item-row { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #f5f5f5; }
        .item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; margin-right: 15px; border: 1px solid #eee; }
        
        .calculation-section { margin-top: 25px; border-top: 2px solid #eee; padding-top: 15px; }
        .calc-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 15px; }
        .grand-total-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 22px; font-weight: bold; color: #d32f2f; border-top: 1px dashed #ccc; padding-top: 10px; }
        
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .Pending { background: #fff3cd; color: #856404; }
        .Delivered { background: #d4edda; color: #155724; }
        
        .back-btn { display: inline-block; margin-top: 30px; color: #777; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .back-btn:hover { color: #d32f2f; }
        
        @media print { .back-btn { display: none; } .details-card { box-shadow: none; border: 1px solid #eee; } }
    </style>
</head>
<body>

    <div class="details-card">
        <div class="header-flex">
            <div>
                <span class="order-id-tag">Order #<?php echo $order_id; ?></span>
                <p style="margin: 10px 0 0 0; font-size: 13px; color: #888;">Placed on: <?php echo date('d M Y, h:i A', strtotime($order_info['created_at'])); ?></p>
            </div>
            <div class="status-badge <?php echo $order_info['status']; ?>">
                Status: <?php echo $order_info['status']; ?>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h4><i class="fas fa-user"></i> Customer Info</h4>
                <p><strong>Name:</strong> <?php echo $order_info['customer_name']; ?></p>
                <p><strong>Phone:</strong> <?php echo $order_info['phone']; ?></p>
                <p><strong>Payment:</strong> <?php echo $order_info['payment_method']; ?></p>
            </div>
            <div class="info-box">
                <h4><i class="fas fa-map-marker-alt"></i> Shipping Address</h4>
                <p><?php echo nl2br($order_info['address']); ?></p>
                <?php if(!empty($order_info['note'])): ?>
                    <p style="margin-top:5px; color:#666;"><strong>Note:</strong> <?php echo $order_info['note']; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <h4 style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Ordered Items</h4>
        
        <?php 
        $items_subtotal = 0;
        while($item = mysqli_fetch_assoc($items_res)) { 
            $line_total = $item['quantity'] * $item['price'];
            $items_subtotal += $line_total;
        ?>
            <div class="item-row">
                <img src="../uploads/<?php echo $item['image']; ?>" class="item-img">
                <div style="flex-grow: 1;">
                    <h4 style="margin: 0; font-size: 15px;"><?php echo $item['name']; ?></h4>
                    <p style="margin: 5px 0; color: #777; font-size: 13px;">BDT <?php echo number_format($item['price'], 2); ?> x <?php echo $item['quantity']; ?></p>
                </div>
                <div style="font-weight: 600;">BDT <?php echo number_format($line_total, 2); ?></div>
            </div>
        <?php } ?>

        <div class="calculation-section">
            <div class="calc-row">
                <span>Subtotal:</span>
                <span>BDT <?php echo number_format($items_subtotal, 2); ?></span>
            </div>
            <div class="calc-row">
                <span>Delivery Charge:</span>
                <span>BDT <?php echo number_format($order_info['delivery_charge'], 2); ?></span>
            </div>
            <div class="grand-total-row">
                <span>Grand Total:</span>
                <span>BDT <?php echo number_format($order_info['total_amount'], 2); ?></span>
            </div>
        </div>

        <a href="my_orders.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to My Orders</a>
        <button onclick="window.print()" style="float: right; margin-top: 25px; background: none; border: 1px solid #ccc; padding: 5px 10px; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Print Invoice
        </button>
        <div style="clear: both;"></div>
    </div>

</body>
</html>