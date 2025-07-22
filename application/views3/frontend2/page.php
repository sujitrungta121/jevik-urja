<?php $this->load->view("frontend/common/header");?>
	
	<!-- banner part -->
	<section class="banner">
		<img src="<?php echo base_url("frontend/");?>assets/images/trns-divider.png" class="img-fluid">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div aria-label="breadcrumb">
					  <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $details->name;?></li>
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
						<h3><?php echo $details->name;?></h3>
						<?php echo $details->description;?>
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
<script  src="assets/js/scripts.js"></script>
<script  src="<?php echo base_url("frontend/assets/js/notifIt.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
</body>
</html>