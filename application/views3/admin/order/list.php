  <?php $this->load->view('admin/header'); ?>
<style type="text/css">
  .delivered{
    background-color: #ff7e00;
  }
  .my_alert2{
    background-color: #ffd200;
  }
  .boy_assigned{
     background-color:#0060ff;
     color: antiquewhite;
  }
  .cancelled{
    background-color:#ff0000;
    color: antiquewhite;
  }
  .processing{
    background-color:#00ff30;

  }
</style>
<div id="page-wrapper" ng-app="myApp" ng-controller="Ctrl">

            <div class="container-fluid">
                        <h1 class="page-header">
                            Dashboard <small>// Orders List</small>
                        </h1>
<hr/>
<p class="text-right">
              <?php echo form_open("",array("method"=>"GET"));?>
               <!-- Trigger the modal with a button -->
               From Date <input type="date" name="from_date" id="from_date" value="<?php echo $from_date;?>">
              To Date <input type="date" name="to_date" id="to_date" value="<?php echo $to_date;?>">

              <select name="status_id"  style="padding:10px;">
                <option value="">All Status</option> 
                <?php foreach($order_status_list as $list){?>
                <option <?php if($status_id==$list["id"]){echo ' selected="selected" ';}?> value="<?php echo $list["id"];?>"><?php echo $list["name"];?></option>
                <?php } ?>
              </select>



              <button class="btn btn-primary">Submit</button>
              <?php echo form_close();?>

             </p>
<table class="table table-bordered" id="data-list">
      <thead>

        <tr>
          <th>Order ID</th>
          <th>Order Time</th>
          <th>Targeted Delivery Date</th>
           <th>Delivery Timming</th>
           <th>Delivery Person</th>
          <th>Status</th>
          <th>Cancel Reason</th>
          <th>Name</th>
          <th>Mobile No.</th>
          <th>Locality</th>
           <th>Pin Code</th>
          <th>Address</th>
         
         
          <!-- <th>Total</th> -->
          <th></th>
        </tr>
      </thead>
      <tbody>
	  <?php
$current_time= strtotime(date("Y-m-d H:i:s"));
foreach($results as $row) { 
  $delivery_timming_alert_start_time= strtotime('-3 hours', strtotime($row["expected_delivery_date"]." ".$row["delivery_timming_slot_from_time"]));

  $class="default";
  if($row["order_status_id"]==3){
    $class="delivered";
  }elseif($row["order_status_id"]==2 && $row["delivery_person_id"]!=""){
    if($current_time>$delivery_timming_alert_start_time){
      $class="my_alert2";
    }else{
      $class="boy_assigned";
    }
    
  }elseif($row["order_status_id"]==2 && $row["delivery_person_id"]==""){
      $class="processing";
  }elseif($row["order_status_id"]==4){
    $class="cancelled";
  }



?> 
         <tr class="<?php echo $class;?>">
		        <td><?php echo $row["order_id"]; ?></td>
            <td><?php echo $row["date"]; ?></td>
            <td><?php echo $row["expected_delivery_date"]; ?></td>
            <td><?php echo $row["delivery_timming_name"]; ?></td>
            <td>
              <?php echo $row["delivery_person_name"]; ?>
              <?php 
                 /* echo "<br/>".$delivery_timming_start_time;
                  echo "<br/>".$order_alert_time;*/

              ?>
              
            </td>
            <td><?php echo $row["status_name"]; ?></td>
            <td><?php echo $row["cancel_reason"]; ?></td>
           <td><?php echo $row["name"]; ?></td>
           <td><?php echo $row["mobile_no"]; ?></td>
           <td><?php echo $row["locality"]; ?></td>
           <td><?php echo $row["pin_code"]; ?></td>
           <td><?php echo $row["address"]; ?></td>
				   
				   <!-- <td><?php echo $row["total"]; ?></td> -->
				   <td>
            <a target="_blank" href="<?php echo base_url("admin/order/detail/".$row["order_id"]);?>">
            <button class="btn btn-primary">Details</button>
            </a>
             <?php if($row["order_status_id"]==1 || $row["order_status_id"]==2) {?>
            <button style="margin-top:5px;" type="button" ng-click="status_form('<?php echo $row["order_id"]; ?>','<?php echo $row["order_status_id"]; ?>','<?php echo $row["cancel_reason"]; ?>');" class="btn btn-success">Status Update</button>
              <?php } ?>
            <?php if($row["order_status_id"]==2){?>
                <button style="margin-top:5px;" type="button" ng-click="assign_delivery_boy('<?php echo $row["order_id"]; ?>','<?php echo $row["order_status_id"]; ?>');" class="btn btn-info">Assign Delivery Boy</button>
            <?php } ?>
            
            <a target="_blank" href="<?php echo base_url("admin/order/bill/".$row["order_id"]);?>">
            <button class="btn btn-primary">Bill</button>
            </a>
            <!-- <a href="<?php echo $this->config->item('admin_url'); ?>order/detail/<?php echo $row["order_id"]; ?>"><span class="glyphicon glyphicon-pencil"></span></a> <span class="glyphicon glyphicon-remove"></span> --></td>
		 </tr>
<?php } ?>


 
      </tbody>
    </table>
<p><?php echo $links; ?></p>
            </div>
            <!-- /.container-fluid -->



<!-- Modal -->
<?php echo form_open("admin/order/status_update");?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Order Id : {{order_id}}</h4>
      </div>
      <div class="modal-body">
        Change Status 
        <select name="status_id" ng-model="current_status" class="form-control" >
          <?php foreach($order_status_list as $list){?>
          <option value="<?php echo $list["id"];?>"><?php echo $list["name"];?></option>
          <?php } ?>
        </select>
      </div>

      <div class="modal-body" ng-if="current_status==4">
        Reason 
        <textarea name="reason" ng-model="reason" class="form-control"></textarea>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
        <input type="text" style="display: none;" name="order_id" value="{{order_id}}">
      </div>
    </div>

  </div>
</div>
<?php echo form_close();?>


<!--delivery boy Modal -->
<?php echo form_open("admin/order/assign_delivery_person");?>
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Order Id : {{order_id}}</h4>
      </div>
      <div class="modal-body">
        Delivery Person
        <select name="delivery_person_id" ng-model="delivery_person_id" class="form-control" >
          <?php foreach($user_list as $list){?>
          <option value="<?php echo $list["user_id"];?>"><?php echo $list["name"];?></option>
          <?php } ?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
        <input type="text" style="display: none;" name="order_id" value="{{order_id}}">
      </div>
    </div>

  </div>
</div>
<?php echo form_close();?>



        </div>
        <!-- /#page-wrapper -->







 
<script type="text/javascript">
  $(document).ready(function() {
    $('#data-list').dataTable();
  } );

var app = angular.module('myApp', []);
app.controller('Ctrl', function($scope, $http) {
  $scope.status_form=function(order_id,status,reason){
    //alert("dsfvasdfsa");
    console.log("current status : "+status);
    $("#myModal").modal("show");
    $scope.order_id=order_id;
    $scope.current_status=status;
    $scope.reason=reason;
  }

  $scope.assign_delivery_boy=function(order_id,delivery_person_id){
    //alert("dsfvasdfsa");
    console.log("current status : "+status);
    $("#myModal2").modal("show");
    $scope.order_id=order_id;
    $scope.delivery_person_id=delivery_person_id
  }

})
</script>
  <?php $this->load->view('admin/footer'); ?>