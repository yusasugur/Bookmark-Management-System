<?php
require "db.php" ;

if ( $_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST) ;
    $sql = "insert into bookmark (title, url, note, owner,categ) values (?,?,?,?,?)" ;
    try{
      $stmt = $db->prepare($sql) ;
      $stmt->execute([$title, $url, $note, $owner,$categ ?? ""]) ;
      addMessage("Success") ;
    }catch(PDOException $ex) {
       addMessage("Fail") ;
    }
}

header("Location: index.php?page=main") ;
