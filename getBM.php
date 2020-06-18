<?php
  require 'db.php';




  $id = $_GET["id"];

  if (filter_var($id,FILTER_VALIDATE_INT) === FALSE) {
    echo json_encode(["error"=>"invalid"]);
  }else {
    try {
        $q = "select * from bookmark where id=?";
        $stmt = $db->prepare($q);
        $stmt->execute([$id]);
        if ($stmt->rowCount()>0) {
          $bm = $stmt->fetch(PDO::FETCH_ASSOC);
          echo json_encode($bm);
        }else {
          echo json_encode(["error"=>"id not found"]);
        }
    } catch (\Exception $e) {
      echo json_encode(["error"=>"select query error"]);

    }


  }

 ?>
