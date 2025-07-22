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
						<li class="breadcrumb-item active" aria-current="page">My Order</li>
					  </ol>
					</div>
				</div>
				<div class="col-xl-12 mt-2 text-center">
					<h2 class="title">My Order</h2>
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
					<div class="myorder" ng-repeat="ord in myOrderList">
						<div class="title2">
							<h5>Order ID: {{ord.order_id}} Order Date: {{ord.order_date}} Bill Amount Rs. {{ord.grand_total}} Payment Mode : {{ord.payment_mode_name}} Current Status : {{ord.status_name}} <div class="float-right">
								<!-- <a ng-if="ord.status==1" ng-click="editOrder(ord.order_id)" href="#" class="badge bg-success">Edit</a> -->
								<a ng-if="ord.status<3" href="<?php echo base_url("track-order/");?>{{ord.order_id}}" class="badge bg-warning">Track Order</a> 
								<a ng-if="ord.status<3"  href="#" class="badge bg-danger"  ng-click="cancelOrder(ord.order_id)">Cancel Order</a></div></h5>
						</div>
						<div class="p-3" ng-repeat="row in ord.products">
							<div class="row justify-content-between align-items-center">
								<div class="col-xl-1 col-md-2 col-sm-3 col-6">
									<a target="_blank" href="<?php echo base_url("product/");?>{{row.slug_name}}.html"><img src="{{row.image}}" alt="{{row.product_name}}" class="img-fluid"></a>
								</div>
								<div class="col-xl-7 col-md-7 col-sm-5 col-7">
									<p class="mb-1">{{row.product_name}}</p>
									<div class="badge badge-warning">{{row.status_name}}</div>
									<small>Quantity : {{row.variant_name}} x {{row.count}}</small>
								</div>
								<div class="col-xl-3 col-md-3 col-sm-4 col-5">
									<div class="checkitem">
										<span>₹{{row.total_price}}</span>
										<!-- <p class="my-1">1% Off <span>₹18</span></p> -->
									</div>
								</div>
							</div>
						</div>
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
<script src="<?php echo base_url("frontend/assets/js/ng-infinite-scroll.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
<div ng-init="getOrderList()"></div>
</body>
</html>