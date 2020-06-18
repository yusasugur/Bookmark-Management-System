<?php
  var_dump($_POST) ;
  var_dump($_FILES) ;
  extract($_POST);


  $filename = $_FILES["profile"]["name"];
  $bytes = $_FILES["profile"]["size"];
  $tmp_file = $_FILES["profile"]["tmp_name"];


  $profile = sha1("ctis2020".uniqid())."_".$filename;
  echo $tmp_file,$profile;
    if(move_uploaded_file($tmp_file , "upload/$profile" )){
      $success = true;
    }
    else {
      header("Location: ?page=main");

    }






 ?>
