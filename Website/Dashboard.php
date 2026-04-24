<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Account.php');
    exit;
}

require_once 'Configure.php';

$user_id = $_SESSION['user_id'];

/* --- Account details --- */
$stmt = $conn->prepare(
    "SELECT MembershipID, First_Name, Surname, Email, Created_At
     FROM `account sign up`
     WHERE MembershipID = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* --- Order history --- */
$stmt = $conn->prepare(
    "SELECT OrderID, OrderDate, Total, Status
     FROM orders
     WHERE MembershipID = ?
     ORDER BY OrderDate DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link rel="stylesheet" href="Account-Style.css">
</head>
<body>

<header>
    <h2 class="logo">My Account</h2>
    <nav class="navigation">
        Shop.htmlShop</a>
        Logout.phpLog out</a>
    </nav>
</header>

<main class="dashboard">

    <!-- ACCOUNT DETAILS -->
    <section class="account-section">
        <h2>Account Details</h2>
        <p><strong>Membership ID:</strong> <?= $user['MembershipID'] ?></p>
        <p><strong>Name:</strong>
            <?= htmlspecialchars($user['First_Name'] . ' ' . $user['Surname']) ?>
        </p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['Email']) ?></p>
        <p><strong>Member since:</strong> <?= $user['Created_At'] ?></p>
    </section>

    <!-- ORDER HISTORY -->
    <section class="account-section">
        <h2>Order History</h2>

        <?php if ($orders->num_rows > 0): ?>
            <table class="order-table">
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Total (£)</th>
                    <th>Status</th>
                </tr>
                <?php while ($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $order['OrderID'] ?></td>
                    <td><?= $order['OrderDate'] ?></td>
                    <td><?= number_format($order['Total'], 2) ?></td>
                    <td><?= htmlspecialchars($order['Status']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>You haven’t placed any orders yet.</p>
        <?php endif; ?>
    </section>

</main>

</body>
</html>