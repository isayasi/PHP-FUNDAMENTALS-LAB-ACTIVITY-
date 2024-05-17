<!DOCTYPE html>
<html>
<head>
    <title>Canteen Order Management System</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF5EE;
            margin: 0;
            padding: 0;
        }

        /* Container styling */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header styling */
        .header {
            background-color: #FFDAB9;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Navbar styling */
        .navbar {
            background-color: #8B4513;
            overflow: hidden;
            padding: 10px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-right: 10px;
        }

        .navbar a:hover {
            background-color: #662D0F;
        }

        /* Form group styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"], .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group input[type="submit"] {
            background-color: #8B4513;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #662D0F;
        }

        /* Order summary styling */
        .order-summary {
            background-color: #FFDAB9;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .order-summary h2 {
            margin-bottom: 10px;
        }

        .order-summary p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header section -->
        <div class="header">
            <h1>Canteen Order Management System</h1>
        </div>

        <!-- Navbar section -->
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="index.php?action=register">Register</a>
            <a href="index.php?action=login">Login</a>
        </div>

        <?php
        // Database connection setup
        $conn = new mysqli('localhost', 'root', '', 'canteen_db');

        // Check if the connection is successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Create 'users' table if it doesn't exist
        $conn->query("CREATE TABLE IF NOT EXISTS users (
          username VARCHAR(50) NOT NULL,
          password VARCHAR(255) NOT NULL,
          PRIMARY KEY (username)
        )");

        session_start();

        // Handle registration
        if(isset($_POST['register'])) {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                echo "<p>Registration successful!</p>";
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }

        // Handle login
        if(isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($hashed_password);

            if ($stmt->num_rows > 0) {
                $stmt->fetch();
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['username'] = $username;
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<p>Invalid password!</p>";
                }
            } else {
                echo "<p>Invalid username!</p>";
            }

            $stmt->close();
        }

        // Handle canteen order
        if(isset($_POST['submit'])) {
            $food = $_POST['food'];
            $quantity = $_POST['quantity'];
            $cash = $_POST['cash'];

            $prices = [
                'Cheezy' => 39,
                'Clover' => 37,
                'Cracklings' => 25
            ];

            if (isset($prices[$food])) {
                $price = $prices[$food];
                $totalCost = $price * $quantity;
                $change = $cash - $totalCost;

                echo "<div class='order-summary'>";
                echo "<h2>Total Cost: P$totalCost</h2>";
                echo "<h2>Change: P$change</h2>";
                echo "<h2>Thank you for ordering!</h2>";
                echo "<h3>Order Details:</h3>";
                echo "$food x $quantity<br>";
                echo "</div>";
                exit();
            } else {
                echo "<h2>Invalid food selection!</h2>";
            }
        }

        // Handle logout
        if(isset($_GET['action']) && $_GET['action'] == 'logout') {
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit();
        }

        // Display forms based on user login status
        if(isset($_SESSION['username']))
        {
            echo "<p>Welcome, " . $_SESSION['username'] . "!</p>";
            echo '<a href="?action=logout">Logout</a>';
        ?>
            <!-- Order form -->
            <h1>Welcome to Canteen! Here are the prices:</h1>
            <form method="post" action="index.php">
                <div class="form-group">
                    <input type="radio" name="food" value="Cheezy" required> Cheezy - P39<br>
                    <input type="radio" name="food" value="Clover" required> Clover - P37<br>
                    <input type="radio" name="food" value="Cracklings" required> Cracklings - P25<br><br>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="1" required>
                </div>
                <div class="form-group">
                    <label for="cash">Cash:</label>
                    <input type="number" id="cash" name="cash" min="0" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Submit">
                </div>
            </form>
        <?php
        } else {
            if(isset($_GET['action']) && $_GET['action'] == 'register') {
        ?>
            <!-- Registration form -->
            <h2>Register</h2>
            <form method="post" action="index.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                <input type="submit" name="register" value="Register">
            </form>
            <p>Already have an account? <a href="index.php">Login</a></p>
        <?php
            } else {
        ?>
        <!-- Login form -->
        <h2>Login</h2>
        <form method="post" action="index.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" name="login" value="Login">
        </form>
        <p>Don't have an account? <a href="index.php?action=register">Register</a></p>
        <?php
            }
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
