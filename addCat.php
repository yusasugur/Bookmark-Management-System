<?php
require "db.php" ;
//var_dump($_POST);
if ( $_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST) ;
    $sql = "insert into category (title) values (?)" ;
    try{
      $stmt = $db->prepare($sql) ;
      $stmt->execute([$name]) ;
      addMessage("Success") ;
    }catch(PDOException $ex) {
       addMessage($ex);
    }
}

header("Location: index.php?page=main") ;
