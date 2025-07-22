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
              <button type="button" onclick="excel('sales-report')" class="btn btn-success">Export To Excel</button>
              <?php echo form_close();?>

             </p>

  <table id="data-list" class="table table-bordered table2excel">
      <thead>
        <tr>
          <th>Order Id</th>
          <th>Time</th>
          <th>Name</th>
          <th>Mobile Number</th>
          <th>Address</th>
          <th>City</th>
          <th>Amount</th>
          <th>Delivery Charges</th>
          <th>Total Amount</th>
          <th>Payment Method</th>
        </tr>
      </thead>
      <tbody>
 <?php $i = 1; foreach($list AS $list) { ?>
 		 <tr>
       <td><?php echo $list["order_id"];?></td>
       <td><?php echo $list["delivered_on"];?></td>
       <td><?php echo $list["name"];?></td>
       <td><?php echo $list["mobile_no"];?></td>
       <td><?php echo $list["address"];?></td>
       <td><?php echo $list["city"];?></td>
       <td><?php echo $list["total"];?></td>
       <td><?php echo $list["delivery_charges"];?></td>
       <td><?php echo $list["grand_total"];?></td>
  		 <td><?php echo $list["payment_mode_name"];?></td>
		 </tr>
 <?php $i++; } ?>
 
      </tbody>
    </table>


 

		 

 

   </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
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