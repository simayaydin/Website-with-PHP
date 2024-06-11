<?php
include("config.php");

// En çok satan ürünleri çekmek için SQL sorgusu
$bestsellers_sql = "
    SELECT product_id, product_name, product_picture, SUM(stock_quantity) AS total_sold, product_price 
    FROM orders
    GROUP BY product_id, product_name, product_picture, product_price
    ORDER BY total_sold DESC
    LIMIT 10";
$bestsellers_result = berkhoca_query_parser($bestsellers_sql);
?>

<?php include('header.php'); ?>

<head>
    <style>
        .bestsellers-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            margin-top: 20px;
        }

        .bestseller-card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            width: calc(33.333% - 40px); /* Üç sütun düzeni için */
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .bestseller-title {
            color: #333;
            font-size: 1.5em;
            margin: 10px 0;
        }

        .bestseller-price {
            font-size: 1.2em;
            color: #777;
            margin: 10px 0;
        }

        .bestseller-description {
            font-size: 1em;
            color: #555;
            margin: 10px 0;
            line-height: 1.6;
        }

        .bestseller-image {
            width: 100%;
            height: auto;
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .bestseller-info {
            font-size: 0.9em;
            color: #555;
            margin: 10px 0;
        }

        .view-product-button {
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

        .view-product-button:hover {
            background-color: #218838;
        }

        /* Mobil uyumluluk için */
        @media (max-width: 768px) {
            .bestseller-card {
                width: calc(50% - 40px); /* İki sütun düzeni için */
            }
        }

        @media (max-width: 480px) {
            .bestseller-card {
                width: calc(100% - 40px); /* Tek sütun düzeni için */
            }
        }
    </style>
</head>


<body>

<div id="wrapper">

    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>

    <div id="content">

        <div id="content-header">
            <h1>Best Sellers</h1>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="bestsellers-container">
                <?php foreach ($bestsellers_result as $bestseller) : ?>
                    <div class="bestseller-card">
                        <img src="http://localhost/admin_panel/<?php echo ($bestseller['product_picture']); ?>" alt="<?php echo ($bestseller['product_name']); ?>" class="bestseller-image" onerror="this.src='http://localhost/admin_panel/images/placeholder_image.jpg';" />
                        <h2 class="bestseller-title"><?php echo ($bestseller['product_name']); ?></h2>
                        <p class="bestseller-price">Price: <?php echo ($bestseller['product_price']); ?> USD</p>
                        <p class="bestseller-info">Total Sold: <?php echo ($bestseller['total_sold']); ?></p>
                        
                        <!-- Ürün Detayına Git Formu -->
                        <form method="post" action="product_detail.php?id=<?php echo ($bestseller['product_id']); ?>">
                            <button type="submit" class="view-product-button">View Product</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> <!-- /#content-container -->
    </div> <!-- #content -->

</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
