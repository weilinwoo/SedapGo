<?php
require_once("pdo.php");
$pw="sedapgo666";
$name="admin";
$salt = 'XyZzy12*_';
$password_hash = hash ('md5', $salt.$pw);
$stmt=$pdo->prepare("INSERT INTO user (username,password) VALUES(:name,:password_hash)");
$stmt->execute(array(":name" => $name, ":password_hash" => $password_hash));
if ($stmt){
  echo "Admin is added successfully.";
}else{
  echo "Admin is not added.";
}
?>
