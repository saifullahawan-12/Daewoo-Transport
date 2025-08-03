<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $conn->query("DELETE FROM bookings WHERE id = $id");
    header("Location: admin_dashboard.php");
    exit;
}

// Filters
$routeFilter = $_GET['route'] ?? '';
$dateFilter = $_GET['date'] ?? '';
$timeFilterRaw = $_GET['time'] ?? '';

// Convert time to 24-hour format if provided
$timeFilter = '';
if (!empty($timeFilterRaw)) {
    $timeFilter = date("H:i:s", strtotime($timeFilterRaw));
}

// Query bookings
$query = "SELECT * FROM bookings WHERE 1=1";
if (!empty($routeFilter)) {
    $query .= " AND route = '" . $conn->real_escape_string($routeFilter) . "'";
}
if (!empty($dateFilter)) {
    $query .= " AND DATE(booked_at) = '" . $conn->real_escape_string($dateFilter) . "'";
}
if (!empty($timeFilter)) {
    $query .= " AND time = '" . $conn->real_escape_string($timeFilter) . "'";
}

$result = $conn->query($query);

// Query statistics
$statsResult = $conn->query("SELECT route, COUNT(*) AS total FROM bookings GROUP BY route");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Daewoo Transport</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7fa;
            padding: 30px;
            color: #001f3f;
        }
        h2 {
            color: #001f3f;
        }
        .filter-box, .stats-box {
            background: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1px solid #001f3f;
        }
        input, select, button, a {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #001f3f;
        }
        button {
            background-color: #001f3f;
            color: white;
            cursor: pointer;
        }
        .logout {
            float: right;
            background: #001f3f;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #001f3f;
            color: white;
        }
        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .delete-btn {
            background: #d90429;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>Admin Dashboard</h2>
<a class="logout" href="logout.php">Logout</a>

<div class="filter-box">
    <h3>🔍 Filter Bookings</h3>
    <form method="GET">
        <select name="route">
            <option value="">-- Select Route --</option>
            <?php
            $routes = [
                'Abbottabad to Lahore',
                'Lahore to Karachi',
                'Abbottabad to Multan',
                'Abbottabad to Peshawar'
            ];
            foreach ($routes as $route) {
                $selected = ($routeFilter == $route) ? 'selected' : '';
                echo "<option value=\"$route\" $selected>$route</option>";
            }
            ?>
        </select>
        <input type="date" name="date" value="<?= htmlspecialchars($dateFilter) ?>">
        <input type="time" name="time" value="<?= htmlspecialchars($timeFilterRaw) ?>">
        <button type="submit">Apply Filters</button>
        <a href="admin_dashboard.php">Reset</a>
    </form>
</div>

<div class="stats-box">
    <h3>📊 Bookings Statistics</h3>
    <ul>
        <?php while($stat = $statsResult->fetch_assoc()): ?>
            <li><strong><?= htmlspecialchars($stat['route']) ?>:</strong> <?= $stat['total'] ?> bookings</li>
        <?php endwhile; ?>
    </ul>
</div>

<h3>🧾 Booking List</h3>
<?php if ($result->num_rows > 0): ?>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Route</th>
        <th>Seat No</th>
        <th>Booking Date</th>
        <th>Time</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['route']) ?></td>
        <td><?= htmlspecialchars($row['seat_no'] ?? 'N/A') ?></td>
        <td><?= date('Y-m-d', strtotime($row['booked_at'])) ?></td>
        <td><?= date('h:i A', strtotime($row['time'])) ?></td>
        <td>
            <a class="delete-btn" href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p><strong>No bookings found for the selected criteria.</strong></p>
<?php endif; ?>

</body>
</html>
