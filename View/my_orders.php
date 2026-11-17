<?php 
include '../Model/db.php';
session_start();


if(!isset($_SESSION['email'])) { 
    header("Location: login.php"); 
    exit(); 
}

$u_email = $_SESSION['email'];


$u_res = mysqli_query($conn, "SELECT id FROM users WHERE email='$u_email'");
$u_id = mysqli_fetch_assoc($u_res)['id'];


$res = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$u_id' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Baking Valley</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        
        body { background: #fdfaf6; font-family: 'Poppins', sans-serif; margin: 0; padding: 20px; }
        .order-container { max-width: 1000px; margin: 40px auto; background: white; padding: 35px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #d32f2f; padding-bottom: 15px; margin-bottom: 30px; }
        .header h2 { color: #d32f2f; margin: 0; text-transform: uppercase; }
        
        /* Success Message */
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 5px solid #28a745; display: flex; align-items: center; gap: 10px; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f8f8; color: #333; padding: 15px; text-align: left; font-size: 14px; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 14px; color: #444; }
        
        .status { padding: 5px 12px; border-radius: 15px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .status-Pending { background: #fff3cd; color: #856404; }
        .status-Delivered { background: #d4edda; color: #155724; }
        .status-Cancelled { background: #f8d7da; color: #721c24; }
        
        .view-btn { background: #d32f2f; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 12px; transition: 0.3s; }
        .view-btn:hover { background: #b71c1c; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        
        .back-link { display: inline-block; margin-top: 25px; color: #d32f2f; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }

        @media (max-width: 600px) {
            .order-container { padding: 15px; }
            table thead { display: none; }
            table td { display: block; text-align: right; position: relative; padding-left: 50%; }
            table td::before { content: attr(data-label); position: absolute; left: 15px; font-weight: bold; text-transform: uppercase; }
        }
    </style>
</head>
<body>

<div class="order-container">
    <?php if(isset($_GET['order']) && $_GET['order'] == 'success'): ?>
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> 
            <div>
                <strong>Congratulations!</strong> Your order has been placed successfully.
            </div>
        </div>
    <?php endif; ?>

    <div class="header">
        <h2><i class="fas fa-shopping-bag"></i> My Order History</h2>
        <div style="font-size: 13px; color: #888;">User: <b><?php echo $_SESSION['email']; ?></b></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) { ?>
                <tr>
                    <td data-label="Order ID"><b>#<?php echo $row['id']; ?></b></td>
                    <td data-label="Date"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    <td data-label="Total Amount" style="font-weight: 600;">৳ <?php echo number_format($row['total_amount'], 2); ?></td>
                    <td data-label="Status">
                        <span class="status status-<?php echo $row['status']; ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td data-label="Action">
                        <a href="order_details.php?id=<?php echo $row['id']; ?>" class="view-btn">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </td>
                </tr>
            <?php } 
            } else { ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 50px; color: #999;">
                        <i class="fas fa-box-open" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                        You haven't placed any orders yet.
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="../index.php" class="back-link">← Back to Shopping</a>
</div>

</body>
</html>