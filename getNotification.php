<?php
  require 'db.php';

  $id = $_SESSION['user']['id'];


        $q = "select message from notif where user_id=?";
        $stmt = $db->prepare($q);
        $stmt->execute([$id]);
        if ($stmt->rowCount()>0) {
          $notification = $stmt->fetch(PDO::FETCH_ASSOC);
          echo json_encode($notification);
        }else {
          echo json_encode(["error"=>"id not found"]);
        }




 ?>
