<?php

session_start();
require_once 'Configure.php';

if (isset($_POST['btn'])) {
    $first_name = $_POST['first_name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

    $check_email = $conn->query("SELECT Email FROM account sign up WHERE email = ?");
    if ($check_email->num_rows > 0) {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Email already exists. Please use a different email address.'
        ];
        $_SESSION['active_form'] = 'register';
    } else {
        $conn->query("INSERT INTO account sign up (First_Name, Surname, Email, Password) VALUES (?, ?, ?, ?)");
        $_SESSION['alerts'][] = [
            'type' => 'success',
            'message' => 'Registration successful.'
        ];
        $_SESSION['active_form'] = 'login';
    }
    header('Location: Account.php');
    exit();
}

if (isset($_POST['btn1'])) {
    $membership_id = $_POST['membership_id'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $conn->query("SELECT * FROM account sign up WHERE MembershipID = ?");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['alerts'][] = [
                'type' => 'success',
                'message' => 'Login successful.'
            ];
            $_SESSION['active_form'] = 'login';
        } else {
            $_SESSION['alerts'][] = [
                'type' => 'error',
                'message' => 'Invalid password. Please try again.'
            ];
            $_SESSION['active_form'] = 'login';
        }
    } else {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Membership ID not found. Please check your credentials.'
        ];
        $_SESSION['active_form'] = 'login';
    }
    header('Location: Account.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Access</title>
    <link rel="stylesheet" href="Account-Style.css">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css">
</head>

<body>

    <header>
        <h2 class="logo"></h2>
        <nav class="navigation">
            <a href="About.html">About</a>
            <a href="Farmers.html">Farmers</a>
            <a href="Shop.html">Shop</a>
            <a href="Support.html">Support</a>
            <button class="btnLogin-popup">Account</button>
        </nav>
    </header>
</body>

</html>