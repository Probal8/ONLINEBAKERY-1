<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Baking Valley | POS Terminal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f7f6; display: flex; height: 100vh; overflow: hidden; }
        .product-section { flex: 7; padding: 20px; overflow-y: auto; border-right: 2px solid #ddd; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
        .product-card { background: #fff; padding: 15px; border-radius: 10px; text-align: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: 0.3s; border: 1px solid transparent; }
        .product-card:hover { border-color: #6a1b9a; transform: translateY(-3px); }
        .product-card img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        
        /* বারকোড সার্চ বক্সের স্টাইল */
        .search-container { margin-bottom: 20px; width: 100%; }
        .search-bar { width: 95%; padding: 15px; border-radius: 30px; border: 2px solid #6a1b9a; outline: none; font-size: 16px; box-shadow: 0 2px 10px rgba(106, 27, 154, 0.1); }

        .billing-section { flex: 3; background: #fff; display: flex; flex-direction: column; padding: 20px; box-shadow: -2px 0 10px rgba(0,0,0,0.05); }
        .cart-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .cart-table th { text-align: left; border-bottom: 2px solid #eee; padding: 10px 0; color: #666; }
        .cart-table td { padding: 12px 0; border-bottom: 1px solid #eee; }
        
        .summary { margin-top: auto; padding-top: 20px; border-top: 2px solid #6a1b9a; }
        .total-row { display: flex; justify-content: space-between; font-size: 22px; font-weight: bold; color: #6a1b9a; margin-bottom: 15px; }
        .btn-pay { width: 100%; background: #6a1b9a; color: white; border: none; padding: 15px; border-radius: 8px; font-size: 18px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-pay:hover { background: #4a148c; }
    </style>
</head>
<body>

<div class="product-section">
    <h2 style="color: #6a1b9a;"><i class="fa fa-shopping-basket"></i> Baking Valley POS</h2>
    
    <div class="search-container">
        <input type="text" id="barcode-input" class="search-bar" placeholder="Scan Barcode or Search Item..." autofocus autocomplete="off">
    </div>
    
    <div class="grid">
        <?php
            $conn = new mysqli("localhost", "root", "", "baking_valley");
            $result = $conn->query("SELECT * FROM products");
            while($row = $result->fetch_assoc()):
        ?>
        <div class="product-card" onclick="addToCart('<?= addslashes($row['name']) ?>', <?= $row['price'] ?>, <?= $row['id'] ?>)">
            <img src="<?= $row['image'] ?>" onerror="this.src='https://via.placeholder.com/60'">
            <p style="font-weight: bold; margin: 10px 0 5px;"><?= $row['name'] ?></p>
            <p style="color: #6a1b9a; margin: 0;">৳ <?= $row['price'] ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="billing-section">
    <h3><i class="fa fa-shopping-cart"></i> Current Order</h3>
    <div style="flex-grow: 1; overflow-y: auto;">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="width: 60px;">Qty</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="cart-items"></tbody>
        </table>
    </div>

    <div class="summary">
        <div class="total-row">
            <span>Total:</span>
            <span>৳ <span id="grand-total">0</span></span>
        </div>
        <button class="btn-pay" onclick="processSale()">Confirm Order & Print</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let cart = [];

    // ১. বারকোড স্ক্যান লজিক
    $('#barcode-input').on('keypress', function(e) {
        if (e.which == 13) { // Enter key (Scanner sends enter)
            let val = $(this).val();
            if (val) {
                // AJAX কল করে ডাটাবেস থেকে বারকোড অনুযায়ী প্রোডাক্ট আনা
                $.get('../Controller/get_product.php', { barcode: val }, function(res) {
                    let data = JSON.parse(res);
                    if(data.status === 'success') {
                        addToCart(data.name, data.price, data.id);
                        $('#barcode-input').val('').focus();
                    } else {
                        alert("Product not found!");
                        $('#barcode-input').val('').focus();
                    }
                });
            }
        }
    });

    // ২. কার্টে আইটেম যোগ করা
    function addToCart(name, price, id) {
        let item = cart.find(i => i.id === id);
        if (item) {
            item.qty++;
        } else {
            cart.push({ id: id, name: name, price: parseFloat(price), qty: 1 });
        }
        renderCart();
    }

    // ৩. কার্ট আপডেট এবং রেন্ডার (Fix: input change handling)
    function renderCart() {
        let html = '';
        let grandTotal = 0;
        cart.forEach((item, index) => {
            let subtotal = item.price * item.qty;
            grandTotal += subtotal;
            html += `<tr>
                <td>${item.name}</td>
                <td><input type="number" value="${item.qty}" min="1" style="width:45px" onchange="updateQty(${index}, this.value)"></td>
                <td>${subtotal.toFixed(2)}</td>
                <td><i class="fa fa-trash" style="color:red; cursor:pointer" onclick="removeItem(${index})"></i></td>
            </tr>`;
        });
        $('#cart-items').html(html);
        $('#grand-total').text(grandTotal.toFixed(2));
    }

    function updateQty(index, newQty) {
        cart[index].qty = parseInt(newQty) || 1;
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function processSale() {
        if(cart.length === 0) return alert("Cart empty!");
        // আপনার সেলস প্রসেসিং AJAX এখানে হবে
        alert("Sale successful!");
        window.print();
        cart = [];
        renderCart();
    }
</script>
</body>
</html>