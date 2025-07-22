  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// <?php echo $page_title;?></small>
                        </h1>

		<hr>
						 <p class="text-right">
              <?php echo form_open();?>
               <!-- Trigger the modal with a button -->
               From Date <input type="date" name="from_date" id="from_date" value="<?php echo $from_date;?>">
              To Date <input type="date" name="to_date" id="to_date" value="<?php echo $to_date;?>">

              <button class="btn btn-primary">Submit</button>
              <?php echo form_close();?>

             </p>

  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Mobile Number</th>
          <th>Earning Amount</th>
          <th>Time</th>
          <th>Referral Code</th>
          <th>Against Order Id</th>
          <th>Order Amt</th>
        </tr>
      </thead>
      <tbody>
 <?php $i = 1; foreach($list AS $list) { ?>
 		 <tr>
       <td><?php echo $i;?></td>
       <td><?php echo $list["name"];?></td>
       <td><?php echo $list["email"];?></td>
       <td><?php echo $list["mobile"];?></td>
       <td><?php echo $list["in_amt"];?></td>
       <td><?php echo $list["created_on"];?></td>
       <td><?php echo $list["own_referral_code"];?></td>
       <td><?php echo $list["order_id"];?></td>
  		 <td><?php echo $list["grand_total"];?></td>
		 </tr>
 <?php $i++; } ?>
 
      </tbody>
    </table>


 

		 

 

   </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
 <script type="text/javascript">
  $(document).ready(function() {
    $('#data-list').dataTable();
  } );
</script>

  <?php $this->load->view('admin/footer'); ?>