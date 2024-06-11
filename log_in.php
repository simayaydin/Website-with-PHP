<?php
include("config.php");

//if (session_status() == PHP_SESSION_NONE) {
//    session_start();
//}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kullanıcıyı veritabanından alma sorgusu
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = berkhoca_query_parser($sql);

    if ($result && count($result) > 0) {
        $user = $result[0];
        if ($password === $user['password']) { // Şifre kontrolü
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            header('Location: index.php');
            exit();
        } else {
            $error_message = "Incorrect password.";
            error_log("Incorrect password for user with email: $email");
        }
    } else {
        $error_message = "User not found.";
        error_log("User not found with email: $email");
    }
}
?>

<?php include('header.php'); ?>

<head>
    <style>
        .login-container {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .login-form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
        }
    </style>
</head>

<body>

<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>

    <div id="content">
        <div id="content-header">
            <h1>Login</h1>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="login-container">
                <?php if (isset($error_message)) : ?>
                    <p class="error-message"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <form class="login-form" method="POST" action="log_in.php">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div> <!-- /#content-container -->
    </div> <!-- #content -->

</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
