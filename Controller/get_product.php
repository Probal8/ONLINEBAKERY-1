<?php
// Controller/get_product.php
$conn = new mysqli("localhost", "root", "", "baking_valley");

if (isset($_GET['barcode'])) {
    $barcode = $conn->real_escape_string($_GET['barcode']);
    
    // বারকোড অথবা নাম দিয়ে সার্চ করার কুয়েরি
    $query = "SELECT id, name, price FROM products WHERE barcode = '$barcode' OR name = '$barcode' LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price']
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>