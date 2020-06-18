
<?php
require "db.php";
$id =  $_SESSION["user"]["id"];

try {
   $stmt = $db->prepare("select * from user where id = :id") ;
   $stmt->execute(["id" => $id]);
   $user = $stmt->fetch(PDO::FETCH_ASSOC);
   extract($user) ;
} catch( PDOException $ex) {
  echo $ex;
}
?>
<style media="screen">
  .circle{
    width: 160px;height: 160px;
  }
</style>
<div class="container">
  <h1 class="center">Edit User</h1>
  <h2 class="center">
    <img src="upload/<?= $user["profile"]; ?>" class="circle">

  </h2>

  <div class="row">
    <form class="col s12" action="?page=profile" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="input-field col s6">
          <input placeholder="Placeholder" id="full_name" type="text" class="validate" value="<?= $name ?>" name="fullname">
          <label for="full_name">Full Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="email" type="email" class="validate" value="<?= $email ?>" name="email">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="input-field">
  <input placeholder="Password" id="user_pass" type="text" name="password">
  <label for="user_pass">Password</label>
</div>


      <div class="file-field input-field">
        <div class="btn">
          <span>File</span>
          <input type="file" name="profile">
        </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text">
        </div>
      </div>


      <!-- <div class="file-field input-field">
        <div class="btn">
          <span>Edit Profile Phote</span>
          <input type="file">
        </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text">
        </div>
      </div> -->
      <button class="btn waves-effect waves-light" type="submit" name="action">Submit
        <i class="material-icons right">send</i>
      </button>
    </form>
  </div>
</div>
