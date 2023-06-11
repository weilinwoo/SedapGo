<?php
require_once "pdo.php";
session_start();

if(isset($_POST['edit_food'])){
  $f_name = $_POST['f_name'];
  $f_price = $_POST['f_price'];
  $id = $_POST['f_id'];
  $imgFile = $_FILES['image']['name'];
  $imgdir = $_FILES['image']['tmp_name'];

if ($imgFile) {
move_uploaded_file($imgdir,"img/" . $imgFile);
$image = $imgFile;
}

else {
  $image = $row['image'];
}
  $update_stmt = $pdo->prepare("UPDATE food SET food_name = :name, unit_price = :price, image = :image WHERE food_id =:food_id");
  $update_stmt->execute(array(
              ':name' => $f_name,
              ':price' => $f_price,
              ':image' => $image,
              ':food_id' => $id));

  if ($update_stmt) {
    $_SESSION['message'] = 'Food item updated successfully ';
    header("Location: index.php");
  }else{
    $_SESSION['message'] = 'Food item fail to update';
    }
  }

if (!isset($_GET['food_id']))  {
  echo '<script> alert("Please login");</script>';
  header('refresh:0;url=login.html');
  return;
}else{
  $stmt = $pdo->prepare("SELECT * FROM food where food_id =:food_id");
  $stmt -> execute(array(":food_id" =>$_GET['food_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $f = htmlentities($row['food_name']);
  $p = htmlentities($row['unit_price']);
  $i = $row['image'];
  $id = $row['food_id'];
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Food Item</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="styles.css">

</head>
<body>
  <body>
  <?php
  if(isset($_SESSION["message"])){
     echo '<div class="message"><span>'.$_SESSION["message"].'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
     unset($_SESSION["message"]);
  }
  ?>

  <?php include 'header.php'; ?>
  <div class="container">

  <section>

    <form  method="post" class="add-product-form" enctype="multipart/form-data">
      <h3>Edit food item</h3>

      <input type="text" name="f_name" value="<?= $f ?>" class="box" required minlength="3" maxlength = "50">
      <input type="number" name="f_price" step="0.1" min="0" value="<?= $p?>" class="box" required>
      <img src="img/<?= $i ?>" class="box" alt="">
      <input type="file" name="image" accept="image/png, image/jpg, image/jpeg" class="box">
      <input type="hidden" name="f_id" value="<?= $id ?>">
      <input type="submit" value="Edit" name="edit_food" class="btn">
      <a href="index.php">
        <input class="cancel-btn" type=button value="Cancel"/>
      </a>

    </form>

  </section>
</div>
  <!-- custom js file link  -->
  <script src="script.js"></script>

</body>
</html>
