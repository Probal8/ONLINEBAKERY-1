<?php
include 'Model/db.php';
$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($res);

if(isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $old_price = $_POST['old_price']; 
    
    // Database update query
    $query = "UPDATE products SET name='$name', price='$price', old_price='$old_price' WHERE id=$id";
    if(mysqli_query($conn, $query)) {
        header("Location: admin.php?page=products"); 
    }
}
?>

<form method="POST" style="max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 15px;">
    <h2>Edit Product</h2>
    <label>Product Name</label>
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required style="width:100%; margin-bottom:15px;">
    
    <label>Current Price </label>
    <input type="number" name="price" value="<?php echo $product['price']; ?>" required style="width:100%; margin-bottom:15px;">
    
    <label>Old Price </label>
    <input type="number" name="old_price" value="<?php echo $product['old_price']; ?>" placeholder="Jemon: 1000" style="width:100%; margin-bottom:15px;">
    
    <button type="submit" name="update_product" style="background:#7c3aed; color:white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer;">Update Product</button>
</form>