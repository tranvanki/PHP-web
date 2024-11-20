<?php
session_start();
ob_start(); // Start output buffering

require_once 'includes/db.php';
require_once 'includes/databasefunction.php';

// Fetch posts from the database
$posts = fetch_post($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title> HomePage </title>
</head>
<body>
    <h1>Welcome to the Homepage</h1>
    <div class="search">
        <i class="fas fa-magnifying-glass" style="margin-right: auto; position: absolute; color: grey"></i>
        <input type="text" id="searchInput" placeholder="Search posts...">
        <button id="searchButton">Search</button>
        
    </div>
    <div id="searchResults"></div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
        <p>You are logged in as an admin.</p>
        <?php 
        if (isset($_GET['logout'])) {
            session_unset();
            session_destroy();
            header("Location: login.php"); 
            exit();
        } ?>
        <a href="index.php?logout=true">Logout</a> 
    <?php else: ?>
        <a class="btnLogin-popup" href="login.php">Login</a> 
        <a class="btnLogin-popup" href="signup.html">Signup</a>
    <?php endif; ?>
    <div class="container my-5"></div>
        <h2>All Posts</h2>
        <?php displayPosts($posts); ?>
        // $sql = "SELECT *"
    
</body>
</html>

<?php
// Capture the page content into $output
$output = ob_get_clean();

// Include the layout and pass the output

include "templates/layout.html.php";
?>
