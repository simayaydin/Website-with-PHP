<?php
include('config.php');
//include('logged_in_check.php');

//if (session_status() == PHP_SESSION_NONE) {
//    session_start();
//}

// Kullanıcı oturum açmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: log_in.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Kullanıcının sepetini veri tabanından çekme
$cart_sql = "SELECT c.*, p.product_name, p.product_picture, p.price 
             FROM cart c 
             JOIN products p ON c.product_id = p.product_id 
             WHERE c.user_id = '$user_id'";
$cart_result = berkhoca_query_parser($cart_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($cart_result)) {
        $error_message = "Your cart is empty. Please add items to your cart before proceeding.";
    } elseif (!isset($_SESSION['shipping'])) {
        $error_message = "Shipping details are missing. Please fill in the shipping details before proceeding.";
        header("Location: shipping_detail_page.php");
        exit();
    } else {
        $shipping_details = $_SESSION['shipping'];
        $name = $shipping_details['name'];
        $surname = $shipping_details['surname'];
        $address = $shipping_details['address'];
        $phone = $shipping_details['phone'];

        $_SESSION['payment_details'] = [
            'card_number' => $_POST['card_number'],
            'card_expiry' => $_POST['card_expiry'],
            'card_cvc' => $_POST['card_cvc']
        ];

        // Sipariş verilerini veritabanına kaydet ve stok miktarını güncelle
        foreach ($cart_result as $item) {
            $product_id = $item['product_id'];
            $product_name = $item['product_name'];
            $stock_quantity = $item['stock_quantity'];
            $product_picture = $item['product_picture'];
            $product_price = $item['price'];

            // Siparişi kaydet
            $insert_order_sql = "INSERT INTO orders (user_id, product_id, product_name, stock_quantity, product_picture, product_price, order_date, name, surname, address, phone) VALUES (
                '$user_id', 
                '$product_id', 
                '$product_name', 
                '$stock_quantity', 
                '$product_picture', 
                '$product_price', 
                NOW(),
                '$name',
                '$surname',
                '$address',
                '$phone'
            )";
            berkhoca_query_parser($insert_order_sql);

            // Stok miktarını güncelle
            $update_stock_sql = "UPDATE products SET stock_quantity = stock_quantity - '$stock_quantity' WHERE product_id = '$product_id'";
            berkhoca_query_parser($update_stock_sql);
        }

        // Sepeti temizle
        $clear_cart_sql = "DELETE FROM cart WHERE user_id = '$user_id'";
        berkhoca_query_parser($clear_cart_sql);

        // Başarı sayfasına yönlendirme
        header("Location: success_page.php");
        exit();
    }
}
?>

<?php include('header.php'); ?>

<head>
    <style>
        .payment-form-container {
            width: 80%;
            margin: 20px auto;
        }

        .payment-form {
            display: flex;
            flex-direction: column;
        }

        .payment-form input {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 1em;
        }

        .payment-form button {
            padding: 10px;
            font-size: 1em;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .payment-form button:hover {
            background-color: #218838;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>

    <div id="content">
        <div id="content-header">
            <h1>Payment Details</h1>
        </div>

        <div id="content-container">
            <div class="payment-form-container">
                <?php if (isset($error_message)) : ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form class="payment-form" method="POST">
                    <input type="text" name="card_number" placeholder="Card Number" required />
                    <input type="text" name="card_expiry" placeholder="Card Expiry Date (MM/YY)" required />
                    <input type="text" name="card_cvc" placeholder="CVC" required />
                    <button type="submit">Proceed</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>
