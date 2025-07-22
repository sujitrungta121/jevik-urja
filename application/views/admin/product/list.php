  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// Product List</small>					

                        </h1>
<?php if(empty($this->session->userdata("user_brand_id"))){?>
 <p class="text-right"><a href="<?php echo base_url(); ?>admin/product/add" class="btn btn-primary btn-lg active" role="button">Add New</a></p>
<?php } ?>
 <?php echo form_open("admin/Product/status_update"); ?>
  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>Image</th>
      <th>Active</th>
      <th>Name</th>
      <th>Brand</th>
      <th>Category</th>
      <th>WB Price</th>
		  <th>WB Old Price</th>
       <th>UP Price</th>
		  <th>UP Old Price</th>
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
 <td><?php echo $product->wb_price; ?></td>
 <td><?php echo $product->wb_old_price; ?></td>
 <td><?php echo $product->up_price; ?></td>
 <td><?php echo $product->up_old_price; ?></td>
 <td><?php echo $product->rank; ?></td>
 <td><a href="<?php echo $this->config->item('admin_url'); ?>product_option/detail/25/<?php echo $product->id; ?>">Size Options</a>
</td>

 <td>
<?php if(empty($this->session->userdata("user_brand_id"))){?>
  <a href="<?php echo $this->config->item('admin_url'); ?>product/detail/<?php echo $product->id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
<?php } ?>
<?php if(empty($this->session->userdata("user_brand_id"))){?>
 <a onclick="return confirm('Are You Sure You Want To Delete This Product?');" href="<?php echo $this->config->item('admin_url'); ?>product/delete/<?php echo $product->id; ?>"><span class="glyphicon glyphicon-remove"></span></a>
<?php } ?>
 </td>

 </tr>
 <?php 
  $count++;
} ?>
      </tbody>
    </table>
<center>
<?php if(empty($this->session->userdata("user_brand_id"))){?>
  <button type="submit" onclick="return confirm('Do You really Want To Update Status Of This Products?')" class="btn btn-primary">Update Status</button>
<?php } ?>
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