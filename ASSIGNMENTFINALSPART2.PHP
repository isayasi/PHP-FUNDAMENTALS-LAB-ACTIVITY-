<!DOCTYPE html>
<html>
<head>
    <title>Canteen Order Management System</title>
</head>
<body>
    <?php
    if(isset($_POST['submit'])) {
        $food = $_POST['food'];
        $quantity = $_POST['quantity'];
        $cash = $_POST['cash'];

        // Adjusted calculation based on selected food item's price
        $prices = [
            'Cheezy' => 39,
            'Clover' => 37,
            'Cracklings' => 25
        ];

        if (isset($prices[$food])) {
            $price = $prices[$food];
            $totalCost = $price * $quantity;
            $change = $cash - $totalCost;

            echo "<h2>Total Cost: P$totalCost</h2>";
            echo "<h2>Change: P$change</h2>";
            echo "<h2>Thank you for ordering!</h2>";
            echo "<h3>Order Details:</h3>";
            echo "$food x $quantity<br>";
        } else {
            echo "<h2>Invalid food selection!</h2>";
        }
    } else {
    ?>
        <h1>Welcome to Canteen! Here are the prices:</h1>
        <form method="post">
            <input type="radio" name="food" value="Cheezy"> Cheezy - P39<br>
            <input type="radio" name="food" value="Clover"> Clover - P37<br>
            <input type="radio" name="food" value="Cracklings"> Cracklings - P25<br><br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" required><br><br>
            <label for="cash">Cash:</label>
            <input type="number" id="cash" name="cash" min="0" required><br><br>
            <input type="submit" name="submit" value="Submit">
        </form>
    <?php
    }
    ?>
</body>
</html>
