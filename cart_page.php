<?php
include("config.php");
//include("logged_in_check.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: log_in.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Sepetten Ürün Silme İşlemi (Miktarı Azaltma)
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    $update_sql = "UPDATE cart SET stock_quantity = stock_quantity - 1 WHERE user_id = $user_id AND product_id = $remove_id";
    berkhoca_query_parser($update_sql);
    
    // Miktar 0 veya daha az ise ürünü sepetten sil
    $check_quantity_sql = "SELECT stock_quantity FROM cart WHERE user_id = $user_id AND product_id = $remove_id";
    $check_quantity_result = berkhoca_query_parser($check_quantity_sql);
    if ($check_quantity_result[0]['stock_quantity'] <= 0) {
        $delete_sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $remove_id";
        berkhoca_query_parser($delete_sql);
    }

    header('Location: cart_page.php'); // İşlemden sonra sayfayı yenile
    exit();
}

// Sepetten Ürün Miktarını Artırma İşlemi
if (isset($_GET['increase'])) {
    $increase_id = intval($_GET['increase']);
    $update_sql = "UPDATE cart SET stock_quantity = stock_quantity + 1 WHERE user_id = $user_id AND product_id = $increase_id";
    berkhoca_query_parser($update_sql);
    header('Location: cart_page.php'); // İşlemden sonra sayfayı yenile
    exit();
}

// Sepeti Temizleme İşlemi
if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    $clear_sql = "DELETE FROM cart WHERE user_id = $user_id";
    berkhoca_query_parser($clear_sql);
    header('Location: cart_page.php'); // İşlemden sonra sayfayı yenile
    exit();
}

// Kullanıcının sepetini veri tabanından çekme
$cart_sql = "SELECT c.*, p.product_name, p.product_picture, p.price 
             FROM cart c 
             JOIN products p ON c.product_id = p.product_id 
             WHERE c.user_id = '$user_id'";
$cart_result = berkhoca_query_parser($cart_sql);

?>

<?php include('header.php'); ?>

<head>
    <style>
        .cart-container {
            width: 80%;
            margin: 20px auto;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        .cart-item-info {
            flex-grow: 1;
        }

        .cart-item-name {
            font-size: 1.2em;
            color: #333;
        }

        .cart-item-price {
            font-size: 1em;
            color: #777;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
        }

        .cart-item-quantity button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
            transition: background-color 0.3s ease;
        }

        .cart-item-quantity button:hover {
            background-color: #218838;
        }

        .cart-item-remove {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .cart-item-remove:hover {
            background-color: #c82333;
        }

        .cart-total {
            text-align: right;
            font-size: 1.5em;
            margin-top: 20px;
        }

        .clear-cart-button,
        .proceed-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }

        .clear-cart-button:hover,
        .proceed-button:hover {
            background-color: #c82333;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>

<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">      
        <div id="content-header">
            <h1>Your Cart</h1>
        </div> <!-- #content-header --> 

        <div id="content-container">
            <div class="cart-container">
                <?php
                if (count($cart_result) > 0) {
                    foreach ($cart_result as $item) {
                        echo "<div class='cart-item'>";
                        echo "    <img src='http://localhost/admin_panel/{$item['product_picture']}' alt='{$item['product_name']}' onerror=\"this.src='http://localhost/admin_panel/images/placeholder_image.jpg';\" />";
                        echo "    <div class='cart-item-info'>";
                        echo "        <p class='cart-item-name'>{$item['product_name']}</p>";
                        echo "        <p class='cart-item-price'>Price: {$item['price']} TL</p>";
                        echo "        <div class='cart-item-quantity'>";
                        echo "            <a href='cart_page.php?remove={$item['product_id']}'><button>-</button></a>";
                        echo "            <span>{$item['stock_quantity']}</span>";
                        echo "            <a href='cart_page.php?increase={$item['product_id']}'><button>+</button></a>";
                        echo "        </div>";
                        echo "    </div>";
                        echo "    <a href='cart_page.php?remove={$item['product_id']}' class='cart-item-remove'>Remove</a>";
                        echo "</div>";
                    }

                    // Toplam Fiyatı Hesaplama
                    $total_price = 0.0;
                    foreach ($cart_result as $item) {
                        $price = floatval(str_replace(',', '.', $item['price']));
                        $quantity = intval($item['stock_quantity']);
                        $total_price += $price * $quantity;
                    }

                    echo "<div class='cart-total'>Total: " . number_format($total_price, 2) . " TL</div>";
                    echo "<div class='button-group'>";
                    echo "    <a href='cart_page.php?action=clear' class='clear-cart-button'>Clear Cart</a>";
                    echo "    <a href='shipping_detail_page.php' class='proceed-button'>Proceed</a>";
                    echo "</div>";
                } else {
                    echo "<p>Your cart is empty.</p>";
                }
                ?>
            </div>
        </div> <!-- /#content-container -->
    </div> <!-- #content -->    
</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
