  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// Brand List</small>
                        </h1>
                         <p class="text-right"><a href="<?php echo $this->config->item('admin_url'); ?>Brand/form" class="btn btn-primary btn-lg active" role="button">Add New</a></p>

  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>Image</th>
          <th>Brand Name</th>
          <th>Description</th>
          <th colspan="2">Action</th>
        </tr>
      </thead>
      <tbody>
 <?php foreach($list as $row){ ?>
 
 <tr>
<td>
  <img height="100px;" src="<?php echo $row->image;?>">
</td>
 <td><?php echo $row->name; ?></td>
 <td><?php echo $row->description; ?></td>
 <td><a href="<?php echo $this->config->item('admin_url'); ?>Brand/form/<?php echo $row->brand_id; ?>">
  <button class="btn btn-primary">
    <span class="glyphicon glyphicon-pencil"></span> Edit
  </button>
</a>
</td>

<td>
<?php echo form_open("admin/Brand/remove");?>
<button  onclick="confirm('Do You Really Want To Delete This Brand?');" class="btn btn-danger" type="submit" > <span class="glyphicon glyphicon-remove"></span> delete</button>
<input type="hidden" name="brand_id" value="<?php echo $row->brand_id; ?>">
<?php echo form_close();?>

 </td>

 </tr>
 <?php } ?>
      </tbody>
    </table>
<p><?php //echo $links; ?></p>
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
