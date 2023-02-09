<!DOCTYPE html>
<!-- Login form with logo-->
<html>

<head>
    <title>Log In</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>

<body>
    <div class="loginbox">
        <div>
            <img src="assets/logo.png" class="logo" width="100px">
            <h1>Log In or Register</h1>
        </div>
        <div>
            <form method="post" action="">
                <p>Username</p>
                <input type="text" name="username" placeholder="Enter Username">
                <p>Password</p>
                <input type="password" name="password" placeholder="Enter Password"> <br><br>

                <input type="reset" value="Reset">
                <input type="submit" name="submit" value="Validate">
                <input type="submit" name="submit" value="Add">
            </form>
        </div>
    </div>

</html>

<?php
session_start();
// limit connexion 
$_SESSION['count'] = 0;


if (isset($_REQUEST['submit'])) {
    // connection to the database
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'customers';

    $mysqli = @new mysqli(
        $db_host,
        $db_user,
        $db_password,
        $db_db
    );

    if ($mysqli->connect_error) {
        echo 'Errno: ' . $mysqli->connect_errno;
        echo '<br>';
        echo 'Error: ' . $mysqli->connect_error;
        exit();
    }
    switch ($_REQUEST["submit"]) {
        case "Validate":
            if (empty($_POST['username']) && empty($_POST['password'])) {
                echo 'Please correct the fields';
                return false;
            }

            // check if the user is blocked
            if (isset($_SESSION['count']) && $_SESSION['count'] >= 3) {
                $time = time() - $_SESSION['time'];
                if ($time < 120) {
                    echo "<p style='color:#FF0000'>You are blocked for 3 minutes</p>";
                    return false;
                } else {
                    $_SESSION['count'] = 0;
                }
            }

            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            // sql with prepared statements
            $sql = "SELECT * FROM customers WHERE username = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $hashedPassword = $row['password'];
                if (password_verify($password, $hashedPassword)) {
                    echo "<p style='color:#00FF00'>Login successful</p>";
                    $_SESSION['count'] = 0;
                    $_SESSION['time'] = time();
                } else {
                    echo "<p style='color:#FF0000'>Your password is incorrect</p>";
                    if (isset($_SESSION['count'])) {
                        $_SESSION['count'] += 1;
                        $_SESSION['time'] = time();

                    }
                }
            } else {
                echo "<p style='color:#FF0000'>Login failed</p>";
                if (isset($_SESSION['count'])) {
                    $_SESSION['count'] += 1;
                    $_SESSION['time'] = time();

                }
            }
            $stmt->close();

            break;
        case "Add":
            if (empty($_POST['username']) && empty($_POST['password'])) {
                echo 'Please correct the fields';
                return false;
            }
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO customers (username, password) VALUES (?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ss", $username, $hashedPassword);
            if ($stmt->execute()) {
                echo "<p style='color:#00FF00'>User added</p>";
            } else {
                echo "<p style='color:#FF0000'>User not added</p>";
            }
            $stmt->close();
            break;
        default:
            echo "Default";
            break;

    }
    // close connection
    $mysqli->close();
}
?>