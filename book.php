<?php
include 'db_connect.php';

$message = '';
$availableSeatsMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $route = isset($_POST['route']) ? $_POST['route'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';

    if (empty($name) || empty($phone) || empty($route) || empty($time) || empty($date)) {
        $message = "<div class='alert error'>❌ All fields are required</div>";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $message = "<div class='alert error'>❌ Name must contain only alphabets and spaces</div>";
    } elseif (!preg_match('/^\d{11}$/', $phone)) {
        $message = "<div class='alert error'>❌ Phone number must be exactly 11 digits</div>";
    } elseif ($date < date('Y-m-d')) {
        $message = "<div class='alert error'>❌ Travel date cannot be in the past</div>";
    } else {
        $time_24hr = DateTime::createFromFormat('g:i A', $time)->format('H:i:s');

        $fare = 0.00;
        $stmt = $conn->prepare("SELECT fare FROM routes WHERE route_name = ?");
        $stmt->bind_param("s", $route);
        $stmt->execute();
        $stmt->bind_result($fetched_fare);
        if ($stmt->fetch()) {
            $fare = $fetched_fare;
        }
        $stmt->close();

        $bookedSeats = [];
        $stmt = $conn->prepare("SELECT seat_no FROM bookings WHERE route = ? AND time = ? AND date = ?");
        $stmt->bind_param("sss", $route, $time_24hr, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $bookedSeats[] = (int)$row['seat_no'];
        }
        $stmt->close();

        $seat_no = null;
        for ($i = 1; $i <= 40; $i++) {
            if (!in_array($i, $bookedSeats)) {
                $seat_no = $i;
                break;
            }
        }

        if ($seat_no === null) {
            $message = "<div class='alert error'>❌ All 40 seats are booked for this route/time/date.</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO bookings (name, phone, route, time, date, fare, seat_no) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssd", $name, $phone, $route, $time_24hr, $date, $fare, $seat_no);
            if ($stmt->execute()) {
                $message = "<div class='alert success'>✅ Booking confirmed for $date! Seat No: $seat_no. Fare: Rs. $fare. Confirmation sent to $phone</div>";
            } else {
                $message = "<div class='alert error'>❌ Error booking ticket. Please try again.</div>";
            }
            $stmt->close();
        }
    }

    if (!empty($route) && !empty($time) && !empty($date)) {
        $time_24hr = DateTime::createFromFormat('g:i A', $time)->format('H:i:s');
        $stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE route = ? AND time = ? AND date = ?");
        $stmt->bind_param("sss", $route, $time_24hr, $date);
        $stmt->execute();
        $stmt->bind_result($bookedCount);
        $stmt->fetch();
        $stmt->close();
        $available = 40 - $bookedCount;
        $availableSeatsMessage = "<div class='seat-info'>🪑 $available of 40 seats available for selected time</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book a Ticket - Daewoo Bus</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #001f3f;
            margin: 0;
            padding: 30px 0;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            min-height: 100vh;
        }

        .booking-form {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #001f3f;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #001f3f;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            background: #001f3f;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: #003366;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .seat-info {
            background: #e0f7fa;
            color: #004d40;
            padding: 10px;
            border-radius: 8px;
            margin-top: -10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .payment-note {
            margin-top: 20px;
            text-align: center;
            font-size: 15px;
            color: #333;
            background: #f1f1f1;
            padding: 12px;
            border-radius: 8px;
            border-left: 5px solid #001f3f;
        }

        .back-container {
            text-align: center;
            margin-top: 20px;
        }

        .back-btn {
            background-color: #0077b6;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .back-btn:hover {
            background-color: #005f8f;
        }
    </style>
</head>
<body>

<div class="booking-form">
    <h2>🎫 Book Your Daewoo Ticket</h2>
    <?= $message ?>
    <form method="POST">
        <label for="name">Full Name</label>
        <input type="text" name="name" placeholder="Enter your name" required pattern="[A-Za-z ]+" title="Name must contain only alphabets and spaces">

        <label for="phone">Phone Number (11-digit)</label>
        <input type="text" name="phone" placeholder="e.g. 03001234567" required pattern="\d{11}" title="Enter 11-digit phone number">

        <label for="route">Select Route</label>
        <select name="route" required>
            <option value="">-- Choose a Route --</option>
            <option value="Abbottabad to Lahore">Abbottabad to Lahore</option>
            <option value="Lahore to Karachi">Lahore to Karachi</option>
            <option value="Abbottabad to Peshawar">Abbottabad to Peshawar</option>
            <option value="Abbottabad to Multan">Abbottabad to Multan</option>
        </select>

        <label for="time">Select Time</label>
        <select name="time" required>
            <option value="">-- Choose Time --</option>
            <option value="8:00 AM">8:00 AM</option>
            <option value="12:00 PM">12:00 PM</option>
            <option value="4:00 PM">4:00 PM</option>
        </select>

        <label for="date">Travel Date</label>
        <input type="date" name="date" required min="<?= date('Y-m-d') ?>">

        <button type="submit">🚍 Book Now</button>
    </form>

    <?= $availableSeatsMessage ?>

    <div class="payment-note">
        💵 <strong>Note:</strong> Payment will be made at the terminal upon arrival.
    </div>

    <div class="back-container">
        <a class="back-btn" href="index.php">⬅ Back to Menu</a>
    </div>
</div>

</body>
</html>
