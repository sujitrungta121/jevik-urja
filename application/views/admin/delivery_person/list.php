  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// <?php echo $page_title;?></small>
                        </h1>

		<hr>
						 <p class="text-right">
               <!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add New Pin Code</button>
             </p>

  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>code</th>
         <!--  <th>Status</th> -->
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
 <?php $i = 0; foreach($list AS $list) { ?>
 		 <tr>
		 <td>
		 
		 <?php echo $list["code"]; ?></td>
 
		   <!-- <td><?php if($list["status"]==1){echo "Active";}else{echo "Inactive";}?></td> -->
       <td>
        <?php echo form_open("admin/Pin_code/delete");?>
        <button type="submit" class="btn btn-danger" >
          <span class="glyphicon glyphicon-remove"></span>
          Delete
        </button>
        <input type="hidden" value="<?php echo $list["code"]; ?>" name="pin_code">
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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Pin Code</h4>
      </div>
      <div class="modal-body">
        
        <?php echo form_open("admin/Pin_code/save");?>
  <div class="form-group">
    <label for="email">Pin Code:</label>
    <input type="text" class="form-control" id="pin_code" name="pin_code">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

 
 <script type="text/javascript">
  $(document).ready(function() {
    $('#data-list').dataTable();
  } );
</script>

  <?php $this->load->view('admin/footer'); ?>