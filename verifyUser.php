<?php
require_once("pdo.php");

session_start();

if ( isset($_POST["username"]) && isset($_POST["password"])){
  unset($_SESSION["username"]);
  $username=htmlentities($_POST["username"]);
  $password=htmlentities(trim($_POST["password"]));
  $salt = 'XyZzy12*_';
  $password_hash = hash ('md5', $salt.$password);

  $stmt=$pdo->prepare("SELECT username,password FROM user WHERE username=:username");
  $stmt->execute(array(":username" => $username));
  $result=$stmt->rowCount();
  if($result===0){
      echo '<script> alert("Username Not Exists");</script>';
      header('refresh:0;url=login.html');
  }
  else{
    while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         if ($password_hash===$row['password']){
                    $_SESSION["username"] = $username;
                     echo '<script> alert("Login Successful");</script>';
                    //header('refresh:0;url=index.php');
                    header("Location: index.php?name=".urlencode($_POST['username']));
                    return;
         }
         else
         {
               echo '<script> alert("Incorrect Password");</script>';
               header('refresh:0;url=login.html');
               return;
         }
    }
  }
}

?>
