<?php

  require "db.php" ;

  $id = $_GET["id"] ?? "";

  try {
    $stmt = $db->prepare("delete from category where id=:id");
    $stmt->execute(["id"=>$id]);
    if ($stmt->rowCount()>0) {
      echo json_encode(["status" => "ok","message" => "$id category deleted"]);
    }
    else {
      echo json_encode(["status" => "error","message" => "$id is not valid"]);
    }
  } catch (\Exception $e) {
    echo json_encode(["status" => "error","message" => "Query syntax error"]);

  }
