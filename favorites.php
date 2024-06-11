<?php
//if (session_status() == PHP_SESSION_NONE) {
//   session_start();
//}

include("config.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: log_in.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Favori ürünleri çekmek için SQL sorgusu
$favorites_sql = "SELECT products.*, categories.category_name FROM favorites 
                  JOIN products ON favorites.product_id = products.product_id 
                  LEFT JOIN categories ON products.category_id = categories.category_id 
                  WHERE favorites.user_id = {$user_id}";
$favorites_result = berkhoca_query_parser($favorites_sql);

// Favorilerden Çıkart işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_favorites'])) {
    $product_id = intval($_POST['product_id']);
    $favorite_delete_sql = "DELETE FROM favorites WHERE user_id = {$user_id} AND product_id = {$product_id}";
    berkhoca_query_parser($favorite_delete_sql);

    // Yönlendirme yaparak formun tekrar gönderimini önle
    header("Location: favorites.php");
    exit;
}
?>

<?php include('header.php'); ?>

<head>
    <style>
        .favorites-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            margin-top: 20px;
        }

        .favorite-card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 300px;
            margin: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .favorite-title {
            color: #333;
            font-size: 1.5em;
            margin: 10px 0;
        }

        .favorite-price {
            font-size: 1.2em;
            color: #777;
            margin: 10px 0;
        }

        .favorite-description {
            font-size: 0.9em;
            color: #555;
            margin: 10px 0;
            line-height: 1.6;
        }

        .favorite-image {
            width: 100%;
            height: auto;
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .favorite-info {
            font-size: 0.9em;
            color: #555;
            margin: 10px 0;
        }

        .remove-from-favorites-button {
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

        .remove-from-favorites-button:hover {
            background-color: #FF1493; /* Derin Pembe Renk */
        }

        .add-to-cart-button {
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

        .add-to-cart-button:hover {
            background-color: #218838;
        }

        .button-container {
            display: flex;
            justify-content: space-around;
            width: 100%;
        }
    </style>
</head>

<body>

<div id="wrapper">

    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>

    <div id="content">

        <div id="content-header">
            <h1>Your Favorites</h1>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="favorites-container">
                <?php foreach ($favorites_result as $favorite) : ?>
                    <div class="favorite-card">
                        <img src="http://localhost/admin_panel/<?php echo ($favorite['product_picture']); ?>" alt="<?php echo ($favorite['product_name']); ?>" class="favorite-image" onerror="this.src='http://localhost/admin_panel/images/placeholder_image.jpg';" />
                        <h2 class="favorite-title"><?php echo ($favorite['product_name']); ?></h2>
                        <p class="favorite-price">Price: <?php echo ($favorite['price']); ?> USD</p>
                        
                        
                        <!-- Button Container -->
                        <div class="button-container">
                            <!-- Sepete Ekle Formu -->
                            <form method="post" action="product_detail.php?id=<?php echo ($favorite['product_id']); ?>">
                                <button type="submit" class="add-to-cart-button">View Product</button>
                            </form>

                            <!-- Favorilerden Çıkart Formu -->
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="<?php echo ($favorite['product_id']); ?>">
                                <input type="hidden" name="remove_from_favorites" value="1">
                                <button type="submit" class="remove-from-favorites-button">
                                    <i class="fa fa-heart-broken"></i> Remove from Favorites
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> <!-- /#content-container -->
    </div> <!-- #content -->

</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
