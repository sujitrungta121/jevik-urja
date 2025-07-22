  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// <?php if($brand_id==""){echo "Add Brand";}else{echo "Edit Brand";}?></small>
                        </h1>
            
    <form action="<?php echo $this->config->item('admin_url'); ?>Brand/form/<?php echo $brand_id;?>" method="post" accept-charset="utf-8" class="form-horizontal" role="form"  enctype="multipart/form-data"  >
            
  <div role="tabpanel">       
   <ul class="nav nav-tabs" role="tablist">
<?php foreach($languages as $language){ ?>
<?php if($language->default == 1){ ?>
    <li role="presentation" class="active"><a href="#<?php echo $language->id; ?>" aria-controls="<?php echo $language->id; ?>" role="tab" data-toggle="tab"><?php echo $language->language_name; ?></a></li>
 

<?php } else { ?>
    <li role="presentation"><a href="#<?php echo $language->id; ?>" aria-controls="<?php echo $language->id; ?>" role="tab" data-toggle="tab"><?php echo $language->language_name; ?></a></li>

<?php } ?>
<?php } ?>
 
</ul>

 

<!-- Tab panes -->
<div class="tab-content">
<?php foreach($languages as $language){ ?>
<?php if($language->default == 1){ ?>
 
  <div role="tabpanel" class="tab-pane fade in active" id="<?php echo $language->id; ?>">
  <br>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Brand Name</label>
    <div class="col-sm-10">
      <input name="name[]" type="text" class="form-control" id="inputNameStandart" value="<?php if(isset($desc_data[$language->id]['name'])){  echo $desc_data[$language->id]['name'];}?>" >
    </div>
  </div>
 
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Description </label>
    <div class="col-sm-10">
      <!-- <input  type="text" class="form-control" id="inputEmail3" > -->
      <textarea class="form-control" id="inputEmail3" name="description[]">
        <?php if(isset($desc_data[$language->id]['description'])){  echo $desc_data[$language->id]['description'];}?>
      </textarea>
      <input type="hidden" name="language_id[]" value="<?php echo $language->id; ?>" >
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">User Email</label>
    <div class="col-sm-10">
      <input name="email" type="text" class="form-control" id="email" value="<?php if(isset($brand_user_data->email)){  echo $brand_user_data->email;}?>" >
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input name="password" type="password" class="form-control" id="password" value="<?php if(isset($brand_user_data->password)){  echo $brand_user_data->password;}?>" >
    </div>
  </div>

  
  
  
  </div>
<?php } else { ?>

  
  
  </div>
<?php } ?>
<?php } ?>

   </div>
  </div>
 
 <hr>


  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Image</label>
    <div class="col-sm-10">
      <input type="file" name="image" <?php if($brand_id==""){echo "required"; }?> class="form-control" id="image">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <?php if($brand_id==""){?>
      <button type="submit" class="btn btn-default">Add New</button>
      <?php }else{ ?>
       <button type="submit" class="btn btn-default">Update</button>
      <?php } ?>
    </div>
  </div>
</form>

 
 
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

 

  <?php $this->load->view('admin/footer'); ?>