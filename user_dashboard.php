<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_phone'])) {
    header("Location: user_login.php");
    exit;
}

$phone = $_SESSION['user_phone'];
$stmt = $conn->prepare("SELECT * FROM bookings WHERE phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Bookings</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f8ff;
            padding: 30px;
        }

        h2 {
            color: #0077b6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #0077b6;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .logout {
            float: right;
            background: #d62828;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a class="logout" href="logout.php">Logout</a>
    <h2>Welcome, <?= htmlspecialchars($phone) ?>!</h2>
    <h3>Your Ticket Bookings</h3>

    <table>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Route</th>
            <th>Time</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['route']) ?></td>
            <td><?= htmlspecialchars($row['time']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
