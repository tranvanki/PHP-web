<?php
session_start();
include "includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        echo "Invalid email format!"; 
        exit(); 
    }
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists, PDO::PARAM_STR:tells PDO to treat the parameter as a string
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetch()) {
        echo "Username is already taken!";
    } else {
        // Prepare to insert new user record
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Set session variables and redirect
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            ob_start();
            echo "Sign Up successfully! Redirecting...";
            header("Location: index.php");
            
            exit();
        } else {
            echo "An error occurred during signup. Please try again.";
        }
    }
} else {
    echo "Invalid request.";
}
