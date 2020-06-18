<?php
  require 'db.php';

  $ids=$_POST['userId'];
  foreach ($ids as $id) {
    $q = "Select message from notif where user_id=?";
    $stmt = $db->prepare($q);
    $stmt->execute([$id]);
    $totalMessage = $stmt->fetch();
    $t=$totalMessage["message"];
    $t++;

    $q = "Update notif Set message=? Where user_id=? ";
    $stmt = $db->prepare($q);
    $stmt->execute([$t,$id]);

  }
  header("Location: ?page=main");
  exit ;




 ?>
