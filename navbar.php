<?php
  $register_link = ["home", "loginForm"] ;
  $login_link = ["home", "registerForm"] ;
  $main_link = ["main"];
  require 'db.php';

  if (isset($_SESSION["user"])) {
    $photo = $db->query("select profile from user where id = {$_SESSION['user']['id']}")->fetchAll(PDO::FETCH_ASSOC) ;
  }

?>
<style media="screen">
  .circle{
    width: 40px;height: 40px;

  }
</style>
<nav>
    <div class="nav-wrapper">
      <a href="?" class="brand-logo"><i class="material-icons left hide-on-med-and-down">home</i>BMS</a>
      <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>

      <!-- Menu Items -->
      <?php
      $menu_items = [
        "desktop" => '<ul id="nav-mobile" class="right hide-on-med-and-down">',
        "mobile" => '<ul class="sidenav" id="mobile-demo">'
      ];
      ?>

      <?php foreach($menu_items as $type => $menu)  : ?>
          <?= $menu ?>
          <?php if ( $type == "mobile") : ?>
            <li class="red-text" style="margin-left: 3em; margin-top:2em">BMS v1.0</li>
            <li class="divider"></li>
          <?php endif ?>
          <?php if ( in_array($page, $register_link)) : ?>
            <li>
                <a href="?page=registerForm"><i class="material-icons left">person_add</i>Register</a>
            </li>
          <?php endif ?>

          <?php if ( in_array($page, $login_link)) : ?>
            <li>
                  <a href="?page=loginForm"><i class="material-icons left">directions_run</i>Sign in</a>
            </li>
          <?php endif ?>

          <?php if ( in_array($page, $main_link)) : ?>
            <li>
              <a class="resetNotif"><i class='material-icons left'>notifications</i> <span id="bms-notif"></a> </a>

            </li>
            <li>
              <img src="upload/<?= $photo[0]["profile"]; ?>" class="circle">

            </li>
            <li>

                <a href="?page=profileForm">

                  <?= $_SESSION["user"]["name"] ?></a>
            </li>
            <li>
                <a href="?page=logout"><i class="material-icons left">exit_to_app</i>Logout</a>
            </li>
          <?php endif ?>
      </ul>
      <?php endforeach ?>
    </div>
  </nav>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
  });

  $.get("index.php",
        {"page":"getNotification"},
        function (data) {
          console.log("data");
          $("#bms-notif").text(data.message);

        },
        "json"
        )


        $(".resetNotif").click(function(e){
           e.preventDefault();
           $("#bms-notif").text(0);
           
           $.get("index.php",
                 {"page":"resetNotif"},
                 function (data) {
                   console.log("data");

                 },
                 "json"
                 )


        });





    setInterval(function functionName() {
          $.get("index.php",
                {"page":"getNotification"},
                function (data) {
                  console.log(data.message);
                  $("#bms-notif").text(data.message);

                },
                "json"
                )
        },5000);



  </script>
