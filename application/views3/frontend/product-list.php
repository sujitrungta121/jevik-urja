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
						<li class="breadcrumb-item active" aria-current="page">
							<?php
							echo "type : ".$type;
							if($type=="category"){
								echo $main_category_data["category_name"];
							}else{
								echo "Brand List";
							}
							 ?>
							
								
						</li>
					  </ol>
					</div>
				</div>
				<div class="col-12 mt-3">
					<?php if($type=="category"){?>
					<img src="<?php echo $main_category_data["banner_image"];?>" alt="" class="img-fluid">
					<?php }else{?>
					<img src="<?php echo base_url("frontend/");?>assets/images/slide.jpg" alt="" class="img-fluid">
					<?php } ?>
				</div>
			</div>
		</div>
	</section>
	<!-- /end banner part -->

	<!-- product part -->
	<?php echo form_open("Api/filterProductList",array("id"=>"productListFilterForm"));?>
	<section class="product-main">
		<div class="container">
			<div class="row">
				<div class="col-xl-3">
					<?php if($type=="category"){?>
					<div class="fillter">
						<div class="title-pt">Category</div>
						<div class="position-relative form-group">
							<?php echo $main_category_data["category_name"];?>
							
							<input type="hidden" name="category_id[]" value="<?php echo $main_category_data["category_id"];?>">
							
							<?php $i=0; foreach($filter_category_data as $row){$i++;?>
							<div class="custom-checkbox custom-control">
								<!-- <input ng-click="filterProductList();" class="custom-control-input" checked="checked" name="category_id[]" id="category<?php echo $i;?>" type="checkbox" value="<?php echo $row["category_id"];?>">
								<label class="custom-control-label" for="category<?php echo $i;?>">
									<?php echo $row["name"];?>
								</label> -->
								<input type="checkbox" ng-click="filterProductList();" name="category_id[]" type="checkbox"  value="<?php echo $row["category_id"];?>"> <?php echo $row["name"];?>
							</div>
							<?php } ?>
							<!-- <input type="hidden" name="category_id[]" value="-1"> -->
						</div>

					</div>
					<?php } ?>




					<?php if($type=="brand"){?>
					<div class="fillter">
						<div class="title-pt">Brands</div>
						<div class="position-relative form-group">
							
							<?php $i=0; foreach($brand_list as $row){$i++;?>
							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="brand_id[]" type="checkbox" <?php if($id==$row["id"]){ echo 'checked="checked"';} ?> value="<?php echo $row["id"];?>"> <?php echo $row["name"];?>
							</div>
							<?php } ?>
							<input type="hidden"  name="brand_id[]" value="-1" >
						</div>

					</div>
					<?php } ?>




					<div class="fillter">
						<div class="title-pt">Discount</div>
						<div class="position-relative form-group">

							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="discount[]"  value="1-5"> Upto 5%
							</div>
							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="discount[]"  value="6-10"> 6% To 10%
							</div>
							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="discount[]"  value="11-20"> 11% To 20%
							</div>

							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="discount[]"  value="21-100"> 21% To 100%
							</div>

							<!-- <div class="custom-checkbox custom-control">
								<input class="custom-control-input" id="exampleCustomCheckbox" type="checkbox" name="discount[]" value="0-5">
								<label class="custom-control-label"  for="exampleCustomCheckbox">Upto 5%</label>
							</div>
							<div class="custom-checkbox custom-control">
								<input class="custom-control-input" id="exampleCustomCheckbox2" type="checkbox" name="discount[]" value="6-10">
								<label class="custom-control-label" for="exampleCustomCheckbox2">6% - 10%</label>
							</div>
							<div class="custom-checkbox custom-control">
								<input class="custom-control-input" id="exampleCustomCheckbox3" type="checkbox" name="discount[]" value="11-20">
								<label class="custom-control-label" for="exampleCustomCheckbox3">11% - 20%</label>
							</div>
							<div class="custom-checkbox custom-control">
								<input class="custom-control-input" id="exampleCustomCheckbox4" type="checkbox" name="discount[]" value="21-100">
								<label class="custom-control-label" for="exampleCustomCheckbox4">More than 20%</label>
							</div> -->
						</div>
					</div>

					<div class="fillter">
						<div class="title-pt">Price</div>
						<div class="position-relative form-group">
							<div class="custom-checkbox custom-control">
								<!-- <input class="custom-control-input" id="exampleCustomCheckbox5" type="checkbox" name="price[]" value="0-20">
								<label class="custom-control-label" for="exampleCustomCheckbox5">Less than Rs 20</label> -->
								<input type="checkbox" ng-click="filterProductList();" name="price[]"  value="0-20"> Less than Rs 21
							</div>
							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="price[]"  value="21-50"> Rs 21 to Rs 50
							</div>
							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="price[]"  value="51-100"> Rs 51 to Rs 100
							</div>
							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="price[]"  value="101-500"> Rs 101 to Rs 500
							</div>
							<div class="custom-checkbox custom-control">
								<input type="checkbox" ng-click="filterProductList();" name="price[]"  value="501-10000000">Above 500
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-9">
					<h2 class="title">
						<?php
							if($type=="category"){
								echo $main_category_data["category_name"];
							}else{
								echo "Product List";
							}
							 ?>
				
							<select ng-change="filterProductList();" ng-model="order_by" name="order_by" class="form-control float-right" style="display: inline-block; width: auto;">
								<option value="" selected="selected">Default sorting</option>
								<!-- <option value="popularity">Sort by popularity</option>
								<option value="rating">Sort by average rating</option> -->
								<!-- <option value="date">Sort by latest</option> -->
								<option value="price-asc">Sort by price: low to high</option>
								<option value="price-desc">Sort by price: high to low</option>
						</select>

					</h2>
				<?php echo form_close();?>

					
					<!-- loop start -->
					
						<div class="products" infinite-scroll='filterProductList(filterOffset)'>
						
						<div class="card-inner" ng-repeat="row in products">
							<?php  echo form_open("",array("id"=>"form1","name"=>"form1","class"=>"form1"));?>
							<h6>{{row.category_name}}</h6>
							<a href="{{baseUrl}}product/{{row.slug_name}}.html"><img src="{{row.image}}" alt="product" class="img-fluid"></a>
							<h5>{{row.name}}</h5>
							<div class="mb-2">
								<!-- <select class="form-control" name="variant_id" ng-model="row.variant_id" > -->
									<select class="form-control" ng-options="item as item.option_name+' - '+item.price for item in row.options track by item.id" ng-model="row.selected_option"></select>
									<!-- <option ng-selected="opt.id=row.variant_id"  ng-repeat="opt in row.options" ng-value="opt.id">
										{{opt.option_name}} - 
										₹{{opt.price}}
									</option> -->
								</select>
							</div>
							<ul>
								<li>₹{{row.selected_option.old_price}}</li>
								<li>₹{{row.selected_option.price}}</li>
								<li style="color: red; font-weight:bold">{{row.selected_option.disc}}% off</li>
							</ul>
							<div class="add-bag">
								<!-- <a href="#"><i class="ti-heart"></i></a> -->
								<button  class="add_to_cart_btn" type="button" ng-click="addToCart2('+',row,'multi_variant')"  >ADD TO BAG</button>
								<input type="hidden" name="product_id" value="{{row.product_id}}">
								<input type="hidden" name="qty" value="1">
							</div>
							<?php  echo form_close();?>
						</div>
						<div ng-if="filterWait==1" align="center" style="width:100%" >
							<img src="<?php echo base_url("frontend/assets/images/ajax-loader-in-list.svg");?>"	>
						</div>
						<div ng-if="filterMsg !== undefined && filterMsg!=''" class="alert alert-info" style="width:100%">
						  {{filterMsg}}
						</div>

					</div>
					
					<!-- loop end -->	
				</div>
			</div>
		</div>
	</section>
	<!-- //product part -->

	
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
				<img src="<?php echo base_url("frontend/");?>assets/images/adver.jpg" alt="" class="img-fluid">
			</div>
		  </div>
		</div>
	  </div>
	</div>
</div> -->



<script src="<?php echo base_url("frontend/");?>assets/js/jquery.mobile-menu.min.js"></script> 
<script  src="<?php echo base_url("frontend/assets/js/notifIt.js");?>"></script>   

<script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/ng-infinite-scroll.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
	var queryString="<?php echo get_url_query_string();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
<script  src="<?php echo base_url("frontend/");?>assets/js/scripts.js"></script>
<div ng-init="filterProductList()"></div>
</body>
</html>