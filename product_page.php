<?php
include("config.php");
//include("logged_in_check.php");

// Ürünleri ve kategorilerini almak için SQL sorgusu
$sql = "SELECT products.*, categories.category_name FROM products 
        LEFT JOIN categories ON products.category_id = categories.category_id 
        ORDER BY product_name ASC";
$result = berkhoca_query_parser($sql);

$products = $result;

if ($products === false) {
    die("Query failed: " . $conn->error);
}
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
        }

        .product-price {
            font-size: 1em;
            color: #777;
        }

        .product-image {
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
            <h1>Products</h1>
        </div> <!-- #content-header --> 

        <div id="content-container">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h3 class="heading"></h3>
                    <div class="row">
                        <?php
                        if (!empty($products)) {
                            foreach ($products as $product) {
                                $product_picture_url = "http://localhost/admin_panel/" . $product['product_picture'];
                                
                                echo "<div class='col-md-3 col-sm-6'>";
                                echo "    <a href='product_detail.php?id={$product['product_id']}' class='product-card'>";
                                echo "        <img src='$product_picture_url' alt='{$product['product_name']}' class='product-image' onerror=\"this.src='http://localhost/admin_panel/images/placeholder_image.jpg';\" />";
                                echo "        <h3 class='product-title'>{$product['product_name']}</h3>";
                                echo "        <p class='product-price'>Price: {$product['price']} USD</p>";
                                echo "    </a>";
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
