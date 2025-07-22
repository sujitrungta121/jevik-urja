<?php $this->load->view("frontend/common/header");?>	
	
	<!-- banner part -->
	<section class="banner">
		<img src="<?php echo base_url();?>frontend/assets/images/trns-divider.png" class="img-fluid">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div aria-label="breadcrumb">
					  <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.html">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Information</li>
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
			<div class="thankyou">
				<div class="row justify-content-center">
					<div class="col-xl-3">
						<img src="<?php echo base_url();?>frontend/assets/images/thank-img.png">
					</div>
					<div class="col-xl-7">
						<h3><?php echo $this->session->flashdata('msg');?></h3>
						<!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> -->
						<a href="<?php echo base_url();?>" class="btn btn-success">Back to Homepage</a>
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


<script src="assets/js/jquery.mobile-menu.min.js"></script>    
<script  src="assets/js/scripts.js"></script>
<script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/ng-infinite-scroll.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
</body>
</html>