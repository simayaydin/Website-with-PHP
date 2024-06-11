<?php
include("config.php");


// Ürünleri ve kategorilerini almak için SQL sorgusu
$product_sql = "SELECT products.*, categories.category_name FROM products 
                LEFT JOIN categories ON products.category_id = categories.category_id 
                ORDER BY product_name ASC";
$product_result = berkhoca_query_parser($product_sql);
$products = $product_result;

$category_sql = "SELECT * FROM categories ORDER BY category_name ASC";
$category_result = berkhoca_query_parser($category_sql);
$categories = $category_result;

if ($products === false || $categories === false) {
    die("Query failed: " . $conn->error);
}
?>

<?php include('header.php'); ?>

<head>
    <style>
        .category-card {
            background-color: #e0f7fa; /* Arka plan rengi */
            color: #006064; /* Metin rengi */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100px;
            width: 150px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
        }

        .category-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #b2ebf2; /* Hover arka plan rengi */
            color: #004d40; /* Hover metin rengi */
        }

        .product-card {
            background-color: #fffde7; /* Arka plan rengi */
            color: #f57f17; /* Metin rengi */
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
            text-decoration: none;
        }

        .product-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #fff9c4; /* Hover arka plan rengi */
            color: #e65100; /* Hover metin rengi */
        }

        .category-title, .product-title {
            color: inherit;
            font-size: 1.2em;
            margin: 10px 0;
        }

        .product-price {
            font-size: 1em;
            color: inherit;
        }

        .product-image, .category-image {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain; /* Changed to contain */
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        #content-container .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .category-row {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            white-space: nowrap;
        }

        .category-row::-webkit-scrollbar {
            display: none; /* Kaydırma çubuğunu gizle */
        }

        .categories-heading {
            margin-bottom: 10px;
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
            <h1>Sim Shopping</h1>
        </div> <!-- #content-header --> 

        <div id="content-container">
            <h3 class="categories-heading">Categories</h3>
            <div class="category-row">
                <?php
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        echo "<div>";
                        echo "    <a href='category_detail_page.php?id={$category['category_id']}' class='category-card'>";
                        echo "        <h3 class='category-title'>{$category['category_name']}</h3>";
                        echo "    </a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No categories found.</p>";
                }
                ?>
            </div> <!-- /.category-row -->

            <h3 class="heading">Products</h3>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <?php
                        if (!empty($products)) {
                            foreach ($products as $product) {
                                $product_picture_url = "http://localhost/admin_panel/" . $product['product_picture'];
                                
                                echo "<div class='col-md-3 col-sm-6'>";
                                echo "<a href='product_detail.php?id={$product['product_id']}' class='product-card'>";
                                echo "<img src='$product_picture_url' alt='{$product['product_name']}' class='product-image' onerror=\"this.src='http://localhost/admin_panel/images/placeholder_image.jpg';\" />";
                                echo "<h3 class='product-title'>{$product['product_name']}</h3>";
                                echo "<p class='product-price'>Price: {$product['price']} USD</p>";
                                echo "</a>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No products found.</p>";
                        }
                        ?>
                    </div> <!-- /.row -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /#content-container -->
    </div> <!-- #content -->    
</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
