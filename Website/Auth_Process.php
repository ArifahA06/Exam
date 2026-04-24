<?php
session_start();
require_once 'Configure.php';

if ($_POST['action'] === 'register') {

    $first_name = trim($_POST['first_name']);
    $surname    = trim($_POST['surname']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];

    if (!$first_name || !$surname || !$email || !$password) {
        $_SESSION['alerts'][] = ['type'=>'error','message'=>'All fields required'];
        $_SESSION['active_form'] = 'register';
        header('Location: Account.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT Email FROM `account sign up` WHERE Email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alerts'][] = ['type'=>'error','message'=>'Email already exists'];
        $_SESSION['active_form'] = 'register';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $insert = $conn->prepare(
            "INSERT INTO `account sign up` (`First Name`,`Surname`,`Email`,`Password`)
             VALUES (?,?,?,?)"
        );
        $insert->bind_param("ssss",$first_name,$surname,$email,$hash);
        $insert->execute();

        $_SESSION['alerts'][] = ['type'=>'success','message'=>'Registration successful'];
        $_SESSION['active_form'] = 'login';
    }

    header('Location: Account.php');
    exit;
}

if ($_POST['action'] === 'login') {

    $id = $_POST['membership_id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT MembershipID, `First Name`, Password
         FROM `account sign up` WHERE MembershipID=?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['first_name'] = $user['First Name'];
            $_SESSION['alerts'][] = ['type'=>'success','message'=>'Login successful'];
        } else {
            $_SESSION['alerts'][] = ['type'=>'error','message'=>'Incorrect password'];
        }
    } else {
        $_SESSION['alerts'][] = ['type'=>'error','message'=>'Membership ID not found'];
    }

    $_SESSION['active_form'] = 'login';
    header('Location: Account.php');
    exit;
}

$_SESSION['user_id'] = $user['MembershipID'];
$_SESSION['first_name'] = $user['First Name'];

$_SESSION['alerts'][] = [
    'type' => 'success',
    'message' => 'Login successful.'
];

header('Location: Dashboard.php');
exit;
