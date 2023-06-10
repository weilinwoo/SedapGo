<?php
  require_once "pdo.php";

?>

<header class="header">

   <div class="flex">

      <img src="img/SedapGO.png" style = "height:62px; width:130px" class="logo" alt="SedapGo"/>

      <form id="myform">
      <nav class="navbar">
         <a href="index.php">Home</a>
         <input type="hidden" name="username" >
         <a href="insert.php" onclick="document.getElementById('myform').submit()" value="<?php echo $_SESSION['username'] ?>">Add Food</a>
         <a href="logout.php">Logout <i class="fa fa-sign-out" aria-hidden="true"></i></a>
      </nav>
    </form>
      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>
