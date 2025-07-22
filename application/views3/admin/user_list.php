  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// <?php echo $page_title;?></small>
                        </h1>

		<hr>
						 <p class="text-right">
               <!-- Trigger the modal with a button -->
               <button type="button" class="btn btn-primary btn-lg" onclick="excel('user-list');" >Export To Excel</button>
               <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add New User</button>

             </p>

  <table id="data-list" class="table table-bordered table2excel" >
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Mobile Number</th>
          <?php if($type==9){?>
          <th>Address</th>
          <th>Pin Code</th>
          <th>Referral Code</th>
          <?php } ?>
          <th>Entry Time</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
 <?php $i = 1; foreach($list AS $list) { ?>
 		 <tr>
     <td><?php echo $i;?></td>
     <td><?php echo $list["name"];?></td>
     <td><?php echo $list["email"];?></td>
     <td><?php echo $list["mobile"];?></td>
     <?php if($type==9){?>
     <td><?php echo $list["address"];?></td>
     <td><?php echo $list["pin_code"];?></td>
     <td><?php echo $list["referral_code"];?></td>
     <?php } ?>
		 <td><?php echo $list["created_on"];?></td>
     <td>
       <?php if($list["status"]==1){
              echo "Active";
             }elseif($list["status"]==2){
              echo "Blocked";
             }elseif($list["status"]==3){
              echo "Not Authorized";
             }
        ?>
     </td>
      <td>
        <?php echo form_open("admin/User/status_update");?>
        <?php if($list["status"]==2){?>
        <button type="submit" class="btn btn-success">
          Activate
        </button>
        <input type="hidden" name="user_id" value="<?php echo $list["user_id"];?>">
        <input type="hidden" name="status" value="1">
        <?php }elseif($list["status"]==1){?>
        <button type="submit" class="btn btn-warning">
          Block
        </button>
         <input type="hidden" name="status" value="2">
        <input type="hidden" name="user_id" value="<?php echo $list["user_id"];?>">
        <?php }elseif($list["status"]==3){?>
           <button type="submit" class="btn btn-warning">
          Authorize To Login
        </button>
         <input type="hidden" name="status" value="1">
        <input type="hidden" name="user_id" value="<?php echo $list["user_id"];?>">

        <?php } ?>
        <?php echo form_close();?>
        <?php echo form_open("admin/User/status_update");?>

        <button onclick="return confirm('Are You Sure You Want To Delete This User?');" style="margin-top:5px;" type="submit" class="btn btn-danger" >
          <span class="glyphicon glyphicon-remove"></span>
          Delete
        </button>
        <input type="hidden" name="user_id" value="<?php echo $list["user_id"];?>">
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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add User</h4>
      </div>
      <div class="modal-body">
        
        <?php echo form_open("admin/User/save");?>
  <div class="form-group">
    <label for="email">Name :</label>
    <input type="text" class="form-control" id="name" name="name">
  </div>
  <div class="form-group">
    <label for="email">Email :</label>
    <input type="text" class="form-control" id="email" name="email">
  </div>
  <div class="form-group">
    <label for="email">Mobile Number :</label>
    <input type="text" class="form-control" id="mobile_no" name="mobile_no">
  </div>
  <div class="form-group">
    <label for="email">password :</label>
    <input type="text" class="form-control" id="password" name="password">
  </div>
  <div class="form-group">
    <label for="email">Confirm Password :</label>
    <input type="text" class="form-control" id="confirm_password" name="confirm_password">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
  <input type="hidden" name="type" id="type" value="<?php echo $type;?>">
</form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

 <script src="<?php echo base_url(); ?>js/jquery.table2excel.js"></script>
 <script type="text/javascript">
  $(document).ready(function() {
    $('#data-list').dataTable();
  } );

  function excel(name="report"){
    $(".table2excel").table2excel({
      exclude: ".noExl",
      name: name,
      filename: name,
      fileext: ".xls",
      exclude_img: true,
      exclude_links: true,
      exclude_inputs: true
    });
  }
</script>


  <?php $this->load->view('admin/footer'); ?>