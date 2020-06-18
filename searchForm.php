<?php
require "db.php" ;

$bookmarks = $db->query("select user.id uid, bookmark.id bid, name, title, note, created, url,categ
                              from bookmark, user
                              where (user.id = bookmark.owner and user.id = {$_SESSION['user']['id']}) and ((title Like '%".$search."%') or (note Like '%".$search."%' ))")->fetchAll(PDO::FETCH_ASSOC) ;

$bookmarks = $db->query("Select * From bookmark Where (title Like '%".$search."%') or (note Like '%".$search."%' )")->fetchAll(PDO::FETCH_ASSOC);
echo $bookmarks;
$start=($_GET["no"]-1)*3+1;
$end=$_GET["no"]*3;
$length;
echo $start,$end;

if(count($bookmarks)%3 ==0){
$length = count($bookmarks)/3;
}
else{
  $length = count($bookmarks)/3+1;
}
$bookmarks=array_slice($bookmarks,$start,$end);



 ?>

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
          <tr id="row<?= $bm["id"] ?>" class="cat<?php echo $bm["categ"]; ?>">
              <td><span class="truncate"><a href="<?= $bm['url'] ?>"><?= $bm['title'] ?></a></span></td>
              <td><span class="truncate"><?= $bm['note'] ?></span></td>
              <td class="created hide-on-med-and-down"><?php
                 $date = new DateTime($bm['created']);
                 echo $date->format("d M y");
                ?>
               </td>
               <td class="action">
                  <a href="<?= $bm["id"] ?>" class="bms-delete btn-small"><i class="material-icons">delete</i></a>
                  <a class="btn-small bms-view" href="<?= $bm['id'] ?>"><i class="material-icons">visibility</i></a>
                   <a class="bms-edit btn-small modal-trigger " href="#edit_form<?= $bm["id"] ?>">
                     <i class="material-icons">edit</i>
                   </a>

                   <div id="edit_form<?= $bm["id"] ?>" class="modal">
                     <form action="?page=searchForm&no=<?php echo $_GET["no"]; ?>" method="post" >
                       <div class="modal-content">
                           <h5 class="center">Edit Bookmark</h5>
                           <input type="hidden" name="id" value="<?= $bm["id"] ?>">
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

        <?php endforeach ?>
     </table>

 </div>

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



 <ul class="pagination">
     <li id="first" class="<?php if($_GET["no"]==1){echo "disabled";}else{ echo "waves-effect";} ?>"><a href="?page=searchForm&no=<?php echo $_GET["no"]-1; ?>"><i class="material-icons">chevron_left</i></a></li>
     <?php for ($i=1; $i <=$length; $i++) {?>

     <li  class="<?php if($_GET["no"]==$i){echo "active";}else{echo "waves-effect";} ?>"><a href="?page=searchForm&no=<?php echo $i; ?>"><?php echo $i; ?></a></li>

     <?php  }?>
   <li id="last" class="<?php if($_GET["no"]==$length){echo "disabled";}else{ echo "waves-effect";} ?>"><a href="?page=searchForm&no=<?php echo $_GET["no"]+1; ?>"><i class="material-icons">chevron_right</i></a></li>
 </ul>

 <script type="text/javascript">

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
     $(".bms-view").click(function functionName(e) {
       e.preventDefault();
       let id = $(this).attr("href");

       $.get("index.php",
             {"page":"getBM","id":id},
             function (data) {
               console.log(data.title);
               $("#detail-title").text(data.title);
               $("#detail-url").text(data.url);
               $("#detail-note").text(data.note);
               $("#detail-date").text(data.created);
             },
             "json"
             )

     });

   });

 var get = <?php echo json_encode($_GET["no"], JSON_HEX_TAG); ?>;
 var length = <?php echo json_encode($length, JSON_HEX_TAG); ?>;

 $("#first").click(function(e){
    if (get==1) {
      e.preventDefault();
    }
 });
 $("#last").click(function(e){
    if (get==length) {
      e.preventDefault();
    }
 });

 </script>
