<?php

require 'db.php';

$categ_id = $_GET["id"];


  try {
    $categed_bookmarks = $db->query("select user.id uid, bookmark.id bid, name, title, note, created, url
                            from bookmark, user
                            where user.id = bookmark.owner and user.id = {$_SESSION['user']['id']} and bookmark.categ = {$categ_id} ")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($categed_bookmarks);
      } catch (\Exception $e) {
    echo json_encode(["error"=>$e]);

  }




 ?>
