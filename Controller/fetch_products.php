<?php 
include '../Model/db.php';
header('Content-Type: application/json');

// ডাটা রিসিভ করা
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$cat = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';

$sql = "SELECT * FROM products WHERE is_deleted = 0";

// সার্চ ফিল্টার
if($search != '') {
    $sql .= " AND (name LIKE '%$search%' OR category LIKE '%$search%')";
}

// ক্যাটাগরি ফিল্টার: এখানে 'All' চেকটি যোগ করা হয়েছে যাতে 'All' থাকলে সব আসে
if($cat != '' && $cat != 'All' && $cat != 'undefined') {
    $sql .= " AND category = '$cat'";
}

$sql .= " ORDER BY id DESC"; 

$res = mysqli_query($conn, $sql);
$products = [];

if ($res) {
    while($row = mysqli_fetch_assoc($res)) {
        // আপনার আগের সব ফরম্যাটিং ঠিক রাখা হয়েছে
        $row['old_price'] = $row['old_price'] ? (float)$row['old_price'] : null;
        $row['price'] = (float)$row['price'];
        $row['stock_status'] = $row['stock_status'] ?? 'In Stock'; 
        $row['description'] = $row['description'] ?? 'No description available.';
        
        $products[] = $row;
    }
}

echo json_encode($products);
?>