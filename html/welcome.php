<?php
// Initialize the session
session_start();

$servername = 'my-db-instance.cdcsjr2hivxu.us-east-2.rds.amazonaws.com';
$username = 'username';
$password = 'password';
$dbname = 'users';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$grocery = "";
$username = $_SESSION['username'];
$user_sql = "SELECT id FROM users WHERE username = '$username'";
$result = $conn->query($user_sql);
while ($row = mysqli_fetch_assoc($result)) {
    $personid = $row["id"];
}

$final_sql = "SELECT ItemName from grocery_list where id ='$personid'";
$final_result = $conn->query($final_sql);

if (isset($_POST['SubmitButton'])) {
    $grocery = $_POST["grocery"];

// Processing form data when form is submitted

// ensures item is added

    if (empty($grocery)) {
        echo "Please enter a grocery.";
    } else {


        $add_sql = "INSERT INTO grocery_list(ItemName, id) VALUES('$grocery', '$personid')";
        if ($conn->query($add_sql) === true) {
            echo "Added item.";
        } else {
            echo "Something went wrong.";
        }

    }

}


if (isset($_POST['SubmitRemove'])) {
    $grocerytoremove = $_POST["grocerytoremove"];

// Processing form data when form is submitted

// ensures item is removed

    if (empty($grocerytoremove)) {
        echo "Please enter a grocery to remove.";
    } else {

        $del_sql = "DELETE FROM grocery_list WHERE ItemName = '$grocerytoremove' and id = '$personid'";
        if ($conn->query($del_sql) === true) {
            echo "Removed item.";
        } else {
            echo "Something went wrong.";
        }

    }

}

$string = "";
$newline = "<br>";
$final_result = $conn->query($final_sql);
while ($items = mysqli_fetch_assoc($final_result)) {
    $string = $string . $newline . $newline . $items["ItemName"];
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="page-header">
    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Here is your grocery list.</h1>
</div>
<form action="welcome.php" method="post">
    Add Grocery: <input type="text" name="grocery">
    <input type="submit" name="SubmitButton">
</form>
<form action="welcome.php" method="post">
    Remove Grocery: <input type="text" name="grocerytoremove">
    <input type="submit" name="SubmitRemove">
</form>
<body>
<?php echo $string; ?>
<br>
</body>
<p>
    <br>
    <br>
    <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
</p>
</body>
</html>


