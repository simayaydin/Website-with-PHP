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

// Kullanıcının tüm siparişlerini veri tabanından çekme
$order_sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$order_result = berkhoca_query_parser($order_sql);

?>

<?php include('header.php'); ?>

<head>
    <style>
        .order-history-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .order-history-container h1 {
            color: #333;
        }

        .order-history {
            text-align: left;
            margin-top: 20px;
        }

        .order-history table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-history th, .order-history td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .order-history th {
            background-color: #f2f2f2;
            color: #333;
        }

        .order-history img {
            width: 100px;
            height: auto;
        }
    </style>
</head>

<body>

<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">
        <div id="content-header">
            <h1>Order History</h1>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="order-history-container">
                <h1>Your Order History</h1>

                <?php if (count($order_result) > 0) : ?>
                    <div class="order-history">
                        <h2>All Orders</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Order Date</th>
                                    <th>Name</th>
                                    <th>Surname</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_result as $order) : ?>
                                    <tr>
                                        <td><?php echo $order['product_name']; ?></td>
                                        <td><?php echo $order['stock_quantity']; ?></td>
                                        <td><?php echo $order['product_price']; ?></td>
                                        <td><img src="http://localhost/admin_panel/<?php echo $order['product_picture']; ?>" alt="<?php echo $order['product_name']; ?>" onerror="this.src='http://localhost/admin_panel/images/placeholder_image.jpg';"></td>
                                        <td><?php echo $order['order_date']; ?></td>
                                        <td><?php echo $order['name']; ?></td>
                                        <td><?php echo $order['surname']; ?></td>
                                        <td><?php echo $order['address']; ?></td>
                                        <td><?php echo $order['phone']; ?></td>
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
ü