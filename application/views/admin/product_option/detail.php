<?php $this->load->view('admin/header'); ?>
<div id="page-wrapper" ng-app="myApp" ng-controller="Ctrl">

            <div class="container-fluid">
                        <h1 class="page-header">
                            <?php echo $product_details->name;?> <small>// Size List</small>
                            <?php if(empty($this->session->userdata("user_brand_id"))){?>
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add New Size</button>
                            <?php } ?>
                        </h1>

				
		<hr>
    <!-- <input type="radio" name="stock_mode" ng-click="change_stock_mode(1)" value="1" <?php if($product_details->base_unit_value_stock==0){ echo ' checked="checked"'; } ?> > Individual Variant Stock 
    
    <input type="radio" name="stock_mode" value="2" <?php if($product_details->base_unit_value_stock>0){ echo ' checked="checked"';}?> ng-click="change_stock_mode(2)" > Shared Stock
    <?php echo form_open("admin/Product_option/update_shared_stock/".$option_id."/".$product_id);?> 
    <div ng-if="shared_stock_slected_option_id>0  || stock_mode==2">
      <input required name="shared_stock" id="shared_stock" type="number" <?php if($product_details->base_unit_value_stock>0){ echo ' value="'.$product_details->base_unit_value_stock/$product_details->base_unit_value.'"';}?> >
      <select name="option_value_id" required>
        <option ng-selected="row.pr_value_id==shared_stock_slected_option_id" value="{{row.pr_value_id}}" ng-repeat="row in optionList">{{row.value_name}}</option>
      </select>
      <button type="submit" class="btn btn-primary">Update Shared Stock</button>
    </div> -->
    <?php echo form_close();?>
  <form action="<?php echo $this->config->item('admin_url'); ?>product_option/value_update/<?php echo $option_id; ?>/<?php echo $product_id; ?>" method="post" accept-charset="utf-8" class="form-horizontal" role="form">   
  <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
         <!--  <th>Operation</th> -->
          <!-- <th>Default</th> -->
          <!-- <th>Stock</th> -->
          <!-- <th>Addon Old Price</th>
          <th width="80px;">Discount %</th> -->
          <th>WB Price</th>
          <th>UP Price</th>
          <th>Order</th>
          <?php if(empty($this->session->userdata("user_brand_id"))){?>
          <th>Action</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>

 <!--  <?php // $i=0; foreach($values as $value){ $i++; ?> -->
 
 <tr ng-repeat="row in optionList">
 <td>
  {{row.value_name}}
  <input type="hidden" class="form-control"  value="{{row.pr_value_id}}" name="pr_value_id[]">  
 </td>
 <!-- <td><input type="text"  class="form-control" name="price[]" ng-model="row.price" ng-change="getDiscountPercent(row)"></td> -->
 <td><input type="text" class="form-control" name="wb_price[]" ng-model="row.wb_price"></td>
 <td><input type="text" class="form-control" name="up_price[]" ng-model="row.up_price"></td>
 <td>{{row.order}}</td>

 <?php if(empty($this->session->userdata("user_brand_id"))){?>
 <td>
  <a onclick="return confirm('Do You Really Want To Delete This Variant?');" href="<?php echo base_url("admin/product_option/delete_value/");?>{{row.pr_value_id}}"><span class="glyphicon glyphicon-remove"></span></a>
 </td>
<?php } ?>
</tr>
 <?php // } ?>
 <tr>
 <td colspan="7">
      <p align="center">
        <a href="<?php echo base_url("admin/Product/lists");?>">
          <button type="button" class="btn btn-success">Click Here To Back To Product List</button>
        </a>
        <button type="submit" onclick="return checkGate()" class="btn btn-primary">Update Data</button>
        <input type="hidden" name="stock_mode" id="up_stock_mode" value="{{stock_mode}}" >
        <input type="hidden" name="prev_stock_mode" id="prev_stock_mode" value="<?php if($shared_stock_slected_option_id>0){echo 2;}else{echo 1;}?>" >
        <input type="hidden" name="product_id" value="<?php echo $product_id;?>" >

      </p>
	  </td>
</tr>
	  </form>
      </tbody>
    </table>





<?php echo form_open("admin/product_option/add_value/".$option_id."/".$product_id,array("class"=>"form-horizontal"));?>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Size</h4>
      </div>
      <div class="modal-body">
        
  <div class="form-group">
    <label class="control-label col-sm-3" for="email">Size :</label>
    <div class="col-sm-9">
        <select name="value_id" required class="form-control">
          <option value="">Select Size</option>
          <?php foreach($value_list AS $values){ ?>
            <option value="<?php echo $values->option_value_id; ?>"><?php echo $values->value_name; ?></option>
            <?php } ?>
        </select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-3" for="email">WB Price :</label>
    <div class="col-sm-9">
      <input type="number" required class="form-control" id="wb_price" name="wb_price" ng-model="newRow.wb_price">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-3" for="email">UP Price :</label>
    <div class="col-sm-9">
      <input type="number" required class="form-control" id="up_price" name="up_price" ng-model="newRow.up_price">
    </div>
  </div>

   <div class="form-group">
    <label class="control-label col-sm-3" for="email">Order :</label>
    <div class="col-sm-9">
      <input type="number" required class="form-control" id="order" name="order" ng-model="newRow.order">
    </div>
  </div>

      </div>
      <div class="modal-footer">
        <input type="hidden" name="option_id" value="<?php echo $option_id; ?>">
  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>

  </div>
</div>
<?php echo form_close();?>



 

		 

 

   </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<script type="text/javascript">
  function checkGate(){
    if($("#up_stock_mode").val()!=$("#prev_stock_mode").val()){
      var c=confirm("Change In Stock Mode Done During This Session...Your Previous Stock Mode Data Will Be Erased If You Press Confirm");
      if(c){
        return true;
      }else{
        return false;
      }
    }
  }
  var app = angular.module('myApp', []);
  app.controller('Ctrl', function($scope, $http) {
    $scope.optionList=[];
    $scope.newRow = {
      old_price : 0,
      disc : 0,
      wb_price: 0,
      up_price: 0
    }
   
    $scope.product_details=[];

    $scope.product_details=<?php echo json_encode($product_details);?>;
    $scope.shared_stock_slected_option_id=<?php echo $shared_stock_slected_option_id;?>;
    <?php if($shared_stock_slected_option_id>0){?>
     $scope.stock_mode=2;
    <?php }else{?>
     $scope.stock_mode=1;
    <?php }?>

    $scope.loadProductOptions=function(){
      //alert("sdaSDAS");
      $http.get("<?php echo base_url();?>Api/productOptions/<?php echo $product_id;?>")
        .then(function (response) {
        //alert(response);
        $scope.optionList = response.data.list;
        console.log(response.data.list);
      });
    }
     $scope.loadProductOptions();

    $scope.calc=function(row){
      var diff_amt=row.old_price-row.wb_price;
      var percent=diff_amt*100/row.old_price;
      row.disc=percent;
    }

    $scope.getNewPrice=function(row){
        row.wb_price= Math.round(angular.copy(row.old_price-(row.old_price*row.disc/100)));
    }

    $scope.getDiscountPercent=function(row){
      console.log("hello");
        var disc= Math.round(angular.copy((row.old_price-row.wb_price)*100/row.old_price));
        console.log("disc : "+disc);
        row.disc=disc;
    }

    $scope.getDiscountPercent2=function(row){
      console.log("hello");
      var disc= Math.round(angular.copy((row.old_price-row.wb_price)*100/row.old_price));
        console.log("disc : "+disc);
        row.disc=disc;
       
    }



    $scope.change_stock_mode=function(mode){
      console.log("mode : "+mode);
      if(mode==1){
        $scope.shared_stock_slected_option_id=0;
      }else{
         $scope.shared_stock_slected_option_id=<?php echo $shared_stock_slected_option_id;?>;
      }
      $scope.stock_mode=mode;
    }

  })
</script>
 
  <?php $this->load->view('admin/footer'); ?>