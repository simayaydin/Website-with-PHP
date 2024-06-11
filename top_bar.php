<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sim Shopping</title>
    <style>
        .site-logo {
            font-size: 2em; /* Yazı boyutunu ayarlar */
            text-align: center; /* Metni ortalar */
            font-weight: red; /* Yazıyı kalın yapar */
        }
    </style>
</head>
<body>

<header id="header">
    <h1 id="site-logo" class="site-logo">
        <?php echo 'Sim Shopping'; ?>
    </h1>   

    <a href="javascript:;" data-toggle="collapse" data-target=".top-bar-collapse" id="top-bar-toggle" class="navbar-toggle collapsed">
        <i class="fa fa-cog"></i>
    </a>

    <a href="javascript:;" data-toggle="collapse" data-target=".sidebar-collapse" id="sidebar-toggle" class="navbar-toggle collapsed">
        <i class="fa fa-reorder"></i>
    </a>
</header> <!-- header -->

<nav id="top-bar" class="collapse top-bar-collapse">
    <ul class="nav navbar-nav pull-right">
        <?php if (isset($_SESSION['user_name'])) : ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                    <i class="fa fa-user"></i>
                    <?php echo $_SESSION['user_name']; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li class="divider"></li>
                    <li>
                        <a href="logout.php">
                            <i class="fa fa-sign-out"></i> 
                            Logout
                        </a>
                    </li>
                </ul>
            </li>
        <?php else : ?>
            <li>
                <a href="log_in.php" class="login-button">
                    <i class="fa fa-sign-in"></i>
                    Log In
                </a>
            </li>
            <li>
                <a href="sign_in.php" class="signin-button">
                    <i class="fa fa-user-plus"></i>
                    Sign In
                </a>
            </li>
        <?php endif; ?>
        
        <!-- Sepet Butonu -->
        <li>
            <a href="cart_page.php" class="cart-button">
                <i class="fa fa-shopping-cart"></i>
                Cart
            </a>
        </li>
    </ul>
</nav> <!-- /#top-bar -->

<!-- Buton Stilleri -->
<style>
    .cart-button, .login-button, .signin-button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 1em;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }

    .cart-button:hover, .login-button:hover, .signin-button:hover {
        background-color: #218838;
    }

    .cart-button i, .login-button i, .signin-button i {
        margin-right: 10px;
    }
</style>

</body>
</html>
