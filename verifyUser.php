<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' fonts.googleapis.com; img-src 'self'; font-src fonts.gstatic.com; form-action 'self'; frame-ancestors 'self';");
require_once("pdo.php");

session_start();

if (!isset($_POST['username']) || !isset($_POST['password'])) {
  die('Please Log In');
}

$username = htmlentities($_POST["username"]);
$password = htmlentities(trim($_POST["password"]));
$salt = 'XyZzy12*_';
$password_hash = hash('md5', $salt . $password);

$stmt = $pdo->prepare("SELECT username, password FROM user WHERE username=:username");
$stmt->execute(array(":username" => $username));
$result = $stmt->rowCount();

if ($result === 0) {
    echo '<script> alert("Username Not Exists");</script>';
    header('refresh:0;url=login.html');
} else {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($password_hash === $row['password']) {
            // Generate and store a new anti-CSRF token for each successful login
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            // Redirect the user to the desired page
            echo '<script> alert("Login Successful");</script>';
            header('refresh:0;url=index.php');
        } else {
            echo '<script> alert("Incorrect Password");</script>';
            header('refresh:0;url=login.html');
        }
    }
}
?>