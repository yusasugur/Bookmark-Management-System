<?php

   require "db.php" ;

    // To remember sort between pages.
    // You can use the same technique for page numbers in pagination.
    //$sort = $_GET["sort"] ?? "created" ;
   if ( !isset($_GET["sort"])) {
        $sort = $_SESSION["sort"] ?? "title" ;
    } else {
        $sort = $_GET["sort"] ;
        $_SESSION["sort"] = $sort ;

    }

    $users = $db->query("select * from user order by name")->fetchAll(PDO::FETCH_ASSOC) ;

    $bookmarks = $db->query("select user.id uid, bookmark.id bid, name, title, note, created, url,categ
                                  from bookmark, user
                                  where user.id = bookmark.owner and user.id = {$_SESSION['user']['id']}
                                  order by $sort desc")->fetchAll(PDO::FETCH_ASSOC) ;

    $categories = $db->query("select * from category")->fetchAll(PDO::FETCH_ASSOC) ;



    $which_categ = $_GET["categ"] ?? "all";




?>
  <div class="row">
<div class="col s3">
      <div class="collection">
        <a href="?categ=0" class="bms-categ collection-item  <?php if($which_categ==0) echo "active"; ?> ">all</a>

        <?php foreach ($categories as $cat ): ?>
          <a id="cat<?php echo $cat["id"]; ?>" class="bms-categ collection-item <?php if($which_categ==$cat["id"]) echo "active"; ?> " href="?categ=<?php echo $cat["id"]; ?>"><?php echo $cat["title"]; ?></a>
        <?php endforeach; ?>

      </div>
      <a class="categ-add modal-trigger" href="#test"><i class="material-icons">add</i></a>
      <a class="categ-del" href="<?php echo $which_categ; ?>"><i class="material-icons">delete</i></a>


</div>
<div class="col s9">
<!-- Floating button at the bottom right -->

<div class="container">

    <form action="?page=searchForm&no=1" class="col s12"  method="post"  >
      <div class="row">
        <div class="input-field col s6 ">
          <i class="material-icons prefix">search</i>
          <textarea name="search" id="icon_prefix2" class="materialize-textarea"></textarea>
          <label for="icon_prefix2">Search</label>

        </div>

      <div class="send-bm modal-footer">
        <button  class="btn waves-effect waves-light" type="submit" name="action">Search
         <i class="material-icons right">send</i>
      </button>
    </div>
      </div>
    </form>
  </div>

<div class="fixed-action-btn">
  <a class="btn-floating btn-large red modal-trigger z-depth-2" href="#add_form">
    <i class="large material-icons">add</i>
  </a>
</div>

<!-- Main Table for all bookmarks -->
<div>
    <table class="striped"  id="main-table">
     <tr style="height:60px" class="grey lighten-5">
         <th class="title">
             <a href="?sort=title">Title
             <?= $sort == "title" ? "<i class='material-icons'>arrow_drop_down</i>": "" ?>
             </a>
        </th>
         <th class="note">
             <a href="?sort=note">Note
             <?= $sort == "note" ? "<i class='material-icons'>arrow_drop_down</i>": "" ?>
             </a>
        </th>
         <th class="created hide-on-med-and-down">
             <a href="?sort=created">Date
             <?= $sort == "created" ? "<i class='material-icons'>arrow_drop_down</i>": "" ?>
             </a>
        </th>
         <th class="action">Actions</th>
     </tr>

     <?php foreach($bookmarks as $bm)  : ?>
        <?php if ($bm["categ"]==$which_categ || $which_categ==0): ?>

         <tr id="row<?= $bm["bid"] ?>" class="cat<?php echo $bm["categ"]; ?>">
             <td><span class="truncate"><a href="<?= $bm['url'] ?>"><?= $bm['title'] ?></a></span></td>
             <td><span class="truncate"><?= $bm['note'] ?></span></td>
             <td class="created hide-on-med-and-down"><?php
                $date = new DateTime($bm['created']);
                echo $date->format("d M y");
               ?>
              </td>
              <td class="action">
                 <a href="<?= $bm["bid"] ?>" class="bms-delete btn-small"><i class="material-icons">delete</i></a>
                 <a class="bms-view btn-small" href="<?= $bm['bid'] ?>"><i class="material-icons">visibility</i></a>


                  <a class="bms-edit btn-small modal-trigger " href="#edit_form<?= $bm["bid"] ?>">
                    <i class="material-icons">edit</i>
                  </a>
                  <a class="bms-edit btn-small modal-trigger " href="#share_form">
                    <i class="material-icons">share</i>
                  </a>

                  <div id="edit_form<?= $bm["bid"] ?>" class="modal">
                    <form action="?page=editBM" method="post" >
                      <div class="modal-content">
                          <h5 class="center">Edit Bookmark</h5>
                          <input type="hidden" name="id" value="<?= $bm["bid"] ?>">
                          <div class="input-field">
                            <input id="title" type="text" name="title" value="<?= $bm['title'] ?>" >
                            <label for="title">Title</label>
                          </div>
                          <div class="input-field">
                              <input id="url" type="text" name="url" value="<?= $bm['url'] ?>">
                              <label for="url">URL</label>
                          </div>
                          <div class="input-field">
                            <textarea id="note" class="materialize-textarea" name="note" ><?= $bm['note'] ?> </textarea>
                            <label for="note">Notes</label>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button  class="btn waves-effect waves-light" type="submit" name="action">Edit
                           <i class="material-icons right">send</i>
                        </button>
                      </div>
                    </form>
                  </div>
              </td>

         </tr>
       <?php endif; ?>

       <?php endforeach ?>


    </table>

</div>

<div class="center hide" id="loader">
  <div class="preloader-wrapper small active">
      <div class="spinner-layer spinner-green-only">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
    </div>
</div>

</div>
</div>

<!-- All modal bookmarks in detail to show after clicking view buttons -->

    <div id="bm-detail" class="modal">
    <div class="modal-content">
      <table class="striped">
          <tr>
              <td>Title:</td>
              <td id="detail-title" ></td>
          </tr>
          <tr>
              <td>Notes:</td>
              <td id="detail-note"></td>
          </tr>
          <tr>
              <td>URL:</td>
              <td id="detail-url" ></td>
          </tr>
          <tr>
              <td>Date:</td>
              <td id="detail-date" ></td>
          </tr>
      </table>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>


<!-- Modal Form for new Bookmark -->
<div id="add_form" class="modal">
  <form action="?page=addBM" method="post" >
    <div class="modal-content">
        <h5 class="center">New Bookmark</h5>
        <input type="hidden" name="owner" value="<?= $_SESSION["user"]["id"]?>">
        <input type="hidden" name="categ" value="<?php echo $which_categ;  ?>">

        <div class="input-field">
          <input id="title" type="text" name="title" >
          <label for="title">Title</label>
        </div>
        <div class="input-field">
            <input id="url" type="text" name="url" >
            <label for="url">URL</label>
        </div>
        <div class="input-field">
          <textarea id="note" class="materialize-textarea" name="note"></textarea>
          <label for="note">Notes</label>
        </div>
      </div>
      <div class="send-bm modal-footer">
        <button  class="btn waves-effect waves-light" type="submit" name="action">Add
         <i class="material-icons right">send</i>
      </button>
    </div>
  </form>
</div>

<div id="test" class="modal">
  <form action="?page=addCat" method="post" >
    <div class="modal-content">
        <h5 class="center">New Category</h5>
        <input type="hidden" name="owner" value="<?= $_SESSION["user"]["id"]?>">

        <div class="input-field">
          <input id="name" type="text" name="name" >
          <label for="name">Category Name</label>
        </div>

      </div>
      <div class="send-bm modal-footer">
        <button  class="btn waves-effect waves-light" type="submit" name="action">Add
         <i class="material-icons right">send</i>
      </button>
    </div>
  </form>
</div>


<div id="share_form" class="modal">
  <form action="?page=sendNotification" method="post" >
    <div class="modal-content">
    <?php foreach ($users as $user): ?>
      <p>
        <label>
          <input type="checkbox" class="filled-in" checked="checked" name="userId[]" value="<?php echo $user["id"]; ?>" />
          <span><?php echo $user["name"]; ?></span>
        </label>
      </p>
    <?php endforeach; ?>
  </div>
      <div class="send-bm modal-footer">
        <button  class="btn waves-effect waves-light" type="submit" name="action">Send
         <i class="material-icons right">send</i>
      </button>
    </div>
  </form>
</div>


<!-- Initialization of modal elements and listboxes -->
  <script>
  var instanceDetail;

    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems);
        instanceDetail = M.Modal.init(document.getElementById("bm-detail"));
        elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);
    });


    $(function(){
       // page is loaded
       //alert("jquery works");
       $(".bms-delete").click(function(e){
          e.preventDefault() ;
         // alert("Delete Clicked") ;
         let id = $(this).attr("href") ;
         //alert( id + " clicked");
         $("#loader").toggleClass("hide") ; // show loader.
         $.get("index.php",
               { "page" : "delete", "id" : id},
               function(data) {
                  console.log(data) ;
                  $("#row" + id).remove(); // removes from html table.
                  $("#loader").toggleClass("hide") ; // hide loader.
               },
               "json"
         );
       });

          $(".bms-view").click(function(e){
             e.preventDefault();
             let id = $(this).attr("href");


             $.get("index.php",
                   {"page":"getBM","id" : id},
                   function (data) {
                      console.log("test") ;
                      $("#detail-title").text(data.title) ;
                      $("#detail-url").text(data.url) ;
                      $("#detail-note").text(data.note) ;
                      $("#detail-date").text(data.created) ;
                      instanceDetail.open() ;

                   }
                   , "json"
             )
          });

      $(".categ-del").click(function(e){
         e.preventDefault();
        // alert("Delete Clicked") ;
        let id = $(this).attr("href") ;

        $.get("index.php",
              { "page" : "deleteCategory", "id" : id},
              function(data) {
                 console.log(data) ;
                 $("#cat" + id).remove(); // removes from html table.
                 $(".cat"+id).remove();
              },
              "json"
        );

      }

      );







    });

  </script>
