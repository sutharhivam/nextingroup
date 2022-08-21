<?php
    $page_title="Manage Artist";
    include("includes/header.php");
    require("includes/lb_helper.php");
    require("language/language.php");
    
    if(isset($_POST['data_search'])){
        $artist_qry="SELECT * FROM tbl_artist 
        WHERE tbl_artist.artist_name like '%".addslashes($_POST['search_text'])."%'  
        ORDER BY tbl_artist.artist_name"; 
        $result=mysqli_query($mysqli,$artist_qry);
    }else{
        
        $tableName="tbl_artist";   
        $targetpage = "manage_artist.php"; 
        $limit = 12; 
        $query = "SELECT COUNT(*) as num FROM $tableName";
        $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
        $total_pages = $total_pages['num'];
        $stages = 3;
        $page=0;
        
        if(isset($_GET['page'])){
            $page = mysqli_real_escape_string($mysqli,$_GET['page']);
        }
        
        if($page){
            $start = ($page - 1) * $limit; 
        }else{
            $start = 0; 
        } 
        
        $artist_qry="SELECT * FROM tbl_artist ORDER BY tbl_artist.`artist_name` LIMIT $start, $limit"; 
        $result=mysqli_query($mysqli,$artist_qry);
    }

?>    
<div id="content" class="main-content">
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <!----------------------------------------------------------------------------------------------------------------->
            <div class="widget-content widget-content-area br-6">
                <div class=" mt-2">
                    <div class="row">
                        <div class="col-sm-8 m-auto p-auto"><h3 class="mr-1"><?=$page_title ?></h3></div>
                        <div class="col-sm-4 m-auto p-auto">
                            <div class="search_list m-auto p-auto">
                                <div class="search_block">
                                    <form  method="post" action="">
                                        <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" value="<?php if(isset($_POST['data_search'])){ echo $_POST['search_value'];} ?>" required>
                                        <button type="submit" name="data_search" class="btn-search"><i class="fa fa-search"></i></button>
                                    </form>  
                                </div>
                                <div class="add_btn_primary"> <a href="add_artist.php?add=yes">Add Artist</a> </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="border-bottom: 1px solid #f1f2f3; margin-top: 20px; margin-bottom: 20px;"></div>
                <div class="row">
                    <?php 
                    $i=0;
                    while($row=mysqli_fetch_array($result))
                    {         
                    ?>
                    <div class="col-lg-3 col-sm-6 col-xs-12">
                        <div class="block_wallpaper hover14">           
                            <div class="wall_image_title">
                                <h3 style="color: #ffffff; font-size: 1.30rem;"><?php echo $row['artist_name'];?></3>
                                <ul>                
                                    <li>
                                        <a href="add_artist.php?artist_id=<?php echo $row['id'];?>" class=" bs-tooltip" target="_blank"  data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    </li>               
                                    <li>
                                        <a href="" class="btn_delete_a bs-tooltip" data-id="<?php echo $row['id'];?>" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <?php if($row['artist_image'] == ""){?>
                                <span><img src="assets/images/300x300.jpg" /></span>
                            <?php }else{?>
                                <span><img src="images/<?php echo $row['artist_image'];?>" /></span>
                            <?php }?>
                        </div>
                    </div>
                    <?php
                    $i++;
                    }
                    ?>     
                </div>
                <div class="col-md-12 col-xs-12">
                    <div class="paginating-container pagination-default">
                        <?php if(!isset($_POST["data_search"])){ include("pagination.php");}?>
                    </div>
                </div>
            </div>
            <!----------------------------------------------------------------------------------------------------------------->
        </div>
    </div>
</div>
<?php include("includes/footer.php");?>

<script type="text/javascript">
 $(".btn_delete_a").on("click", function(e) {

    e.preventDefault();

    var _id = $(this).data("id");
    var _table = 'tbl_artist';
    
    swal({
        title: "Are you sure to delete this?",
		type: "warning",
	    confirmButtonClass: 'btn btn-primary mb-2',
        cancelButtonClass: 'btn btn-danger mb-2',
        buttonsStyling: false,
		showCancelButton: true,
		confirmButtonText: "Yes",
		cancelButtonText: "No",
		closeOnConfirm: false,
		closeOnCancel: false,
		showLoaderOnConfirm: true
    }).then(function(result) {
      if (result.value) {
          
         $.ajax({
          type: 'post',
          url: 'processData.php',
          dataType: 'json',
          data: {id: _id, for_action: 'delete', table: _table, 'action': 'multi_action'},
          success: function(res) {
            console.log(res);
            $('.notifyjs-corner').empty();
            if(res.status=='1'){
                swal({
                  title: 'Successfully',
                  text: "Artist is deleted.",
                  type: 'success',
                }).then(function(result) {
                     location.reload();
                });
            }
            else if(res.status=='-2'){
                swal(res.message);
            }
          }
        });
      } else {
            swal.close();
      }
      
    });

});

</script>  