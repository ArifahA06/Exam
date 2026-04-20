<?php

session_start();
require_once 'Configure.php';

if (isset($_POST['btn'])) {
    $first_name = $_POST['first_name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

    $check_email = $conn->query("SELECT Email FROM account_sign_up WHERE email = ?");
    if ($check_email->num_rows > 0) {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Email already exists. Please use a different email address.'
        ];
        $_SESSION['active_form'] = 'register';
    } else {
        $conn->query("INSERT INTO account_sign_up (First_Name, Surname, Email, Password) VALUES (?, ?, ?, ?)");
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

    $result = $conn->query("SELECT * FROM account_sign_up WHERE MembershipID = ?");
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