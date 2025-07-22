<?php $this->load->view("frontend/common/header");?>
	
	<!-- banner part -->
	<section class="banner">
		<img src="<?php echo base_url("frontend/");?>assets/images/trns-divider.png" class="img-fluid">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div aria-label="breadcrumb">
					  <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.html">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Make Payment</li>
					  </ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /end banner part -->

	<!-- inner page -->
	<section class="inner-page">
		<div class="container">
			<div class="inner-content">
				<div class="row">
					<div class="col-12">
												<!--  The entire list of Checkout fields is available at
						 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->
						<script
						  src="https://code.jquery.com/jquery-3.5.1.slim.js"
						  integrity="sha256-DrT5NfxfbHvMHux31Lkhxg42LY6of8TaYyK50jnxRnM="
						  crossorigin="anonymous"></script>
						<form action="<?php echo base_url("index.php/Home/verify_payment");?>" method="POST" id="form1" name="form1">
						  <script
						    src="https://checkout.razorpay.com/v1/checkout.js"
						    data-key="<?php echo $data['key']?>"
						    data-amount="<?php echo $data['amount']?>"
						    data-currency="INR"
						    data-name="GOMART"
						    data-image="http://gomart.in/frontend/assets/images/logo.png"
						    data-description="Shopping"
						    data-prefill.name="<?php echo $data["cartDetails"]->name;?>"
						    data-prefill.email="<?php echo $data["cartDetails"]->email;?>"
						    data-prefill.contact="<?php echo $data["cartDetails"]->mobile;?>"
						    data-notes.shopping_order_id="<?php echo $data["cartDetails"]->order_id;?>"
						    data-order_id="<?php echo $data['order_id'];?>"
						    data-buttontext="Pay with Razorpay"
						  >
						  </script>
						  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
						  <a href="<?php echo base_url("Home/checkout");?>"><button type="button">Cancel</button></a>
						  <input type="hidden" name="shopping_order_id" value="3456">
						</form>

						<script>
						  $(window).on('load', function() {
						   $('.razorpay-payment-button').click();
						  });
						</script>

					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //end inner page -->
	
	
	<?php $this->load->view("frontend/common/footer");?>
</main>

<!-- Login Modal -->
<?php $this->load->view("frontend/common/login_modal");?>

<!-- Cart Modal -->
<?php $this->load->view("frontend/common/cart_modal");?>

<!-- onload modal 
<div class="login">
	<div class="modal fade" id="homemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="col-12 p-0 text-center">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
				<img src="assets/images/adver.jpg" alt="" class="img-fluid">
			</div>
		  </div>
		</div>
	  </div>
	</div>
</div> -->



<script src="assets/js/jquery.mobile-menu.min.js"></script>    
<script  src="<?php echo base_url("frontend/assets/js/notifIt.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
</body>
</html>