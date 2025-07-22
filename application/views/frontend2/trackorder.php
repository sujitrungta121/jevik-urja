<?php $this->load->view("frontend/common/header");?>
	<!-- inner title part -->
	<section class="inner-title">
		<img src="<?php echo base_url();?>frontend/assets/images/trns-divider.png" class="img-fluid">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div aria-label="breadcrumb">
					  <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Track Order</li>
					  </ol>
					</div>
				</div>
				<div class="col-xl-12 mt-2 text-center">
					<h2 class="title">Track Order</h2>
				</div>
			</div>
		</div>
	</section>
	<!-- /end inner title part -->

	<!-- my order page -->
	<section class="container mb-5">
		<div class="row">
			<div class="col-12">
				<div class="inner-card p-4">
					<div class="myorder">
						<div class="title2">
							<h5>Order ID: {{trackOrderData.order_details.order_id}} <div ng-if="trackOrderData.order_details.status <3" class="float-right" ng-click="cancelOrder(trackOrderData.order_details.order_id)"><a  class="badge bg-danger">Cancel Order</a></div></h5>
						</div>
						<div class="p-3">
							<div class="row justify-content-between align-items-center">
								<!-- <div class="col-xl-1 col-md-2">
									<img src="assets/images/product/Bhindi.jpg" alt="Bhindi" class="img-fluid">
								</div>
								<div class="col-xl-4 col-md-4">
									<p class="mb-1">Kinetics Solar Gel Pink Diamond Nail Polish 070</p>
									<div class="badge badge-warning">Pending</div>
									<small>Date: 19/09/2019 04:33:20 PM</small>
								</div> -->
								<div class="col-xl-10 col-md-10">
									<b>{{trackOrderData.order_details.name}}</b>
									<p>
										{{trackOrderData.order_details.address}}
									</p>
									<div class="badge badge-warning">{{trackOrderData.order_details.status_name}}</div>
									<small>Date: {{trackOrderData.order_details.order_date}}</small>

								</div>
								<div class="col-xl-2 col-md-2">
									<div class="checkitem">
										<span>₹{{trackOrderData.order_details.grand_total}}</span>
										<!-- <p class="my-1">1% Off <span>₹18</span></p> -->
									</div>
								</div>
							</div>
						</div>
						<div class="m-3">
						  <table class="table table-bordered">
					    	<thead>
					    		<tr>
						    		<th>Oder ID</th>
						    		<th>Confirm Order</th>
						    		<th>Process</th>
						    		<th>Delivery</th>
						    	</tr>
					    	</thead>
					    	<tbody>
					    		<td>{{trackOrderData.order_details.order_id}}</td>
					    		<td><span style="color:#2aa003">Your Order has been confirmed</span></td>
					    		<td>
					    			<span style="color:#2aa003" ng-if="trackOrderData.status_log.includes('2')">We are Preparing your order</span>
					    			<span style="color:#2aa003" ng-if="!trackOrderData.status_log.includes('2')">PENDING</span>
					    		</td>
					    		<td>
					    			<span ng-if="!trackOrderData.status_log.includes('3')">
					    				Expected Delivery On {{trackOrderData.order_details.expected_delivery_date}}
					    			</span>
					    			<span style="color:#2aa003" ng-if="trackOrderData.status_log.includes('3')">
					    				{{trackOrderData.order_details.delivered_on}}
					    			</span>
					    			
					    		</td>
					    	</tbody>
					    </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //end my order page -->

	<?php $this->load->view("frontend/common/footer");?>
</main>
<!-- Login Modal -->
<?php $this->load->view("frontend/common/login_modal");?>
<!-- Cart Modal -->
<?php $this->load->view("frontend/common/cart_modal");?>
<script src="assets/js/jquery.mobile-menu.min.js"></script>    
<!-- <script  src="assets/js/scripts.js"></script> -->
<script  src="<?php echo base_url("frontend/assets/js/notifIt.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
<div ng-init="trackOrder('<?php echo $order_id;?>')"></div>
</body>
</html>