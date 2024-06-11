<?php
include("config.php");
//include("logged_in_check.php");

// Kategorileri adlarına göre alfabetik ve sıralı olarak sıralamak için SQL sorgusu
$sql = "SELECT * FROM categories ORDER BY category_order ASC, category_name ASC";
$result = berkhoca_query_parser($sql);

// Renk paleti tanımlaması
$colors = ['#ffcccc', '#ccffcc', '#ccccff', '#ffffcc', '#ffccff', '#ccffff'];
$color_count = count($colors);

// Renkleri karıştır
shuffle($colors);
?>

<?php include('header.php'); ?>

<head>
    <!-- Diğer head içerikleri -->

    <style>
        .category-card {
            background-color: #f9f9f9; /* Arka plan rengi */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 300px; /* Kartların yüksekliğini artırdık */
            width: 350px; /* Kartların genişliğini artırdık */
            margin: 15px; /* Kartlar arasına boşluk eklemek için */
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            text-decoration: none; /* Bağlantı alt çizgisini kaldır */
            color: inherit; /* Bağlantı rengini devral */
        }

        .category-card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .category-title {
            color: #333;
            font-size: 1.5em;
        }

        .category-description {
            font-size: 1em;
            color: #777;
        }

        #content-container .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Kartların hizalanması için */
        }

        /* Kartların tam olarak hizalanması için ek stil */
        .col-md-4, .col-sm-6, .col-xs-12 {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Kartların genişliklerini ayarlamak için */
        .category-wrapper {
            width: 100%;
            max-width: 350px; /* Kartların maksimum genişliğini artırdık */
        }
    </style>
</head>

<body>

<div id="wrapper">

    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">      
        
        <div id="content-header">
            <h1>Categories</h1>
        </div> <!-- #content-header --> 

        <div id="content-container">
            <div class="row">
                <?php
                // Her kategori için grid öğesi oluştur
                $color_index = 0;
                foreach ($result as $category) {
                    $category_id = $category['category_id'];
                    $background_color = $colors[$color_index];
                    $color_index = ($color_index + 1) % $color_count;

                    echo "<div class='col-md-4 col-sm-6 col-xs-12'>";
                    echo "<a href='category_detail_page.php?id={$category_id}' class='category-wrapper' style='background-color: {$background_color};'>"; // Bağlantı ekledik
                    echo "<div class='category-card'>";
                    echo "<h3 class='category-title'>{$category['category_name']}</h3>";
                    echo "<p class='category-description'>Explore the best products in the {$category['category_name']} category.</p>";
                    echo "</div>";
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
