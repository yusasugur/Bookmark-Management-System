<?php
  require 'db.php';

  $id = $_SESSION['user']['id'];


    $q = "Update notif Set message=? Where user_id=? ";
    $stmt = $db->prepare($q);
    $stmt->execute([0,$id]);






 ?>
