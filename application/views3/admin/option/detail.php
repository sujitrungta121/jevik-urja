  <?php $this->load->view('admin/header'); ?>
<div id="page-wrapper">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// Value List</small>
                        </h1>

		<form action="<?php echo $this->config->item('admin_url'); ?>option/value_update/<?php echo $option_id; ?>" method="post" accept-charset="utf-8" class="form-horizontal" role="form">				
		<hr>
						 <p class="text-right"><a href="<?php echo $this->config->item('admin_url'); ?>option/add_new_value/<?php echo $option_id; ?>" class="btn btn-primary btn-lg active" role="button">Add New</a></p>

  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Unit Value</th>
          <th>Unit</th>
 
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
 <?php $i = 0; foreach($values AS $value) { ?>
 		 <tr>
		 <td>
		 <?php echo $value->value_name;?>
		 <input type="hidden" class="form-control" name="option_value_row_id[]" value="<?php echo $value->option_value_row_id; ?>">
		 <input type="text" style="display: none;" class="form-control" name="value_name[]" value="<?php echo $value->value_name; ?>"></td>

     <td><input type="number" name="unit_value[]" step="0.01" value="<?php echo $value->unit_value; ?>" ></td>
     <td>
       <select name="unit[]" id="unit" class="form-control" required >
       <option value="">Select Unit</option>
       <?php foreach($unit_list as $det){?>
        <option <?php if($value->unit== $det->id){ echo ' selected="selected" ';}?> value="<?php echo $det->id;?>"><?php echo $det->name;?></option>
       <?php } ?>
       <!-- <option value="2">KG</option>
       <option value="3">OTHER</option> -->
     </select>

     </td>
 
		   <td>

			<a onclick="return confirm('Are You Sure You Want To Delete This?');" href="<?php echo $this->config->item('admin_url'); ?>option/delete_value/<?php echo $value->option_value_id; ?>"><span class="glyphicon glyphicon-remove"></span></a>
		 
		 </td>

		 </tr>
 <?php $i++; } ?>
      </tbody>
    </table>
    <?php if(1==2){?>
   <p align="center"><button type="submit" class="btn btn-default">Update</button></p>
    <?php } ?>
    </form>


 

		 

 

   </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

 <script type="text/javascript">
   $(document).ready(function() {
    console.log("here i am");
    //$('#data-list').dataTable();

    var myTable=$('#data-list').dataTable();
/*
      $(".dataTables_filter input")
    .unbind()
    .bind('keyup change', function(e) {
        if (e.keyCode == 13 || this.value == "") {
            myTable
                .search(this.value)
                .draw();
        }
    });*/
  } );
 </script>

  <?php $this->load->view('admin/footer'); ?>