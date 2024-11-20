<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
     $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
    }
ob_start();
require 'db.php'; 
require 'includes/databasefunction.php';

if ($_SEVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $module_id = $_POST['module_id'];
    $image = $_FILES['image'];
//handle file upload
    $target_dir = "uploads/"; 
    $target_file = $target_dir . basename($image["name"]); 
    $uploadOk = 1; 
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image 
    $check = getimagesize($image["tmp_name"]); 
    if($check !== false) { 
        $uploadOk = 1; 
    } else { 
        echo "File is not an image."; 
        $uploadOk = 0; 
    }

    $message = addPost($pdo, $username, $title, $content, $module_id, $image);
    
    if ($message === 'POST added successfully'){
        header("Location: index.php");
        exit();
    }else{
        echo $message;
    }
    
}

$title = "Add Post";
$output = ob_get_clean();
include "templates/add_post.html.php";
