<?php
include("config.php");
//include("logged_in_check.php");

// Oturum başlatma (Eğer oturum başlatılmadıysa başlat)
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
$cart_sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
$cart_result = berkhoca_query_parser($cart_sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (count($cart_result) === 0) {
        $error_message = "Your cart is empty. Please add items to your cart before proceeding.";
    } else {
        // Kargo bilgilerini oturuma kaydet
        $_SESSION['shipping'] = [
            'name' => $_POST['name'],
            'surname' => $_POST['surname'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone']
        ];

        // Ödeme sayfasına yönlendirme
        header('Location: payment_page.php');
        exit();
    }
}
?>

<?php include('header.php'); ?>

<head>
    <style>
        .shipping-container {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .shipping-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .shipping-form button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .shipping-form button:hover {
            background-color: #c82333;
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
            <h1>Shipping Details</h1>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="shipping-container">
                <?php if (isset($error_message)) : ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form class="shipping-form" method="POST" action="shipping_detail_page.php">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="surname">Surname:</label>
                    <input type="text" id="surname" name="surname" required>
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                    <button type="submit">Proceed</button>
                </form>
            </div>
        </div> <!-- /#content-container -->
    </div> <!-- #content -->

</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
