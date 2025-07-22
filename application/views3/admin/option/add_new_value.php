  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// Add Product Option Value</small>
                        </h1>
						
		<form action="<?php echo $this->config->item('admin_url'); ?>option/add_new_value" method="post" accept-charset="utf-8" class="form-horizontal" role="form">				
						
 
 
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
    <label for="inputEmail3" class="col-sm-2 control-label"><span id="optName">Unit Value</span></label>
    <div class="col-sm-10">
      <input name="value[<?php echo $language->id; ?>][option_id]" value="<?php echo $option_id; ?>" type="hidden" class="form-control" id="inputNameStandart" >
      <input name="value[<?php echo $language->id; ?>][language_id]" value="<?php echo $language->id; ?>" type="hidden" class="form-control" id="inputNameStandart" >
      <input name="value[<?php echo $language->id; ?>][value_name]" type="text" class="form-control" id="inputNameStandart" >
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Unit</label>
    <div class="col-sm-10">
     <select name="value[<?php echo $language->id; ?>][unit]" id="unit" class="form-control" required >
       <option value="">Select Unit</option>
       <?php foreach($unit_list as $det){?>
        <option <?php if(count($unit_list)==1){ echo "selected"; }?> value="<?php echo $det->id;?>"><?php echo $det->name;?></option>
       <?php } ?>
       <!-- <option value="2">KG</option>
       <option value="3">OTHER</option> -->
     </select>
    </div>
  </div>

  
  
  </div>
<?php } else { ?>
  <div role="tabpanel" class="tab-pane fade" id="<?php echo $language->id; ?>">
   <br>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Option Name</label>
    <div class="col-sm-10">
      <input name="value[<?php echo $language->id; ?>][option_id]" value="<?php echo $option_id; ?>" type="hidden" class="form-control" id="inputNameStandart" >
      <input name="value[<?php echo $language->id; ?>][language_id]" value="<?php echo $language->id; ?>" type="hidden" class="form-control" id="inputNameStandart" >
      <input name="value[<?php echo $language->id; ?>][value_name]" type="text" class="form-control" id="inputEmail3" >
    </div>
  </div>


  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Unit</label>
    <div class="col-sm-10">
     <select   name="unit[<?php echo $language->id; ?>][option_id]" id="unit" class="form-control" required >
       <option value="">Select Unit</option>
       <option value="1">GRAM</option>
       <option value="2">KG</option>
       <option value="3">OTHER</option>
     </select>
    </div>
  </div>
 
 
  
  
  </div>
<?php } ?>
<?php } ?>

   </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Add New</button>
    </div>
  </div>
</form>

 
 
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

 <script>
   function getVal(val){
    if(val==1 || val==2){
      //$("#inputNameStandart").prop("type", "number");
      $("#optName").html("Unit Value");
    }else{
     // $("#inputNameStandart").prop("type", "text");
      $("#optName").html("Option Name");

    }

   }
 </script>

  <?php $this->load->view('admin/footer'); ?>