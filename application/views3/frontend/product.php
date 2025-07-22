	<?php $this->load->view("frontend/common/header");?>
	<!-- breadcum -->
	<img src="<?php echo base_url();?>frontend/assets/images/trns-divider.png" class="img-fluid">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo base_url("category/".$details['category_slug_name']);?>.html"><?php echo $details["category_name"];?></a></li>
					<li class="breadcrumb-item active" aria-current="page"><?php echo $details["name"];?></li>
				  </ol>
				</div>
			</div>
		</div>
	</div>
	<!-- /end breadcum -->
	
	<!-- product view -->
	<section class="productview">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="pro-card">
						<div class="row">
							<div class="col-xl-6 col-md-6 text-center">
								<a class="fancybox-effects-d" href="<?php echo $details["image"];?>" rel="gallery">
		                            <img src="<?php echo $details["image"];?>" alt="" class="img-fluid" />
		                        </a>
							</div>
							<div class="col-xl-6 col-md-6">
								<?php echo form_open("",array("id"=>"form1","name"=>"form1","class"=>"form1"));?>
								<div class="pro-content">
									<h2>{{productDetails.name}}</h2>
									<!-- <p><small>Lorem ipsum dolor sit amet, consectetur adipisicing elit</small></p> -->
									<div class="row">
										<div class="col-xl-2 mt-2 mb-2">Quantity</div>
										<div class="col-xl-6">
											<form>
												<select name="variant_id" class="form-control" ng-options="item as item.option_name+' - '+item.price for item in productDetails.options track by item.id" ng-model="productDetails.selected_option"></select>
											</form>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col-xl-6 col-sm-7">
											 <h3><span>MRP:</span> 
											 	<span>₹{{productDetails.selected_option.old_price}}</span> 
											 	<span>₹{{productDetails.selected_option.price}}</span>
											 	<span style="color: red; font-weight:bold">{{productDetails.selected_option.disc}}% off</span>

											 </h3>
											<!-- <ul>
												<li>₹{{productDetails.selected_option.old_price}}</li>
												<li>₹{{productDetails.selected_option.price}}</li>
												<li style="color: red; font-weight:bold">{{productDetails.disc}}% off</li>
											</ul> -->
										</div>
										<div class="col-sm-5 col-xl-4">
												<div class="qty">
													<span title="Delete Product" class="minus" id="minus204"><i class="ti-minus"></i></span>
													<input type="number" class="count" id="count204" name="qty" value="1" >
													<span title="Add Product" class="plus" id="plus204"><i class="ti-plus"></i></span>
													<!-- <div class="delete"><a title="Remove" href="<?php echo base_url();?>frontend/#"><i class="ti-trash"></i></a></div> -->
												</div>
												<script>
													$(document).ready(function(){
														//$('#count'+204).prop('disabled', true);
														$(document).on('click','#plus'+204,function(){
															$('#count'+204).val(parseInt($('#count'+204).val()) + 1 );
															updtqty($('#count'+204).val(),'204');
														});
														$(document).on('click','#minus'+204,function(){
															$('#count'+204).val(parseInt($('#count'+204).val()) - 1 );
															updtqty($('#count'+204).val(),'204');
																if ($('#count'+204).val() == 0) {
																	$('#count'+204).val(1);
																}
															});
													});
												</script>
											</div>
									</div>
									
									
									<div class="main-action-wrap">
										<div class="row">
											<div class="col-sm-5">
												
												<button  class="pro-btn" type="submit"   >ADD TO BAG</button>

												<input type="hidden" name="product_id" value="<?php echo $details["product_id"];?>">
											</div>
											
										</div>
									</div>
									<div class="pro-description">
										<h3>Description</h3>
										<?php echo $details["details"];?>
									</div>
									<!-- <div class="share">
										<ul>
											<li>Share This Product : -</li>
											<li><a href="<?php echo base_url();?>frontend/#"><i class="ti-facebook"></i></a></li>
											<li><a href="<?php echo base_url();?>frontend/#"><i class="ti-twitter"></i></a></li>
											<li><a href="<?php echo base_url();?>frontend/#"><i class="ti-google"></i></a></li>
											<li><a href="<?php echo base_url();?>frontend/#"><i class="ti-instagram"></i></a></li>
										</ul>
									</div> -->
								</div>
								<?php echo form_close();?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /end product view -->

	<!-- product description 
	<section class="pro-description-main">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="pro-description">
						<ul class="nav nav-tabs" role="tablist">
							<li>
							  <a class="active" id="description-tab" data-toggle="tab" href="<?php echo base_url();?>frontend/#description" role="tab" aria-controls="description" aria-selected="false">Description</a>
							</li>
							<li>
							  <a id="details-tab" data-toggle="tab" href="<?php echo base_url();?>frontend/#details" role="tab" aria-controls="details" aria-selected="true">Details</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade active show" id="description" role="tabpanel" aria-labelledby="description-tab">
							  <?php echo $details["details"];?>
							</div>
							<div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
							   <?php echo $details["details"];?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	//end product description -->
	
	<!-- similar product view -->
	<section class="similar-product">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<h2 class="title">Similar Products <a href="<?php echo base_url("category/".$details['category_slug_name']);?>.html" class="float-right v-btn btn"><i class="fas fa-eye mr-2"></i> View All</a></h2>
				</div>
				<div class="col-xl-12">
					<div class="item-slide3 owl-carousel owl-theme products">
						
						<!-- loop start -->
						<div class="card" ng-repeat="row in productDetails.related_products" >
							<?php echo form_open("",array("id"=>"form1","name"=>"form1","class"=>"form1"));?>
							<h6>{{cat.category_name}}</h6>
							<a href="{{baseUrl}}product/{{row.slug_name}}.html"><img src="{{row.image}}" alt="product" class="img-fluid"></a>
							<h5>{{row.name}}</h5>
							<div class="mb-2">
								<select class="form-control" ng-options="item as item.option_name+' - '+item.price for item in row.options track by item.id" ng-model="row.selected_option"></select>
							</div>
							<ul>
								<li>₹{{row.selected_option.old_price}}</li>
								<li>₹{{row.selected_option.price}}</li>
								<li style="color: red; font-weight:bold">{{row.selected_option.disc}}% off</li>
							</ul>
							<div class="add-bag">
								<!-- <a href="#"><i class="ti-heart"></i></a> -->
								<button  class="add_to_cart_btn" type="button" ng-click="addToCart2('+',row,'multi_variant')"  >
									ADD TO BAG
								</button>
								<input type="hidden" name="product_id" value="{{row.product_id}}">
								<input type="hidden" name="qty" value="1">
							</div>
							<?php echo form_close(); ?>
						</div>
						<!-- loop end -->												
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //end similar product view -->
	
	<?php $this->load->view("frontend/common/footer");?>
</main>



<!-- Login Modal -->
<?php $this->load->view("frontend/common/login_modal");?>

<!-- Cart Modal -->
<?php $this->load->view("frontend/common/cart_modal");?>

<script  src="<?php echo base_url("frontend/assets/js/jquery.flexslider.js");?>"></script>
<!-- <script  src="<?php echo base_url();?>frontend/assets/js/scripts.js"></script> -->
<script  src="<?php echo base_url("frontend/assets/js/notifIt.js");?>"></script>
<!-- owl.carousel js -->
<script src="<?php echo base_url();?>frontend/assets/js/owl.carousel.min.js"></script>  
<script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/ng-infinite-scroll.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
<script>
  $(document).ready(function() {
    setInterval(function(){
  		var owl = $('.item-slide3');
	    owl.owlCarousel({
	      items:4,
	      loop:true,
	      nav:true,
	      margin: 10,
	      dots:false,
	      autoplay:false,
	      navText: ["<i class='ti-angle-left'></i>", "<i class='ti-angle-right'></i>"],
	      responsive: {
	        0: {
	          items: 1
	        }
	        , 480: {
	          items: 2
	        }
	        , 768: {
	          items: 3
	        }
	        , 1200: {
	          items: 5
	        }
	      }
	    })
  	}, 2000);
  })
</script>
<!-- fancybox Assets -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>frontend/assets/css/jquery.fancybox.css?v=2.1.5" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>frontend/assets/js/jquery.fancybox.js?v=2.1.5"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.fancybox').fancybox();
        $(".fancybox-effects-d").fancybox({
            padding: 0,
            openEffect : 'elastic',
            openSpeed  : 150,
            closeEffect : 'elastic',
            closeSpeed  : 150,
            closeClick : true,
            helpers : {
            overlay : true
            }
        });
    });
</script>
<div ng-init="getProductDetails('<?php echo $details["product_id"];?>')"></div>
</body>
</html>