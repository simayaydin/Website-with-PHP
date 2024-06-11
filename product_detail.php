<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("config.php");

// Ürün ID'sini URL'den al
$product_id = intval($_GET['id']);

// Ürün bilgilerini çekmek için SQL sorgusu
$product_sql = "SELECT products.*, categories.category_name FROM products 
                LEFT JOIN categories ON products.category_id = categories.category_id 
                WHERE product_id = {$product_id}";
$product_result = berkhoca_query_parser($product_sql);
$product = $product_result[0]; // Sorgu sonucunun ilk satırını al

if (!$product) {
    die("Query failed: Product not found.");
}

// Sepete Ekle işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['add_to_favorites'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: log_in.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Ürün zaten sepette mi kontrol et
    $cart_check_sql = "SELECT * FROM cart WHERE user_id = {$user_id} AND product_id = {$product_id}";
    $cart_check_result = berkhoca_query_parser($cart_check_sql);

    if (count($cart_check_result) > 0) {
        // Ürün zaten sepette, miktarı güncelle
        $cart_update_sql = "UPDATE cart SET stock_quantity = stock_quantity + {$quantity} WHERE user_id = {$user_id} AND product_id = {$product_id}";
        berkhoca_query_parser($cart_update_sql);
    } else {
        // Ürün sepette değil, yeni kayıt ekle
        $cart_insert_sql = "INSERT INTO cart (user_id, product_id, stock_quantity) VALUES ({$user_id}, {$product_id}, {$quantity})";
        berkhoca_query_parser($cart_insert_sql);
    }

    // Yönlendirme yaparak formun tekrar gönderimini önle
    header("Location: product_detail.php?id={$product_id}");
    exit;
}

// Favorilere Ekle işlemi
if (isset($_POST['add_to_favorites'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: log_in.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Ürün zaten favorilerde mi kontrol et
    $favorite_check_sql = "SELECT * FROM favorites WHERE user_id = {$user_id} AND product_id = {$product_id}";
    $favorite_check_result = berkhoca_query_parser($favorite_check_sql);

    if (count($favorite_check_result) === 0) {
        // Ürün favorilerde değil, yeni kayıt ekle
        $favorite_insert_sql = "INSERT INTO favorites (user_id, product_id) VALUES ({$user_id}, {$product_id})";
        berkhoca_query_parser($favorite_insert_sql);
    }

    // Yönlendirme yaparak formun tekrar gönderimini önle
    header("Location: product_detail.php?id={$product_id}");
    exit;
}
?>

<?php include('header.php'); ?>

<head>
    <style>
        .product-detail-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin-top: 20px;
        }

        .product-detail-card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 600px;
            margin: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .product-detail-title {
            color: #333;
            font-size: 2em;
            margin: 10px 0;
        }

        .product-detail-price {
            font-size: 1.5em;
            color: #777;
            margin: 10px 0;
        }

        .product-detail-description {
            font-size: 1em;
            color: #555;
            margin: 10px 0;
            line-height: 1.6;
        }

        .product-detail-image {
            width: 100%;
            height: auto;
            max-width: 400px;
            max-height: 400px;
            object-fit: contain;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .product-detail-info {
            font-size: 1em;
            color: #555;
            margin: 10px 0;
        }

        .add-to-cart-button, .quantity-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin: 10px;
        }

        .add-to-cart-button:hover, .quantity-button:hover {
            background-color: #218838;
        }

        .add-to-favorites-button {
            background-color: #FF69B4; /* Sıcak Pembe Renk */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin: 10px;
        }

        .add-to-favorites-button:hover {
            background-color: #FF1493; /* Derin Pembe Renk */
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
        }

        #quantity {
            width: 50px;
            text-align: center;
            font-size: 1.2em;
        }
    </style>
</head>

<body>

<div id="wrapper">

    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>

    <div id="content">

        <div id="content-header">
            <h1><?php echo ($product['product_name']); ?></h1>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="product-detail-container">
                <div class="product-detail-card">
                    <img src="http://localhost/admin_panel/<?php echo ($product['product_picture']); ?>" alt="<?php echo ($product['product_name']); ?>" class="product-detail-image" onerror="this.src='http://localhost/admin_panel/images/placeholder_image.jpg';" />
                    <h2 class="product-detail-title"><?php echo ($product['product_name']); ?></h2>
                    <p class="product-detail-price">Price: <?php echo ($product['price']); ?> TL</p>
                    <p class="product-detail-description"><?php echo nl2br(($product['description'])); ?></p>
                    <p class="product-detail-info">Stock Quantity: <?php echo ($product['stock_quantity']); ?></p>
                    <p class="product-detail-info">Category: <?php echo ($product['category_name']); ?></p>

                    <?php if ($product['stock_quantity'] > 0): ?>
                        <!-- Sepete Ekle Formu -->
                        <form method="post" action="">
                            <div class="quantity-selector">
                                <button type="button" id="decrease-quantity" class="quantity-button">-</button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo ($product['stock_quantity']); ?>" readonly />
                                <button type="button" id="increase-quantity" class="quantity-button">+</button>
                            </div>
                            <button type="submit" class="add-to-cart-button">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <p class="product-detail-info" style="color: red;">This product is out of stock.</p>
                    <?php endif; ?>

                    <!-- Favorilere Ekle Formu -->
                    <form method="post" action="">
                        <input type="hidden" name="add_to_favorites" value="1">
                        <button type="submit" class="add-to-favorites-button">
                            <i class="fa fa-heart"></i> Add to Favorites
                        </button>
                    </form>
                </div>
            </div>
        </div> <!-- /#content-container -->
    </div> <!-- #content -->

</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

<script>
document.getElementById('decrease-quantity').addEventListener('click', function() {
    var quantityInput = document.getElementById('quantity');
    var currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
});

document.getElementById('increase-quantity').addEventListener('click', function() {
    var quantityInput = document.getElementById('quantity');
    var maxValue = parseInt(quantityInput.max);
    var currentValue = parseInt(quantityInput.value);
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
});
</script>

</body>
</html>
