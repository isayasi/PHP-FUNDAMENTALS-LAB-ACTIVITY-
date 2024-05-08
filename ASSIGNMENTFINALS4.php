<!DOCTYPE html>
<html>
<head>
    <title>Quadratic Equation Discriminant Calculator</title>
</head>
<body>
    <h1>Quadratic Equation Discriminant Calculator</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Enter the value of a: <input type="text" name="a"><br>
        Enter the value of b: <input type="text" name="b"><br>
        Enter the value of c: <input type="text" name="c"><br>
        <input type="submit" value="Calculate">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = $_POST['a'];
        $b = $_POST['b'];
        $c = $_POST['c'];

        $discriminant = $b*$b - 4*$a*$c;
        echo "The discriminant is: " . $discriminant;
    }
    ?>
</body>
</html>
