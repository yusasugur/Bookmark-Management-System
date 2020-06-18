<?php

if( $_SERVER["REQUEST_METHOD"] == "POST") {
  require "db.php" ;
  require "file_upload.php";
  try {
       $id =  $_SESSION["user"]["id"];
       $name = $_POST["fullname"] ;
       $email = $_POST["email"] ;
       $password = $_POST["password"];
       $hash_password = password_hash($password, PASSWORD_DEFAULT) ;
       $_SESSION["user"]["profile"]=$profile;

       $sql = "update user set name=?, email=?,password=?,profile=? where id = ?" ;
       $stmt = $db->prepare($sql) ;
       $stmt->execute([$name, $email,$hash_password,$profile, $id]) ;
       $_SESSION["user"]["name"]=$name;
       header("Location: ?page=main");
       exit ;

  } catch(PDOException $ex) {
     $msg = "Fail" ;
  }

}
