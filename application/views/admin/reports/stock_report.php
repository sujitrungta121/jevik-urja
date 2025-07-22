  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// <?php echo $page_title;?></small>
                        </h1>

		<hr>
						 <p class="text-right">
             </p>
<?php echo form_open("admin/Reports/stock_update");?>
  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Stock Mode</th>
          <th>Variant</th>
          <th>Quantity</th>
          <th>Stock</th>
        </tr>
      </thead>
      <tbody>
 <?php $i = 1; foreach($list as $list) { ?>
 		 <tr>
       <td><?php echo $i;?></td>
       <td><?php echo $list["product_name"];?></td>
       <td><?php echo $list["stock_mode"];?></td>
       <td><?php echo $list["option_name"];?></td>
       <td><?php echo $list["stock"];?></td>
       <td>
        <input type="text" name="stock[]" value="<?php echo $list["stock"];?>">
        <input type="hidden" name="pov_id[]" value="<?php echo $list['pov_id'];?>">
        <input type="hidden" name="mode[]" value="<?php echo $list['mode'];?>">
        <input type="hidden" name="product_id[]" value="<?php echo $list['product_id'];?>">

      </td>
		 </tr>
 <?php $i++; } ?>
 
      </tbody>
    </table>

  <div align="center">
    <button type="submit" class="btn btn-primary">Update Stock</button>
  </div>

  <?php echo form_close();?>


 

		 

 

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