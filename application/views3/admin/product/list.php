  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// Product List</small>					

                        </h1>
 <p class="text-right"><a href="<?php echo base_url(); ?>admin/product/add" class="btn btn-primary btn-lg active" role="button">Add New</a></p>
 <?php echo form_open("admin/Product/status_update"); ?>
  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>Image</th>
      <th>Active</th>
      <th>Name</th>
      <th>Brand</th>
      <th>Category</th>
      <th>Price</th>
		  <th>Old Price</th>
          <th>Rank</th>
		  <th>General Attribute</th>

          <th>Action</th>
        </tr>
      </thead>
      <tbody>
 <?php echo $count=0; ?>
 <?php foreach($products as $product){  ?>
 
 <tr>
 <td><img src="<?php echo $product->image; ?>" width="75" height="75"></td>
 <td>
   <input name="active[<?php echo $count;?>]" type="checkbox" value="<?php echo $product->product_id; ?>" <?php if($product->status==1){echo 'checked="checked"';}?> >
   <input type="hidden" name="product_id[<?php echo $count;?>]" value="<?php echo $product->product_id; ?>">
 </td>
 <td><?php echo $product->name; ?></td>
 <td><?php echo $product->brand_name; ?></td>
 <td><?php echo $product->category_name; ?></td>
 <td><?php echo $product->price; ?></td>
 <td><?php echo $product->old_price; ?></td>
 <td><?php echo $product->rank; ?></td>
 <td><a href="<?php echo $this->config->item('admin_url'); ?>product_option/detail/25/<?php echo $product->id; ?>">Price Options</a>
</td>

 <td><a href="<?php echo $this->config->item('admin_url'); ?>product/detail/<?php echo $product->id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
 <a onclick="return confirm('Are You Sure You Want To Delete This Product?');" href="<?php echo $this->config->item('admin_url'); ?>product/delete/<?php echo $product->id; ?>"><span class="glyphicon glyphicon-remove"></span></a>
 </td>

 </tr>
 <?php 
  $count++;
} ?>
      </tbody>
    </table>
<center>
  <button type="submit" onclick="return confirm('Do You really Want To Update Status Of This Products?')" class="btn btn-primary">Update Status</button>
</center>
<?php echo form_close();?>
<p><?php echo $links; ?></p>
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