  <?php
  $this->load->view('admin/header');
   ?>
<div id="page-wrapper">

            <div class="container-fluid">


<script>
  $(function () {
    $('#myTab a:first').tab('show')
  });
</script>
<div id="printArea">
<h2><?php echo "Order Id : ". $order["order_id"];?></h2>
  <!-- <p>The .table-striped class adds zebra-stripes to a table:</p>     -->
<table  class="table table-striped data-list" >
	<thead>
	<tr>
		<th>Item</th>
		<th>Variant</th>
		<th>Unit Price</th>
		<th>Qty</th>
		<th>Total</th>
		<th>Remove</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($products as $pro){?>
	<tr>
		<td><?php echo $pro["product_name"];?></td>
		<td><?php echo $pro["variant_name"];?></td>
		<td><?php echo $pro["price"];?></td>
		<td><?php echo $pro["count"];?></td>
		<td><?php echo $pro["price"]*$pro["count"];?></td>
		<td>
		<?php echo form_open("admin/Order/remove_item");?>
			<button  onclick="return confirm('Are You Sure You Want To Remove This product?');" type="submit" class="btn btn-danger">Remove</button>
			<input type="hidden" name="oid" value="<?php echo $pro["oid"];?>">
		<?php echo form_close(); ?>
		</td>
	</tr>
	<?php } ?>
	</tbody>
</table>
</div>
<div align="center" style="margin:10px;">
	<button class="btn btn-warning" onclick="printDiv('printArea')">Print</button>
</div>
<center>
	<?php echo form_open("admin/Order/notify_order_details_updates");?>
		<button type="submit" onclick="return confirm('Are You Sure You Want To Send Email To Customer?');" class="btn btn-primary">Send Email To Customer</button>
		<input name="type" type="hidden" value="1">
		<input type="hidden" name="order_id" value="<?php echo $order["order_id"];?>">

	<?php echo form_close();?>
	<?php echo form_open("admin/Order/notify_order_details_updates");?>
		<input name="type" type="hidden" value="2">
		<button onclick="return confirm('Are You Sure You Want To Send SMS To Customer?');" type="submit" class="btn btn-success">Send SMS To Customer</button>
		<input type="hidden" name="order_id" value="<?php echo $order["order_id"];?>">
	<?php echo form_close();?>

</center>


</div>
<!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
  <!-- AUTOCOMPLETE von jQuery UI -->
 <link href="<?php echo base_url(); ?>jquery-ui-1.11.2.custom/jquery-ui.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="<?php echo base_url();?>jquery-ui-1.11.2.custom/jquery-ui.js"></script>
		  <script type="text/javascript">
				  $(function(){
					  $("#tags").autocomplete({
						source: "<?php echo $this->config->item('admin_url'); ?>order/get_product_name"
					  });
					});
		</script>

<!-- AUTOCOMPLETE von jQuery UI -->

<script type="text/javascript">
  $(document).ready(function() {
    $('#data-list').dataTable();
  } );

  function printDiv(divId) { 
             var divContents = document.getElementById(divId).innerHTML; 
        var a = window.open('', '', 'height=500, width=500'); 
        a.document.write('<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css"><html>'); 
        a.document.write('<body >'); 
        a.document.write(divContents); 
        a.document.write('</body></html>'); 
        a.document.close(); 
        a.print(); 
        } 

</script>


  <?php
  $this->load->view('admin/footer');
   ?>
