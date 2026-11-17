<?php
include '../Model/db.php'; 
session_start();

// --- 1. Order Status Update Logic ---
if (isset($_GET['update_id']) && isset($_GET['new_status'])) {
    $order_id = $_GET['update_id'];
    $status = $_GET['new_status'];
    $update_query = "UPDATE orders SET status = '$status' WHERE id = '$order_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: admin.php?page=sales");
        exit();
    }
}

// --- 2. Product Delete Logic ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "UPDATE products SET is_deleted = 1 WHERE id = '$id'");
    header("Location: admin.php?page=products");
    exit();
}

// --- 3. Customer Delete Logic ---
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id = '$user_id' AND role = 'Customer'");
    header("Location: admin.php?page=customers");
    exit();
}

// --- 4. Category Rename & Delete Logic ---
if (isset($_POST['rename_category'])) {
    $old_cat = mysqli_real_escape_string($conn, $_POST['old_cat']);
    $new_cat = mysqli_real_escape_string($conn, $_POST['new_cat']);
    mysqli_query($conn, "UPDATE products SET category = '$new_cat' WHERE category = '$old_cat'");
    header("Location: admin.php?page=categories");
}

if (isset($_GET['del_cat'])) {
    $cat_name = mysqli_real_escape_string($conn, $_GET['del_cat']);
    mysqli_query($conn, "UPDATE products SET category = 'Uncategorized' WHERE category = '$cat_name'");
    header("Location: admin.php?page=categories");
}

// --- 5. Product Save Logic ---
if (isset($_POST['save_product'])) {
    $id = $_POST['product_id'] ?? ''; 
    $name = mysqli_real_escape_string($conn, $_POST['pname']);
    $price = $_POST['price']; 
    $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : "NULL"; 
    $category = mysqli_real_escape_string($conn, $_POST['category']); 
    $description = mysqli_real_escape_string($conn, $_POST['pdesc']);
    $stock_status = $_POST['stock_status'];

    $image_sql = "";
    if (!empty($_FILES['pimage']['name'])) {
        $image_name = $_FILES['pimage']['name'];
        $temp_name = $_FILES['pimage']['tmp_name'];
        move_uploaded_file($temp_name, "../uploads/" . $image_name);
        $image_sql = ", image='$image_name'";
    }

    if (!empty($id)) {
        $sql = "UPDATE products SET name='$name', price='$price', old_price=$old_price, category='$category', description='$description', stock_status='$stock_status' $image_sql WHERE id='$id'";
    } else {
        $image_name = $_FILES['pimage']['name'];
        $sql = "INSERT INTO products (name, price, old_price, category, description, image, stock_status, is_deleted) 
                VALUES ('$name', '$price', $old_price, '$category', '$description', '$image_name', '$stock_status', 0)";
    }
    mysqli_query($conn, $sql);
    header("Location: admin.php?page=products");
    exit();
}

// --- 6. Settings Logic ---
if (isset($_POST['update_settings'])) {
    $new_user = mysqli_real_escape_string($conn, $_POST['admin_user']);
    $new_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
    $new_pass = $_POST['admin_pass'];
    $pass_sql = !empty($new_pass) ? ", password='$new_pass'" : "";
    mysqli_query($conn, "UPDATE users SET username='$new_user', email='$new_email' $pass_sql WHERE role='Admin' LIMIT 1");
    
    $ann_msg = mysqli_real_escape_string($conn, $_POST['announcement_msg']);
    $marquee_status = isset($_POST['marquee_status']) ? 1 : 0;
    
    mysqli_query($conn, "UPDATE settings SET announcement = '$ann_msg', is_marquee_active = '$marquee_status' WHERE id = 1");
    echo "<script>alert('Settings Updated Successfully'); window.location='admin.php?page=settings';</script>";
}

// --- 7. Data Calculations & Stats ---
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM orders"))['total'] ?? 0;
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM users WHERE role='Customer'"))['total'] ?? 0;
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status='Delivered'"))['total'] ?? 0;
$pending_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM orders WHERE status='Pending'"))['total'] ?? 0;
$delivered_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM orders WHERE status='Delivered'"))['total'] ?? 0;
$cancelled_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM orders WHERE status='Cancelled'"))['total'] ?? 0;

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// --- 8. Analytics Data Fetching ---
$sales_data = []; 
$labels = [];
if($page == 'analytics') {
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $res = mysqli_query($conn, "SELECT SUM(total_amount) as daily FROM orders WHERE status='Delivered' AND DATE(created_at) = '$date'");
        $sales_data[] = mysqli_fetch_assoc($res)['daily'] ?? 0;
        $labels[] = date('D', strtotime($date));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baking Valley | Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary: #7c3aed; 
            --bg: #f8fafc; 
            --sidebar-bg: #ffffff; 
            --text-main: #1e293b; 
            --text-sub: #64748b; 
            --border: #e2e8f0; 
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); 
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }

        body { 
            background-color: var(--bg); 
            display: flex; 
            color: var(--text-main); 
            min-height: 100vh; 
        }

        /* Sidebar Navigation */
        .sidebar { 
            width: 280px; 
            height: 100vh; 
            background: var(--sidebar-bg); 
            border-right: 1px solid var(--border); 
            position: fixed; 
            padding: 35px 20px; 
            display: flex; 
            flex-direction: column; 
        }

        .logo { 
            font-size: 24px; 
            font-weight: 800; 
            color: var(--primary); 
            margin-bottom: 45px; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
        }

        .nav-link { 
            display: flex; 
            align-items: center; 
            padding: 12px 18px; 
            color: var(--text-sub); 
            text-decoration: none; 
            border-radius: 12px; 
            margin-bottom: 5px; 
            transition: 0.3s; 
            font-weight: 600; 
            cursor: pointer; 
        }

        .nav-link:hover, .nav-link.active { 
            background: #f5f3ff; 
            color: var(--primary); 
        }

        .dropdown-container { 
            display: none; 
            padding-left: 20px; 
            flex-direction: column; 
        }

        .dropdown-container.show { 
            display: flex; 
        }

        .dropdown-btn i { 
            margin-left: auto; 
            transition: 0.3s; 
        }

        .rotate { 
            transform: rotate(180deg); 
        }

        /* Layout Structure */
        .main-content { 
            margin-left: 280px; 
            flex: 1; 
            padding: 45px; 
        }

        .top-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px; 
        }

        /* Dashboard Stat Cards */
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 25px; 
            margin-bottom: 40px; 
        }

        .stat-card { 
            background: #fff; 
            padding: 30px; 
            border-radius: 24px; 
            border: 1px solid var(--border); 
            display: flex; 
            align-items: center; 
            gap: 20px; 
            box-shadow: var(--card-shadow); 
        }

        .stat-icon { 
            width: 60px; 
            height: 60px; 
            border-radius: 18px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 24px; 
        }

        .icon-purple { background: #f5f3ff; color: var(--primary); }
        .icon-blue { background: #eff6ff; color: #3b82f6; }
        .icon-green { background: #ecfdf5; color: #10b981; }

        /* Tables & Data Display */
        .section-card { 
            background: #fff; 
            border-radius: 24px; 
            padding: 35px; 
            border: 1px solid var(--border); 
            box-shadow: var(--card-shadow); 
            margin-bottom: 20px; 
            overflow-x: auto; 
        }

        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        .data-table th { 
            text-align: left; 
            padding: 15px; 
            color: var(--text-sub); 
            border-bottom: 2px solid var(--bg); 
            font-size: 13px; 
            text-transform: uppercase; 
        }

        .data-table td { 
            padding: 15px; 
            border-bottom: 1px solid var(--bg); 
            font-size: 14px; 
        }

        /* Status Badges */
        .badge { 
            padding: 6px 12px; 
            border-radius: 8px; 
            font-size: 11px; 
            font-weight: 700; 
            text-transform: uppercase; 
        }

        .badge-delivered { background: #ecfdf5; color: #059669; }
        .badge-pending { background: #fff7ed; color: #d97706; }
        .badge-cancelled { background: #fef2f2; color: #dc2626; }
        .badge-instock { background: #ecfdf5; color: #10b981; }
        .badge-outstock { background: #fef2f2; color: #ef4444; }

        /* Buttons & Inputs */
        .btn-add { 
            background: var(--primary); 
            color: #fff; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 10px; 
            cursor: pointer; 
            font-weight: 700; 
            transition: 0.2s;
        }

        .btn-add:hover { opacity: 0.9; }

        .form-input { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid var(--border); 
            border-radius: 8px; 
            margin-bottom: 15px; 
        }

        /* Modal Overlay */
        .modal { 
            display: none; 
            position: fixed; 
            inset: 0; 
            background: rgba(15,23,42,0.6); 
            backdrop-filter: blur(4px); 
            z-index: 1000; 
            justify-content: center; 
            align-items: center; 
        }

        .modal-content { 
            background: #fff; 
            padding: 30px; 
            border-radius: 20px; 
            width: 480px; 
            max-height: 90vh; 
            overflow-y: auto; 
        }

        /* Toggle Switch for Marquee */
        .switch { 
            position: relative; 
            display: inline-block; 
            width: 45px; 
            height: 22px; 
        }

        .switch input { opacity: 0; width: 0; height: 0; }

        .slider { 
            position: absolute; 
            cursor: pointer; 
            top: 0; left: 0; right: 0; bottom: 0; 
            background-color: #ccc; 
            transition: .4s; 
            border-radius: 34px; 
        }

        .slider:before { 
            position: absolute; 
            content: ""; 
            height: 16px; 
            width: 16px; 
            left: 3px; 
            bottom: 3px; 
            background-color: white; 
            transition: .4s; 
            border-radius: 50%; 
        }

        input:checked + .slider { background-color: var(--primary); }
        input:checked + .slider:before { transform: translateX(23px); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo"><i class="fas fa-cake-candles"></i> Baking Valley</div>
    <nav>
        <a href="admin.php?page=dashboard" class="nav-link <?php echo $page == 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        
        <div>
            <div class="nav-link dropdown-btn <?php echo ($page == 'products' || $page == 'categories') ? 'active' : ''; ?>" onclick="toggleDropdown()">
                <span><i class="fas fa-cookie"></i> Products</span>
                <i class="fas fa-chevron-down" id="dropIcon"></i>
            </div>
            <div id="productMenu" class="dropdown-container <?php echo ($page == 'products' || $page == 'categories') ? 'show' : ''; ?>">
                <a href="admin.php?page=products" class="nav-link" style="font-size: 14px;"><i class="fas fa-list"></i> Product List</a>
                <a href="admin.php?page=categories" class="nav-link" style="font-size: 14px;"><i class="fas fa-tags"></i> Categories</a>
            </div>
        </div>

        <a href="admin.php?page=sales" class="nav-link <?php echo $page == 'sales' ? 'active' : ''; ?>">
            <i class="fas fa-tag"></i> Sales
        </a>
        <a href="admin.php?page=customers" class="nav-link <?php echo $page == 'customers' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Customers
        </a>
        <a href="admin.php?page=analytics" class="nav-link <?php echo $page == 'analytics' ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> Analytics
        </a>
        <a href="admin.php?page=notifications" class="nav-link <?php echo $page == 'notifications' ? 'active' : ''; ?>">
            <i class="fas fa-bell"></i> Notifications 
            <?php if($pending_orders > 0): ?>
                <span style="background:red; color:white; font-size:10px; padding:2px 6px; border-radius:50%; margin-left:5px;"><?php echo $pending_orders; ?></span>
            <?php endif; ?>
        </a>
        <a href="admin.php?page=settings" class="nav-link <?php echo $page == 'settings' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Settings
        </a>
    </nav>
    <a href="../Controller/logout.php" style="margin-top:auto; text-decoration:none; color:#ef4444; font-weight:700;">
        <i class="fas fa-sign-out-alt"></i> Log out
    </a>
</aside>

<main class="main-content">
    <header class="top-header">
        <h2><?php echo ucfirst($page); ?> Overview</h2>
        <?php if($page == 'products'): ?>
            <button class="btn-add" onclick="openAddModal()">+ Add Product</button>
        <?php endif; ?>
    </header>

    <?php if($page == 'dashboard'): ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-purple"><i class="fas fa-shopping-cart"></i></div>
                <div><h3><?php echo $total_orders; ?></h3><p>Total Orders</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="fas fa-users"></i></div>
                <div><h3><?php echo $total_customers; ?></h3><p>Total Customers</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="fas fa-sack-dollar"></i></div>
                <div><h3>BDT<?php echo number_format($total_revenue / 1000, 1); ?>k</h3><p>Revenue</p></div>
            </div>
        </div>

    <?php elseif($page == 'products'): ?>
        <div class="section-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th style="color: #ef4444;">Old Price</th>
                        <th style="color: #10b981;">Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM products WHERE is_deleted=0 ORDER BY id DESC");
                    $sn=1; while($row = mysqli_fetch_assoc($res)){
                        $p_data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                        $old_p = ($row['old_price'] > 0) ? "৳".$row['old_price'] : "-";
                        $stock_class = ($row['stock_status'] == 'In Stock') ? 'badge-instock' : 'badge-outstock';
                        $short_desc = (strlen($row['description']) > 30) ? substr($row['description'], 0, 30) . "..." : $row['description'];
                        echo "<tr>
                            <td>$sn</td>
                            <td><img src='../uploads/{$row['image']}' width='40' height='40' style='border-radius:8px;'></td>
                            <td>{$row['name']}</td>
                            <td>{$row['category']}</td>
                            <td><small title='{$row['description']}'>$short_desc</small></td>
                            <td><span class='badge $stock_class'>{$row['stock_status']}</span></td>
                            <td style='text-decoration:line-through; color:#ef4444;'>$old_p</td>
                            <td style='color:#10b981; font-weight:bold;'>৳{$row['price']}</td>
                            <td>
                                <i class='fas fa-edit' style='color:blue; cursor:pointer; margin-right:15px;' onclick='openEditModal($p_data)'></i>
                                <i class='fas fa-trash' style='color:red; cursor:pointer;' onclick=\"if(confirm('Delete Product?')) location.href='admin.php?delete_id={$row['id']}'\"></i>
                            </td>
                        </tr>"; 
                        $sn++;
                    } ?>
                </tbody>
            </table>
        </div>
    
    <?php elseif($page == 'categories'): ?>
        <div class="section-card">
            <h3>Product Categories</h3><br>
            <table class="data-table">
                <thead><tr><th>Category Name</th><th>Action</th></tr></thead>
                <tbody>
                    <?php
                    $cat_res = mysqli_query($conn, "SELECT DISTINCT category FROM products WHERE category != ''");
                    while($row = mysqli_fetch_assoc($cat_res)): $c = $row['category']; ?>
                    <tr>
                        <td><?php echo $c; ?></td>
                        <td>
                            <form method="POST" style="display:inline-flex; gap:10px;">
                                <input type="hidden" name="old_cat" value="<?php echo $c; ?>">
                                <input type="text" name="new_cat" placeholder="New Name" required class="form-input" style="margin:0; padding:5px;">
                                <button type="submit" name="rename_category" class="btn-add" style="padding:5px 10px;">Rename</button>
                            </form>
                            <a href="admin.php?page=categories&del_cat=<?php echo $c; ?>" onclick="return confirm('Delete Category?')" style="color:red; margin-left:15px;">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php elseif($page == 'sales'): ?>
        <div class="section-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Payment</th>
                        <th>TrxID & Sender</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sales = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($sales)){
                        $st = strtolower($row['status']);
                        echo "<tr>
                            <td>#{$row['id']}</td>
                            <td>{$row['customer_name']}</td>
                            <td>{$row['phone']}</td>
                            <td><div style='max-width:200px; font-size:12px;'>{$row['address']}</div></td>
                            <td><span style='font-size:11px; font-weight:700; color:#7c3aed;'>{$row['payment_method']}</span></td>
                            <td style='font-size:12px;'>{$row['trx_id']}</td>
                            <td>৳{$row['total_amount']}</td>
                            <td><span class='badge badge-$st'>{$row['status']}</span></td>
                            <td>
                                <select class='status-select' onchange=\"location='admin.php?page=sales&update_id={$row['id']}&new_status='+this.value\">
                                    <option disabled selected>Update</option>
                                    <option value='Pending'>Pending</option>
                                    <option value='Delivered'>Delivered</option>
                                    <option value='Cancelled'>Cancelled</option>
                                </select>
                            </td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>

    <?php elseif($page == 'customers'): ?>
        <div class="section-card">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr></thead>
                <tbody>
                    <?php $cust = mysqli_query($conn, "SELECT * FROM users WHERE role='Customer' ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($cust)){
                        echo "<tr>
                            <td>#{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td><i class='fas fa-trash' style='color:red; cursor:pointer;' onclick=\"if(confirm('Remove Customer?')) location.href='admin.php?page=customers&delete_user_id={$row['id']}'\"></i></td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>

    <?php elseif($page == 'analytics'): ?>
        <div class="stats-grid">
            <div class="stat-card"><div><h3><?php echo $pending_orders; ?></h3><p>Pending</p></div></div>
            <div class="stat-card"><div><h3><?php echo $delivered_orders; ?></h3><p>Delivered</p></div></div>
            <div class="stat-card"><div><h3><?php echo $cancelled_orders; ?></h3><p>Cancelled</p></div></div>
        </div>
        <div class="section-card">
            <h3>Weekly Sales Analytics</h3>
            <canvas id="salesChart" height="100"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById('salesChart'), { 
                type: 'line', 
                data: { 
                    labels: <?php echo json_encode($labels); ?>, 
                    datasets: [{ 
                        label: 'Revenue (৳)', 
                        data: <?php echo json_encode($sales_data); ?>, 
                        borderColor: '#7c3aed', 
                        tension: 0.4 
                    }] 
                } 
            });
        </script>

    <?php elseif($page == 'notifications'): ?>
        <div class="section-card">
            <h3>Recent Order Notifications</h3><br>
            <?php 
            // Fixed Notification Logic: Fetch orders with status 'Pending'
            $notifs = mysqli_query($conn, "SELECT * FROM orders WHERE status='Pending' ORDER BY id DESC LIMIT 10");
            if(mysqli_num_rows($notifs) > 0) {
                while($n = mysqli_fetch_assoc($notifs)){
                    $order_time = date('d M, h:i A', strtotime($n['created_at']));
                    echo "<div style='padding:15px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;'>
                            <div>
                                <strong style='color:var(--primary);'>New Order #{$n['id']}</strong><br>
                                <span style='font-size:13px;'>Customer: <b>{$n['customer_name']}</b> ordered items worth <b>৳{$n['total_amount']}</b></span>
                            </div>
                            <div style='text-align:right;'>
                                <span style='font-size:11px; color:var(--text-sub);'>$order_time</span><br>
                                <a href='admin.php?page=sales' style='font-size:12px; color:var(--primary); text-decoration:none; font-weight:700;'>View Details</a>
                            </div>
                          </div>";
                }
            } else {
                echo "<p style='color:var(--text-sub);'>No new notifications at the moment.</p>";
            }
            ?>
        </div>

    <?php elseif($page == 'settings'): ?>
        <?php 
            $admin_res = mysqli_query($conn, "SELECT * FROM users WHERE role='Admin' LIMIT 1");
            $admin = mysqli_fetch_assoc($admin_res);
            $ann_res = mysqli_query($conn, "SELECT announcement, is_marquee_active FROM settings WHERE id=1");
            $ann = mysqli_fetch_assoc($ann_res);
        ?>
        <div class="section-card" style="max-width:500px;">
            <h3>Admin & General Settings</h3><br>
            <form method="POST">
                <label>Admin Username</label>
                <input type="text" name="admin_user" value="<?php echo $admin['username']; ?>" class="form-input" required>
                
                <label>Admin Email</label>
                <input type="email" name="admin_email" value="<?php echo $admin['email']; ?>" class="form-input" required>
                
                <label>New Password (Leave blank to keep current)</label>
                <input type="password" name="admin_pass" placeholder="New Password" class="form-input">
                
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                
                <h3 style="color: var(--primary);">Promotional Header</h3><br>
                
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; background: #f5f3ff; padding: 10px; border-radius: 10px;">
                    <span style="font-weight: 600; color: var(--primary);">Enable Scrolling Animation</span>
                    <label class="switch">
                        <input type="checkbox" name="marquee_status" <?php echo (isset($ann['is_marquee_active']) && $ann['is_marquee_active'] == 1) ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                    </label>
                </div>

                <label>Announcement Text</label>
                <input type="text" name="announcement_msg" value="<?php echo $ann['announcement'] ?? ''; ?>" placeholder="Enter offer details..." class="form-input" required>
                
                <button type="submit" name="update_settings" class="btn-add" style="width:100%;">Save Changes</button>
            </form>
        </div>
    <?php endif; ?>
</main>

<div id="uploadModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Product Information</h3><br>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="product_id">
            
            <label>Product Name</label>
            <input type="text" name="pname" id="pname" placeholder="Name" required class="form-input">
            
            <label>Category</label>
            <input type="text" name="category" id="pcat" placeholder="e.g. Cakes, Cookies" required class="form-input">
            
            <label>Product Description</label>
            <textarea name="pdesc" id="pdesc" placeholder="Write details about the product..." class="form-input" style="height: 100px; resize: none;"></textarea>

            <label style="color: #10b981; font-weight: bold;">Current Sale Price</label>
            <input type="number" step="0.01" name="price" id="pprice" placeholder="0.00" required class="form-input" style="border-color: #10b981;">
            
            <label style="color: #ef4444;">Regular Price (Before Discount)</label>
            <input type="number" step="0.01" name="old_price" id="pold" placeholder="0.00" class="form-input" style="border-color: #ef4444;">
            
            <label>Stock Status</label>
            <select name="stock_status" id="pstock" class="form-input">
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>
            
            <label>Product Image</label>
            <input type="file" name="pimage" class="form-input" style="border:none; padding-left:0;">
            
            <button type="submit" name="save_product" class="btn-add" style="width:100%;">Save Product</button>
            <button type="button" onclick="closeModal()" class="btn-add" style="background:#64748b; width:100%; margin-top:10px;">Cancel</button>
        </form>
    </div>
</div>

<script>
    function toggleDropdown() {
        document.getElementById("productMenu").classList.toggle("show");
        document.getElementById("dropIcon").classList.toggle("rotate");
    }

    function openAddModal(){ 
        document.getElementById('modalTitle').innerText="Add New Product"; 
        document.getElementById('product_id').value=""; 
        document.getElementById('pname').value="";
        document.getElementById('pprice').value="";
        document.getElementById('pold').value=""; 
        document.getElementById('pcat').value="";
        document.getElementById('pdesc').value="";
        document.getElementById('uploadModal').style.display='flex'; 
    }

    function openEditModal(data){ 
        document.getElementById('modalTitle').innerText="Edit Product Details"; 
        document.getElementById('product_id').value = data.id; 
        document.getElementById('pname').value = data.name; 
        document.getElementById('pprice').value = data.price; 
        document.getElementById('pold').value = data.old_price; 
        document.getElementById('pcat').value = data.category; 
        document.getElementById('pdesc').value = data.description || "";
        document.getElementById('pstock').value = data.stock_status; 
        document.getElementById('uploadModal').style.display='flex'; 
    }

    function closeModal(){ 
        document.getElementById('uploadModal').style.display='none'; 
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('uploadModal')) {
            closeModal();
        }
    }
</script>
</body>
</html>