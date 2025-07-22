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
          <th>Image</th>
          <th>Brand Name</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
 <?php foreach($list as $row){ ?>
 
 <tr>
<td>
  <img height="100px;" src="<?php echo $row->image;?>">
</td>
 <td><?php echo $row->name; ?></td>
 <td><a href="<?php echo $this->config->item('admin_url'); ?>category/detail/<?php echo $row->id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
 <a href="<?php echo $this->config->item('admin_url'); ?>category/delete/<?php echo $row->id; ?>"><span class="glyphicon glyphicon-remove"></span></a>
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

 

  <?php $this->load->view('admin/footer'); ?>
