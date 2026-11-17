<?php


if (!isset($_POST['selected_skus'])) {
    die("Doya kore product select korun!");
}

$skus = $_POST['selected_skus'];
$quantities = $_POST['qty'];
$names = $_POST['names'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Baking Valley - Print Barcodes</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; }
        .sticker { background: #fff; border: 1px solid #ddd; padding: 10px; text-align: center; width: 160px; }
        .shop-name { font-size: 10px; font-weight: bold; color: #D35400; margin-bottom: 2px; }
        .product-name { font-size: 9px; margin-bottom: 5px; height: 20px; overflow: hidden; }
        .barcode-img { width: 100%; height: auto; }
        .code-text { font-size: 10px; font-weight: bold; margin-top: 3px; }
        @media print { .no-print { display: none; } .sticker { border: 1px solid #000; } body { background: #fff; padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center; margin-bottom:20px;">
        <button onclick="window.print()" style="padding:10px 30px; background:#27ae60; color:white; border:none; cursor:pointer; border-radius:5px;">Print Barcode Sheet</button>
    </div>
    <div class="grid">
        <?php 
        foreach ($skus as $code) {
            $p_name = $names[$code];
            $count = (int)$quantities[$code];
            for ($i = 0; $i < $count; $i++) {
                $url = "https://barcode.tec-it.com/barcode.ashx?data=" . urlencode($code) . "&code=Code128";
                echo "<div class='sticker'>
                        <div class='shop-name'>BAKING VALLEY</div>
                        <div class='product-name'>$p_name</div>
                        <img src='$url' class='barcode-img'>
                        <div class='code-text'>$code</div>
                      </div>";
            }
        } ?>
    </div>
</body>
</html>