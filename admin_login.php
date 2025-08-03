<?php
session_start();
include 'db_connect.php';

$message = '';
$welcomeText = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if ($password === $admin['password']) {
            $_SESSION['admin_username'] = $username;
$welcomeText = "Welcome Admin  Redirecting to dashboard...";
echo "<meta http-equiv='refresh' content='2;url=admin_dashboard.php'>";

        } else {
            $message = "<div class='alert error'>❌ Incorrect password</div>";
        }
    } else {
        $message = "<div class='alert error'>❌ Admin not found</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Daewoo Transport</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --navy: #001f3f;
            --white: #ffffff;
            --gray: #f0f0f0;
            --light-navy: #12375d;
            --highlight: #0077b6;
            --error: #dc3545;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: var(--navy);
            color: var(--navy);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: var(--white);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
        }

        h2 {
            text-align: center;
            color: var(--navy);
            margin-bottom: 10px;
            font-size: 26px;
        }

        .welcome {
            text-align: center;
            color: var(--highlight);
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 18px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--highlight);
        }

        button {
            width: 100%;
            padding: 14px;
            background: var(--navy);
            color: var(--white);
            font-size: 17px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: var(--light-navy);
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .error {
            background: #f8d7da;
            color: var(--error);
            border: 1px solid #f5c2c7;
        }

        .back-button {
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            color: var(--highlight);
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-button a:hover {
            color: var(--light-navy);
        }

        @media (max-width: 500px) {
            .login-box {
                padding: 30px 20px;
            }

            h2 {
                font-size: 22px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Admin Login</h2>
    <?php if ($welcomeText): ?>
        <div class="welcome"><?= $welcomeText ?></div>
    <?php endif; ?>
    <?= $message ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter username" required>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit">Login</button>
    </form>
    <div class="back-button">
        <a href="index.php">🔙 Back to Home</a>
    </div>
</div>

</body>
</html>
