<?php
include("config.php");
//session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Şifreyi hash'lemeden kaydediyoruz
    $username = $_POST['username'];

    // Kullanıcıyı veritabanına ekleme sorgusu
    $sql = "INSERT INTO users (first_name, last_name, email, password, username) VALUES ('$first_name', '$last_name', '$email', '$password','$username')";
    $result = berkhoca_query_parser($sql);

    if ($result) {
        $_SESSION['success_message'] = "Registration successful. You can now log in.";
        header('Location: log_in.php');
        exit();
    } else {
        $error_message = "Registration failed. Please try again.";
    }
}
?>

<?php include('header.php'); ?>

<head>
    <style>
        .register-container {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .register-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .register-form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .register-form button:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }
    </style>
</head>

<body>

<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">
        <div id="content-header">
            <h1>Register</h1>
        </div> <!-- #content-header -->

        <div id="content-container">
            <div class="register-container">
                <?php if (isset($error_message)) : ?>
                    <p class="error-message"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <form class="register-form" method="POST" action="sign_in.php">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="username">Username:</label>
                    <input type="username" id="username" name="username" required>
                    <button type="submit">Sign In</button>

                </form>
            </div>
        </div> <!-- /#content-container -->
    </div> <!-- #content -->

</div> <!-- #wrapper -->

<?php include('footer.php'); ?>

</body>
</html>
