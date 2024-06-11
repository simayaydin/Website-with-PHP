<?php
include("config.php");
//include("logged_in_check.php");

// Kategori ID'sini URL'den al
$category_id = $_GET['id'];

// Kategori bilgilerini ve ürünlerini çekmek için SQL sorguları
$category_sql = "SELECT * FROM categories WHERE category_id = {$category_id}";
$category_result = berkhoca_query_parser($category_sql);
$category = $category_result[0];

// Kategorideki ürünleri çekmek için SQL sorgusu
$products_sql = "SELECT * FROM products WHERE category_id = {$category_id}";
$products_result = berkhoca_query_parser($products_sql);
?>

<?php include('header.php'); ?>

<head>
    <style>
        .product-card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 350px;
            width: 300px;
            margin: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            background-color: #f9f9f9;
            text-decoration: none;
            color: inherit;
        }

        .product-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product-title {
            color: #333;
            font-size: 1.2em;
            margin: 10px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-price {
            font-size: 1em;
            color: #777;
        }

        .product-image {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain; /* Değiştirildi: contain */
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        #content-container .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .col-md-3, .col-sm-6 {
            display: flex;
            justify-content: center;
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
            <h1><?php echo $category['category_name']; ?></h1>
            <p>Explore the best products in the <?php echo $category['category_name']; ?> category.</p>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="row">
                <?php
                // Her ürün için grid öğesi oluştur
                foreach ($products_result as $product) {
                    $product_picture_url = "http://localhost/admin_panel/" . $product['product_picture'];

                    echo "<div class='col-md-3 col-sm-6'>";
                    echo "<a href='product_detail.php?id={$product['product_id']}' class='product-card'>";
                    echo "<img src='$product_picture_url' alt='{$product['product_name']}' class='product-image' onerror=\"this.src='http://localhost/admin_panel/images/placeholder_image.jpg';\" />";
                    echo "<h3 class='product-title'>{$product['product_name']}</h3>";
                    echo "<p class='product-price'>Price: {$product['price']} USD</p>";
                    echo "</a>";
                    echo "</div>";
                }
                ?>
            </div> <!-- /.row -->
        </div> <!-- /#content-container -->
    </div> <!-- #content -->

</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
