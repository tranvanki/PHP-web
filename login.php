<?php
include "includes/db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $msg = 'All fields are required.';
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && isset($user['password']) && password_verify($password, $user['password'])) { // Check if user and password key exist and verify password
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            header("Location: login_success.php");
            exit();
        } else {
            $msg = 'Username or Password not found.';
        }
    }
    

}
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login&signup.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>LOGIN PAGE</title>
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h3> Welcome Back </h3>
            <form action="login.php" method="POST" id="loginform">
                <input type="text" class="form-control" name="username" placeholder="Username" id="username" required>
                <input type="password" class="form-control" name="password" placeholder="Password" id="password" required autocomplete="current-password">
                <input type="submit" class="btn btn-primary" name="login" value="Log In"> 
            </form>
            <?php if (isset($msg)) {
                echo '<p style="color:red;">' . htmlspecialchars($msg) . '</p>';
            } ?>
        </div>
    </div>
</body>
</html>
