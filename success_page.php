<?php
include('config.php');
//include('logged_in_check.php');

//if (session_status() == PHP_SESSION_NONE) {
//   session_start();
//}

// Kullanıcı oturum açmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: log_in.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Kullanıcının son siparişlerini ve kargo bilgilerini veri tabanından çekme
$order_date_sql = "SELECT MAX(order_date) as latest_order_date FROM orders WHERE user_id = '$user_id'";
$order_date_result = berkhoca_query_parser($order_date_sql);
$latest_order_date = $order_date_result[0]['latest_order_date'];

$order_sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND order_date = '$latest_order_date'";
$order_result = berkhoca_query_parser($order_sql);

// Kargo bilgilerini almak için son siparişi çekiyoruz
$shipping_sql = "SELECT name, surname, address, phone FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC LIMIT 1";
$shipping_result = berkhoca_query_parser($shipping_sql);

$payment_details = $_SESSION['payment_details'];
$card_number = $payment_details['card_number'];
$masked_card_number = str_repeat('*', strlen($card_number) - 4) . substr($card_number, -4);
?>

<?php include('header.php'); ?>

<head>
    <style>
        .success-container {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fdfdfd;
            text-align: center;
        }

        .success-container h1 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .success-container p {
            font-size: 1.2em;
            color: #333;
            margin: 5px 0;
        }

        .order-details, .shipping-details {
            text-align: left;
            margin-top: 20px;
        }

        .order-details table, .shipping-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-details th, .order-details td, .shipping-details th, .shipping-details td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .order-details th, .shipping-details th {
            background-color: #f2f2f2;
            color: #333;
        }

        .order-details img {
            width: 100px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .shipping-details {
            margin-top: 30px;
        }

        .shipping-details h2, .order-details h2 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .shipping-details table, .order-details table {
            border: none;
        }

        .shipping-details th, .order-details th {
            background-color: #f9f9f9;
            font-weight: normal;
            color: #555;
        }

        .shipping-details td, .order-details td {
            color: #333;
            text-align: center;
        }

        .success-container hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>

<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">
        <div id="content-header">
            <h1>Payment Successful</h1>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="success-container">
                <h1>Thank You!</h1>
                <p>Your payment was successful.</p>
                <p>Your order has been placed successfully.</p>
                <p>You will receive a confirmation email shortly.</p>
                <p>Payment has been successfully received from your card ending in <?php echo ($masked_card_number); ?></p>
                <hr>

                <?php if (count($shipping_result) > 0) : ?>
                    <div class="shipping-details">
                        <h2>Shipping Details</h2>
                        <table>
                            <tr>
                                <th>Name</th>
                                <td><?php echo ($shipping_result[0]['name']); ?></td>
                            </tr>
                            <tr>
                                <th>Surname</th>
                                <td><?php echo ($shipping_result[0]['surname']); ?></td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td><?php echo ($shipping_result[0]['address']); ?></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td><?php echo ($shipping_result[0]['phone']); ?></td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>

                <hr>

                <?php if (count($order_result) > 0) : ?>
                    <div class="order-details">
                        <h2>Order Details</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_result as $order) : ?>
                                    <tr>
                                        <td><?php echo ($order['product_name']); ?></td>
                                        <td><?php echo ($order['stock_quantity']); ?></td>
                                        <td><?php echo ($order['product_price']); ?> USD</td>
                                        <td><img src="http://localhost/admin_panel/<?php echo ($order['product_picture']); ?>" alt="<?php echo ($order['product_name']); ?>" onerror="this.src='http://localhost/admin_panel/images/placeholder_image.jpg';"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p>No order details found.</p>
                <?php endif; ?>
            </div>
        </div> <!-- /#content-container -->
    </div> <!-- #content -->

</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
