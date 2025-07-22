<?php $this->load->view("frontend/common/header");?>
	
	<!-- banner part -->
	<section class="banner">
		<!-- <img src="<?php echo base_url();?>frontend/assets/images/slide.jpg" class="img-fluid"> -->
		<div class="flexslider">
			<ul class="slides">
				<?php foreach($banner_list as $banner){?>
				<li><img src="<?php echo $banner["file_path"];?>" /></li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<!-- /end banner part -->
	
	<!-- trending product -->
	<section class="trending">
		<div class="container">
			<div class="row">
				<div class="col-xl-12 text-center">
					<h2 class="title">Categories</h2>
				</div>
				<div class="col-xl-12">
					<div class="trending-pro">
						<!-- item looping start -->
						<?php //echo print_r($category_list);?>
						<?php foreach($category_list as $category_data){?>
						<div class="pro-item" >
							<a href="<?php echo base_url("category/".$category_data->slug_name.".html");?>" title="">
								<img src="<?php echo $category_data->main_image;?>" alt="pro" class="img-fluid">
								<div class="text-pt">
									<h5><?php echo $category_data->category_name;?></h5>
								</div>
							</a>
						</div>
						<?php } ?>
						<!-- item looping end -->
						<!-- <div class="pro-item">
							<a href="#" title="">
								<img src="<?php echo base_url();?>frontend/assets/images/pro2.jpg" alt="pro" class="img-fluid">
								<div class="text-pt">
									<h5>FOODGRAINS, OIL & MASALA</h5>
								</div>
							</a>
						</div>
						<div class="pro-item">
							<a href="#" title="">
								<img src="<?php echo base_url();?>frontend/assets/images/pro3.jpg" alt="pro" class="img-fluid">
								<div class="text-pt">
									<h5>EGGS, MEAT AND FISH</h5>
								</div>
							</a>
						</div>
						<div class="pro-item">
							<a href="#" title="">
								<img src="<?php echo base_url();?>frontend/assets/images/pro4.jpg" alt="pro" class="img-fluid">
								<div class="text-pt">
									<h5>PERSONAL CARE</h5>
								</div>
							</a>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //trending product -->

	<!-- product part -->
	<?php /*foreach($category_data_list as $category_data){?>
	<section class="product-main">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<h2 class="title"><?php echo $category_data["category_name"];?> <a href="<?php echo base_url("category/".$category_data['slug_name'].".html");?>" class="float-right v-btn btn"><i class="fas fa-eye mr-2"></i> View All</a></h2>
				</div>
				<div class="col-xl-12">
					<div class="item-slide3 owl-carousel owl-theme products">
						<!-- loop start -->
						<?php foreach($category_data["products"] as $pr){?>
						<?php $this->load->view("frontend/common/product_display_inside_list",array("pr"=>$pr));?>
						<?php } ?>
						<!-- loop end -->
												
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php }*/ ?>


	<section class="product-main" ng-repeat="cat in dashboardData.details" ng-if="cat.products.length>0" >
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<h2 class="title">{{cat.category_name}} <a href="{{baseUrl}}category/{{cat.slug_name}}.html" class="float-right v-btn btn"><i class="fas fa-eye mr-2"></i> View All</a></h2>
				</div>
				<div class="col-xl-12">
					<div class="item-slide3 owl-carousel owl-theme products">
						<!-- loop start -->
						
						<div class="card" ng-repeat="row in cat.products" >
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
								<li style="color: red; font-weight:bold">{{row.disc}}% off</li>
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
	
	<!-- //product part -->
	
	<!-- top brands -->
	<section class="top-charts">
		<div class="container">
			<div class="row">
				<div class="col-xl-12 text-center">
					<h2 class="title">Top Brands</h2>
				</div>
				<div class="col-xl-12">
					<div class="brands-item" >
						<?php foreach($brand_list as $brand){?>
						<div class="item"><a href="<?php echo base_url("brand/".$brand["slug_name"].".html");?>"><img src="<?php echo $brand["image"];?>" alt="" class="img-fluid"></a></div>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //top brands -->
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
				<img src="<?php echo base_url();?>frontend/assets/images/adver.jpg" alt="" class="img-fluid">
			</div>
		  </div>
		</div>
	  </div>
	</div>
</div> -->



<script src="<?php echo base_url("frontend/assets/js/jquery.mobile-menu.min.js");?>"></script>    
<!-- Banner Slider js script file-->
<script  src="<?php echo base_url("frontend/assets/js/jquery.flexslider.js");?>"></script>
<script  src="<?php echo base_url("frontend/assets/js/scripts.js");?>"></script>
<script  src="<?php echo base_url("frontend/assets/js/notifIt.js");?>"></script>
<!-- owl.carousel js -->
<script src="<?php echo base_url("frontend/assets/js/owl.carousel.min.js");?>"></script> 
<script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
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
<div ng-init="loadDashboardData()"></div>
</body>
</html>