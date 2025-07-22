  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// Category List</small>
                        </h1>
						 <p class="text-right"><a href="<?php echo $this->config->item('admin_url'); ?>category/add" class="btn btn-primary btn-lg active" role="button">Add New</a></p>

  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>Main Image</th>
          <th>Banner Image</th>
          <th width="60%">Name</th>
          <th>Rank</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
 <?php foreach($categories as $category){ ?>
 
 <tr>
<td>
  <img height="100px;" src="<?php echo $category->main_image;?>">
</td>
<td>
  <img height="100px;" src="<?php echo $category->banner_image;?>">
</td>
 <td><?php echo $category->category_name; ?></td>
 <td><?php echo $category->rank; ?></td>
 <td><a href="<?php echo $this->config->item('admin_url'); ?>category/detail/<?php echo $category->category_id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
 <a onclick="return confirm('Are You Sure You Want To Delete This Category?');" href="<?php echo $this->config->item('admin_url'); ?>category/delete/<?php echo $category->category_id; ?>"><span class="glyphicon glyphicon-remove"></span></a>
 </td>

 </tr>
 <?php } ?>
      </tbody>
    </table>
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