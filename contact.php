<?php 

include "db.php";

session_start();
function contactAdmin($pdo,$name, $email ,$gender ,$message){
    if (!isset($_SESSION['role'])) {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit();
    }

    $nameErr = $emailErr = $genderErr = "";
    $name = $email = $gender = $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Name validation
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }

        // Email validation
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        // Message validation (optional)
        if (!empty($_POST["message"])) {
            $message = test_input($_POST["message"]);
        }

        // Gender validation
        if (empty($_POST["gender"])) {
            $genderErr = "Gender is required";
        } else {
            $gender = test_input($_POST["gender"]);
        }


        if (empty($nameErr) && empty($emailErr) && empty($genderErr)) {
            $sql = "INSERT INTO contacts (name, email, gender, message) VALUES (:name, :email, :gender, :message)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'gender' => $gender,
                'message' => $message
            ]);

            echo "Message sent successfully!";
        
        }
    }
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

include 'templates/contact_form.html.php';
