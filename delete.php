<?php
require_once "pdo.php";
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

if (isset($_POST['deletefood']) && isset($_POST['food_id'])) {
  $sql = "DELETE FROM food WHERE food_id = :food_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':food_id' => $_POST['food_id']));
  $_SESSION['message'] = 'Food Item Deleted Successfully.';
  header("Location: index.php");
  return;
}

if (!isset($_GET['food_id'])) {
  echo '<script> alert("Please login");</script>';
  header('refresh:0;url=login.html');
  return;
} else {
  $stmt = $pdo->prepare("SELECT food_name, food_id FROM food where food_id = :food_id");
  $stmt->execute(array(":food_id" => $_GET['food_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
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

<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="exampleModalLabel">Delete Food Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location='index.php'">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="delete.php" method="post">
        <div class="modal-body">
          <input type="hidden" name="delete_id" id="delete_id">

          <h5> Are you sure to delete <?= htmlentities($row['food_name']) ?> ?</h5>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="food_id" value="<?= $row['food_id'] ?>">
          <button type="submit" name="deletefood" class="btn btn-danger"> Yes </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:window.location='index.php'"> No </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#deletemodal").modal('show');
  });
</script>