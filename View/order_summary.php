<?php
include '../Model/db.php';
session_start();

if(!isset($_SESSION['email'])) { header("Location: login.php"); exit(); }

$u_email = $_SESSION['email'];
$u_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, username FROM users WHERE email='$u_email'"));
$u_id = $u_info['id'];

$res = mysqli_query($conn, "SELECT p.*, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$u_id'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Review Your Order | Baking Valley</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #d32f2f; --primary-hover: #b71c1c; --bg: #fdfaf6; --bkash: #e2136e; --nagad: #f6921e; }
        body { background: var(--bg); font-family: 'Segoe UI', Roboto, sans-serif; color: #444; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        h2 { color: var(--primary); text-align: center; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #fcfcfc; color: #666; padding: 15px; text-align: left; border-bottom: 2px solid #eee; font-size: 14px; }
        td { padding: 15px; border-bottom: 1px solid #f5f5f5; }

        .order-layout { display: flex; gap: 30px; margin-top: 30px; }
        .form-section { flex: 1.5; }
        .summary-section { flex: 1; background: #fafafa; padding: 25px; border-radius: 12px; border: 1px solid #eee; height: fit-content; position: sticky; top: 20px; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #333; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 15px; transition: 0.3s; box-sizing: border-box; }
        .form-group input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1); }

        .payment-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 10px; }
        .payment-card { position: relative; cursor: pointer; }
        .payment-card input { position: absolute; opacity: 0; }
        .card-design { border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px 8px; display: flex; flex-direction: column; align-items: center; gap: 8px; transition: 0.3s; background: #fff; text-align: center; }
        .card-design i { font-size: 22px; color: #64748b; }
        .payment-card input:checked + .card-design { border-color: var(--primary); background: #fff5f5; }
        .payment-card input:checked + .card-design i { color: var(--primary); }
        .card-design span { font-weight: 600; font-size: 12px; }

        #payment-instruction-box { background: #fff; border: 1.5px solid #eee; padding: 20px; border-radius: 12px; margin-top: 20px; display: none; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .instruction-header { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; font-weight: 700; font-size: 16px; }
        .step-list { font-size: 13.5px; line-height: 1.8; color: #555; margin-bottom: 15px; padding-left: 20px; }
        .merchant-box { background: #f8f9fa; padding: 10px; border-radius: 8px; border: 1px dashed #ccc; text-align: center; margin-bottom: 15px; }

        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 16px; }
        .grand-total { border-top: 2px solid #eee; padding-top: 15px; font-size: 22px; font-weight: 800; color: var(--primary); }

        .btn-confirm { background: var(--primary); color: #fff; padding: 16px; border-radius: 10px; border: none; width: 100%; font-size: 18px; font-weight: 700; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 12px rgba(211, 47, 47, 0.2); }
        .btn-confirm:hover { background: var(--primary-hover); transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-shopping-basket"></i> Review Your Order</h2>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $subtotal_all = 0;
                while($row = mysqli_fetch_assoc($res)) {
                    $sub = $row['price'] * $row['quantity'];
                    $subtotal_all += $sub;
                    echo "<tr>
                            <td style='display:flex; align-items:center; gap:10px;'>
                                <img src='../uploads/{$row['image']}' width='45' height='45' style='border-radius:6px; object-fit:cover;'>
                                <span>{$row['name']}</span>
                            </td>
                            <td>৳ {$row['price']}</td>
                            <td>{$row['quantity']}</td>
                            <td style='font-weight:bold;'>৳ ".number_format($sub, 2)."</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <form action="../Controller/place_order_action.php" method="POST">
            <div class="order-layout">
                
                <div class="form-section">
                    <h3 style="margin-top:0; color: #333; font-size: 18px; margin-bottom: 20px;">
                        <i class="fas fa-map-marker-alt" style="color:var(--primary)"></i> Delivery Details
                    </h3>
                    
                    <div class="form-group">
                        <label>Receiver's Name</label>
                        <input type="text" name="customer_name" value="<?php echo $u_info['username']; ?>" required>
                    </div>

                    <div style="display:flex; gap:15px;">
                        <div class="form-group" style="flex:1;">
                            <label>Mobile Number (Contact)</label>
                            <input type="text" name="phone" placeholder="01XXXXXXXXX" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Delivery Area</label>
                            <select name="location" id="location" onchange="calculateTotal()">
                                <option value="80">Inside Dhaka (৳ 80)</option>
                                <option value="120">Outside Dhaka (৳ 120)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Full Delivery Address</label>
                        <textarea name="address" rows="2" placeholder="House no, Road no, Area..." required></textarea>
                    </div>

                    <h3 style="margin-top:30px; color: #333; font-size: 18px;">
                        <i class="fas fa-credit-card" style="color:var(--primary)"></i> Select Payment Method
                    </h3>
                    
                    <div class="payment-grid">
                        <label class="payment-card">
                            <input type="radio" name="payment_method" value="COD" checked onclick="showGuide('none')">
                            <div class="card-design">
                                <i class="fas fa-hand-holding-dollar"></i>
                                <span>Cash on Delivery</span>
                            </div>
                        </label>

                        <label class="payment-card">
                            <input type="radio" name="payment_method" value="bKash" onclick="showGuide('bkash')">
                            <div class="card-design">
                                <i class="fas fa-mobile-screen" style="color:var(--bkash)"></i>
                                <span>bKash Payment</span>
                            </div>
                        </label>

                        <label class="payment-card">
                            <input type="radio" name="payment_method" value="Nagad" onclick="showGuide('nagad')">
                            <div class="card-design">
                                <i class="fas fa-mobile-screen" style="color:var(--nagad)"></i>
                                <span>Nagad Payment</span>
                            </div>
                        </label>
                    </div>

                    <div id="payment-instruction-box">
                        <div id="bkash-content" style="display:none;">
                            <div class="instruction-header" style="color:var(--bkash)">
                                <i class="fas fa-building-columns"></i> bKash Merchant Payment
                            </div>
                            <div class="merchant-box">
                                Merchant Number: <b style="font-size:18px; color:var(--bkash)">01734090009</b>
                            </div>
                            <ol class="step-list">
                                <li>Go to bKash App or Dial *247#</li>
                                <li>Select <b>"Make Payment"</b></li>
                                <li>Enter the Merchant Number above</li>
                                <li>Amount: <b>৳ <span class="pay-amount"></span></b></li>
                                <li>Reference: <b>BakingValley</b></li>
                            </ol>
                        </div>

                        <div id="nagad-content" style="display:none;">
                            <div class="instruction-header" style="color:var(--nagad)">
                                <i class="fas fa-building-columns"></i> Nagad Merchant Payment
                            </div>
                            <div class="merchant-box">
                                Merchant Number: <b style="font-size:18px; color:var(--nagad)">01734090009</b>
                            </div>
                            <ol class="step-list">
                                <li>Go to Nagad App or Dial *167#</li>
                                <li>Select <b>"Merchant Pay"</b></li>
                                <li>Enter the Merchant Number above</li>
                                <li>Amount: <b>৳ <span class="pay-amount"></span></b></li>
                                <li>Counter: <b>1</b></li>
                            </ol>
                        </div>

                        <div style="display:flex; gap:15px; margin-top:15px; border-top:1px solid #eee; padding-top:15px;">
                            <div class="form-group" style="flex:1; margin-bottom:0;">
                                <label style="color:var(--primary)">Sender Number</label>
                                <input type="text" name="payment_phone" id="payment_phone" placeholder="01XXXXXXXXX">
                            </div>
                            <div class="form-group" style="flex:1; margin-bottom:0;">
                                <label style="color:var(--primary)">Transaction ID (TrxID)</label>
                                <input type="text" name="trx_id" id="trx_id" placeholder="8N7X6W5V4U">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:20px;">
                        <label>Note / Special Instruction (Optional)</label>
                        <input type="text" name="note" placeholder="Example: Near the mosque or Flat 2A">
                    </div>
                </div>

                <div class="summary-section">
                    <h3 style="margin-top:0; font-size: 18px;">Order Summary</h3>
                    <div class="summary-row"><span>Subtotal:</span><span>৳ <?php echo number_format($subtotal_all, 2); ?></span></div>
                    <div class="summary-row"><span>Shipping:</span><span>৳ <span id="shipping-charge">80.00</span></span></div>
                    <div class="summary-row grand-total"><span>Total:</span><span>৳ <span id="grand-total"><?php echo number_format($subtotal_all + 80, 2); ?></span></span></div>

                    <button type="submit" class="btn-confirm">Confirm Order</button>
                    <div style="text-align:center; margin-top:20px;">
                        <a href="../index.php" style="color: #999; text-decoration: none; font-size: 14px;"><i class="fas fa-chevron-left"></i> Back to Shop</a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        function calculateTotal() {
            const itemsTotal = <?php echo $subtotal_all; ?>;
            const shipping = parseInt(document.getElementById('location').value);
            const grandTotal = itemsTotal + shipping;
            document.getElementById('shipping-charge').innerText = shipping.toFixed(2);
            document.getElementById('grand-total').innerText = grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
            
            document.querySelectorAll('.pay-amount').forEach(el => el.innerText = grandTotal.toFixed(2));
        }

        function showGuide(method) {
            const box = document.getElementById('payment-instruction-box');
            const bkash = document.getElementById('bkash-content');
            const nagad = document.getElementById('nagad-content');
            const trx = document.getElementById('trx_id');
            const pPhone = document.getElementById('payment_phone');
            const total = document.getElementById('grand-total').innerText;

            document.querySelectorAll('.pay-amount').forEach(el => el.innerText = total);

            if(method === 'none') {
                box.style.display = 'none';
                trx.required = false;
                pPhone.required = false;
            } else {
                box.style.display = 'block';
                trx.required = true;
                pPhone.required = true;
                bkash.style.display = (method === 'bkash') ? 'block' : 'none';
                nagad.style.display = (method === 'nagad') ? 'block' : 'none';
            }
        }
        calculateTotal();
    </script>
</body>
</html>