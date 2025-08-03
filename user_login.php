<?php
session_start();
include 'db_connect.php';

$error = "";
$bookings = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = trim($_POST['phone']);

    if (!empty($phone)) {
        $_SESSION['user_phone'] = $phone;

        $stmt = $conn->prepare("SELECT * FROM bookings WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        $bookings = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $error = "Please enter your phone number.";
    }
}

if (isset($_GET['cancel_id'])) {
    $id = (int) $_GET['cancel_id'];
    $conn->query("DELETE FROM bookings WHERE id = $id");
    header("Location: user_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login - Daewoo Transport</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #001f3f, #003566);
            margin: 0;
            padding: 40px 20px;
            color: #fff;
        }
        .container {
            max-width: 780px;
            margin: auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
            color: #001f3f;
        }
        h2 {
            text-align: center;
            color: #001f3f;
            margin-bottom: 25px;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 14px;
            width: 65%;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button, .menu-btn {
            padding: 12px 24px;
            background: #001f3f;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 8px;
            font-weight: bold;
            transition: background 0.3s ease;
            text-decoration: none;
        }
        .menu-btn:hover, button:hover {
            background: #003566;
        }
        .error {
            color: #c0392b;
            text-align: center;
            font-weight: bold;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: #f8f9fa;
            color: #001f3f;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 14px;
            text-align: center;
        }
        th {
            background-color: #003566;
            color: white;
        }
        .cancel-btn {
            background: #d90429;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .cancel-btn:hover {
            background: #9e0020;
        }
        .print-btn {
            background: #0f5132;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 5px;
            display: inline-block;
        }
        .print-btn:hover {
            background: #146c43;
        }
        h3 {
            text-align: center;
            margin-top: 30px;
            color: #001f3f;
        }
        .top-bar {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>

    <script>
        function printTicket(bookingId) {
            let ticketContent = document.getElementById('booking-' + bookingId).innerHTML;
            let win = window.open('', '', 'height=600,width=800');
            win.document.write('<html><head><title>Ticket</title></head><body style="font-family:sans-serif;">');
            win.document.write('<h2>Daewoo Bus Ticket</h2>');
            win.document.write(ticketContent);
            win.document.write('<br><br><p>Thank you for choosing Daewoo Transport.</p>');
            win.document.write('</body></html>');
            win.document.close();
            win.print();
            win.onafterprint = () => {
                win.close();
                window.location.href = 'index.php';
            };
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Daewoo User Login</h2>

    <div class="top-bar">
        <a href="index.php" class="menu-btn">Back to Home</a>
    </div>

    <?php if (empty($bookings)): ?>
        <form method="POST">
            <input type="text" name="phone" placeholder="Enter your phone number" required>
            <br><br>
            <button type="submit">View Your Tickets</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($bookings)): ?>
        <h3>🎫 Your Bookings</h3>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Name</th>
                <th>Route</th>
                <th>Date</th>
                <th>Time</th>
                <th>Seat No</th>
                <th>Fare</th>
                <th>Action</th>
            </tr>
            <?php foreach ($bookings as $b): ?>
            <?php $formattedTime = date("g:i A", strtotime($b['time'])); ?>
            <tr>
                <td><?= htmlspecialchars($b['id']) ?></td>
                <td><?= htmlspecialchars($b['name']) ?></td>
                <td><?= htmlspecialchars($b['route']) ?></td>
                <td><?= htmlspecialchars($b['date']) ?></td>
                <td><?= $formattedTime ?></td>
                <td><?= htmlspecialchars($b['seat_no'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($b['fare'] ?? 'N/A') ?></td>
                <td>
                    <a class="cancel-btn" onclick="return confirm('Cancel this ticket?')" href="?cancel_id=<?= htmlspecialchars($b['id']) ?>">Cancel</a>
                    <br>
                    <a class="print-btn" href="javascript:void(0);" onclick="printTicket('<?= htmlspecialchars($b['id']) ?>')">Print</a>
                </td>
            </tr>
            <tr style="display:none;">
                <td colspan="8">
                    <div id="booking-<?= htmlspecialchars($b['id']) ?>">
                        <p><strong>Booking ID:</strong> <?= htmlspecialchars($b['id']) ?></p>
                        <p><strong>Name:</strong> <?= htmlspecialchars($b['name']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($b['phone']) ?></p>
                        <p><strong>Route:</strong> <?= htmlspecialchars($b['route']) ?></p>
                        <p><strong>Date:</strong> <?= htmlspecialchars($b['date']) ?></p>
                        <p><strong>Time:</strong> <?= $formattedTime ?></p>
                        <p><strong>Seat No:</strong> <?= htmlspecialchars($b['seat_no'] ?? 'N/A') ?></p>
                        <p><strong>Fare:</strong> <?= htmlspecialchars($b['fare'] ?? 'N/A') ?></p>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
