<?php
//var_dump($_POST);
if ( $_SERVER["REQUEST_METHOD"] == "POST") {
    require "db.php" ;
    extract($_POST) ;

    $sql = "update bookmark set title=?,note=?,url=? where id = ?" ;
    try{
      $stmt = $db->prepare($sql) ;
      $stmt->execute([$title,$note,$url,$id]) ;
      addMessage("Success") ;
      header("Location: ?page=main");
      exit ;
    }catch(PDOException $ex) {
     addMessage("Insert Failed!") ;

    }
}
header("Location: ?page=main");
exit ;
