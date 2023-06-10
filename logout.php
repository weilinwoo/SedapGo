<?php
  session_start();
  session_destroy();
  echo '<script> alert("Logout Successfully");</script>';
  header("Location: login.html");


?>
