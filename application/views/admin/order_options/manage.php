<?php $this->load->view('admin/header'); ?>
<script>
    var app = angular.module('orderApp', []);
    app.controller('OrderCtrl', function($scope) {
        $scope.optionList = <?php echo json_encode($optionList); ?>;
    });
</script>

<div id="page-wrapper" ng-app="orderApp" ng-controller="OrderCtrl">
    <div class="container-fluid">
        <h1 class="page-header">
            Order Options <small>// Manage Order Options</small>
        </h1>
        
     <table id="data-list" class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
         <!--  <th>Operation</th> -->
          <!-- <th>Default</th> -->
          <!-- <th>Stock</th> -->
          <!-- <th>Addon Old Price</th>
          <th width="80px;">Discount %</th> -->
          <th>Order</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="row in optionList">
            <td>
                {{row.value_name}}
                <input type="hidden" class="form-control" value="{{row.option_value_id}}" name="option_value_id[]">  
            </td>
            <td>
                <input type="text" class="form-control" name="order[]" ng-model="row.order">
            </td>

            <td>
                <button type="button" class="btn btn-primary" ng-click="updateOrder(row)">Update</button>
            </td>
        </tr>
    </tbody>
</table>
  </div>
  </div>

<div class="row mt-3">
    <div class="col-12 text-center">
        <button type="button" class="btn btn-primary" ng-click="saveAllOrders()">Save All Changes</button>
        <a href="<?php echo base_url("admin/Product/lists");?>" class="btn btn-success">Back to Product List</a>
    </div>
</div>

<script>
app.controller('OrderCtrl', function($scope, $http) {
    $scope.optionList = <?php echo json_encode($optionList); ?>;
    
    $scope.updateOrder = function(row) {
        var data = {
            option_value_id: [row.option_value_id],
            order: [row.order]
        };
        
        $http({
            method: 'POST',
            url: '<?php echo base_url("admin/order_options/update"); ?>',
            data: data
        }).then(function(response) {
            if (response.data && response.data.status === 'success') {
                alert('Order updated successfully!');
            } else {
                alert('Error updating order!');
            }
        }, function(error) {
            alert('Error updating order!');
            console.log(error);
        });
    };
    
    $scope.saveAllOrders = function() {
        var data = {
            option_value_id: [],
            order: []
        };
        
        $scope.optionList.forEach(function(row) {
            data.option_value_id.push(row.option_value_id);
            data.order.push(row.order);
        });
        
        $http({
            method: 'POST',
            url: '<?php echo base_url("admin/order_options/update"); ?>',
            data: data
        }).then(function(response) {
            if (response.data && response.data.status === 'success') {
                alert('All orders updated successfully!');
            } else {
                alert('Error updating orders!');
            }
        }, function(error) {
            alert('Error updating orders!');
            console.log(error);
        });
    };

  });

<?php $this->load->view('admin/footer'); ?>
