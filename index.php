<?php
require_once 'pdo.php';

session_start();
if (!isset($_SESSION["username"])) {

   echo '<script> alert("Please Log In First");</script>';
   header('refresh:0;url=login.html');
   return;
} else {
   if (isset($_SESSION["last_activity"])) {
      $timeout_seconds = 1800; // 30 minutes
      $inactive_seconds = time() - $_SESSION["last_activity"];
      if ($inactive_seconds > $timeout_seconds) {
         session_destroy();
         echo '<script> alert("Session timed out");</script>';
         echo '<script> window.location.href = "login.html"; </script>';
         return;
      }
   }

   $_SESSION["last_activity"] = time();
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>SedapGo Food Menu</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="styles.css">

   <script>
      // Start monitoring user activity
      var timeoutSeconds = 1800;
      var timeout = setTimeout(redirectLogout, timeoutSeconds * 1000);

      function redirectLogout() {
         alert("Session timed out. You will be redirected to the login page.");
         window.location.href = "login.html";
      }

      function resetTimeout() {
         clearTimeout(timeout);
         timeout = setTimeout(redirectLogout, timeoutSeconds * 1000);
      }

      // Attach event listeners to monitor user activity
      window.addEventListener("mousemove", resetTimeout);
      window.addEventListener("mousedown", resetTimeout);
      window.addEventListener("keydown", resetTimeout);
      window.addEventListener("scroll", resetTimeout);
      window.addEventListener("touchstart", resetTimeout);
   </script>
</head>

<body>

   <div class="overlay">
      <?php
      if (isset($_SESSION['message'])) {
         echo '<div class="message"><span>' . $_SESSION['message'] . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
         unset($_SESSION['message']);
      }


      ?>

      <?php require_once "header.php"; ?>

      <div class="container">

         <section class="products">

            <h1 class="heading">Food Menu</h1>

            <div class="box-container">

               <?php

               $stmt = $pdo->query("SELECT * FROM food");
               $food = $stmt->rowCount();
               if ($food > 0) {
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
               ?>

                     <form>
                        <div class="box">
                           <img src="img/<?php echo $row['image']; ?>" alt="">
                           <h3><?php echo $row['food_name']; ?></h3>
                           <div class="price">RM<?php echo $row['unit_price']; ?></div>
                           <?php
                           $rating = $row['rating'];
                           $count = floor($rating);
                           for ($i = 0; $i < $count; $i++) { ?>
                              <i class="fa fa-star fa-2x" style="color: orange"></i>
                           <?php
                           }
                           if ($row['rating'] !== 0 && $row['rating'] !== $count) { ?>
                              <i class="fa fa-star-half fa-2x" style="color: orange"></i>
                           <?php
                           }
                           ?>

                           <input type="hidden" name="food_name" value="<?php echo $row['food_name']; ?>">
                           <input type="hidden" name="unit_price" value="<?php echo $row['unit_price']; ?>">
                           <input type="hidden" name="image" value="<?php echo $row['image']; ?>">
                           <input type="hidden" name="food_id" value="<?php $row['food_id'] ?>">
                           </br>
                           <a href="edit.php?food_id=<?= $row['food_id'] ?>"><button class="edit-btn" type="button" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></button></a>
                           <a href="delete.php?food_id=<?= $row['food_id'] ?>"><button class="delete-btn " type="button" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></button></a>
                        </div>
                     </form>

               <?php
                  };
               };
               ?>

            </div>

         </section>

      </div>

      <!-- custom js file link  -->
      <script src="script.js"></script>
   </div>
</body>

</html>