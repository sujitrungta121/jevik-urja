  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// Banner List</small>
                        </h1>

		<hr>
						 <p class="text-right">
               <!-- Trigger the modal with a button -->
               <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add New Banner</button>
             </p>

  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Image</th>
          <th>Created On</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
 <?php $i = 1; foreach($list AS $list) { ?>
 		 <tr>
     <td><?php echo $i;?></td>
     <td><img height="100px;" src="<?php echo $list["file_path"];?>"></td>
		 <td><?php echo $list["created_on"];?></td>
     <td>
       <?php if($list["status"]==1){
              echo "Active";
             }elseif($list["status"]==2){
              echo "Blocked";
             }
        ?>
     </td>
      <td>
        <?php echo form_open("admin/Banner/status_update");?>
        <?php if($list["status"]==2){?>
        <button type="submit" class="btn btn-success">
          Activate
        </button>
        <input type="hidden" name="id" value="<?php echo $list["id"];?>">
        <input type="hidden" name="status" value="1">
        <?php }elseif($list["status"]==1){?>
        <button type="submit" class="btn btn-warning">
          Block
        </button>
         <input type="hidden" name="status" value="2">
        <input type="hidden" name="id" value="<?php echo $list["id"];?>">
        <?php }?>
        <?php echo form_close();?>
        <?php echo form_open("admin/Banner/status_update");?>

        <button style="margin-top:5px;" type="submit" class="btn btn-danger" >
          <span class="glyphicon glyphicon-remove"></span>
          Delete
        </button>
        <input type="hidden" name="id" value="<?php echo $list["id"];?>">
         <input type="hidden" name="status" value="0">
        
       
        <?php echo form_close();?>
		  </td>

		 </tr>
 <?php $i++; } ?>
 
      </tbody>
    </table>


 

		 

 

   </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->


<!-- Modal -->
<?php echo form_open_multipart("admin/Banner/save");?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Banner</h4>
      </div>
      <div class="modal-body">
        
        
  <div class="form-group">
    <label for="email">Banner Image </label>
    <input type="file" required class="form-control" name="image">
  </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>

  </div>
</div>
<?php echo form_close();?>

 
 <script type="text/javascript">
  $(document).ready(function() {
    $('#data-list').dataTable();
  } );
</script>

  <?php $this->load->view('admin/footer'); ?>