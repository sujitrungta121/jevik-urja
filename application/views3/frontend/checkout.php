<?php $this->load->view("frontend/common/header");?>
	
	<!-- inner title part -->
	<section class="inner-title">
		<img src="<?php echo base_url("frontend/assets/images/trns-divider.png");?>" class="img-fluid">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div aria-label="breadcrumb">
					  <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Check Out</li>
					  </ol>
					</div>
				</div>
				<div class="col-xl-12 mt-2 text-center">
					<h2 class="title">Check Out</h2>
				</div>
			</div>
		</div>
	</section>
	<!-- /end inner title part -->

	<?php echo form_open("Home/before_order_complete",array("id"=>"checkoutForm"));?>
	<!-- my profile page -->
	<section class="container mb-5"  >
		
			<h3 ng-if="cartDetails.length==0" align="center">
				The Cart is Empty <br/>
				<a href="<?php echo base_url();?>"><button type="button" class="btn btn-success">Back To Home</button></a>

			</h3>
	
		<div class="row" ng-if="cartDetails.length>0">
			<div class="col-xl-8">
				<div class="accordion" id="accordionExample">

				  <!-- Delivery Address -->
				  <div class="inner-card mb-3">
				  	<div class="checklist" id="headingOne">
				  		<a id="accordionLink1" href="javascript:void(0)" class="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="ti-location-pin"></i> Delivery Address</a>
				  	</div>
				    <div id="collapseOne" class="collapse checkdetails show" aria-labelledby="headingOne" data-parent="#accordionExample">
				    	<!-- <form class="p-3" id="addressForm"> -->
				    	  <div class="row">
				    	  	<div class="col-12" ng-repeat="row in addressListData"  >
				    	  		<div class="p-2 bg-white mb-2" >
				    	  			<div class="custom-control custom-radio">
									  <input ng-if="row.pin_code_available!='0'" type="radio" ng-click="setAddress(row.id)"  id="da{{$index}}" name="customRadio" class="custom-control-input" value="{{row.id}}" ng-checked="row.id==address_id">
									  <label class="custom-control-label" for="da{{$index}}">
									  <h6><b>{{row.name}} {{row.mobile_no}}</b> 
									  	<!-- <a class="float-right" href="javascript:void(0)">Edit</a> -->
									  </h6>
									  <p class="m-0">
									  	{{row.address}}<br/>
									  	Pin Code : {{row.pin_code}}

									  </p>
									  </label>
									</div>
				    	  		</div>
				    	  	</div>
				    	  	<div ng-if="checkout.address_required_msg!=''" style="color:red; padding:10px;">
				    	  		{{checkoutMsg.addressRequired}}
				    	  	</div>
				    	  	<!-- <div class="col-12">
				    	  		<div class="p-2 bg-white mb-2">
				    	  			<div class="custom-control custom-radio">
									  <input type="radio" id="da2" name="customRadio" class="custom-control-input">
									  <label class="custom-control-label" for="da2">
									  <h6><b>Subrata Poria 9800494839</b></h6>
									  <p class="m-0">32 BN Road, Sodepur, Kolkata, Sonarpur, West Bengal - 700145</p>
									  </label>
									</div>
									<button type="submit" class="btn pro-btn px-4 ml-4 mt-3">DELIVERY HERE</button>
				    	  		</div>
				    	  	</div> -->
				    	  </div>
				    	  <div id="addressFormArea" style="display: none;">
							  <div class="form-row">
							    <div class="col-6">
							      <input type="text" name="name" class="form-control" placeholder="Name">
							    </div>
							    <div class="col-6">
							      <input type="text" name="mobile_no" class="form-control" placeholder="10-digit Mobile No">
							    </div>
							  </div>
							  <div class="form-row">
							    <div class="col-6">
							      <input type="text" name="pin_code" id="pin_code" class="form-control" placeholder="Pincode">
							    </div>
							    <div class="col-6">
							      <input name="locality" type="text" class="form-control" placeholder="Locality">
							    </div>
							  </div>
							   <div class="form-row">


							   	<div class="col-6">
							      <select name="state_id" class="form-control">
							      	<option value="">Select State</option>
							      	<?php foreach($state_list as $row){?>
							      	<option value="<?php echo $row["id"];?>"><?php echo $row["name"];?></option>
							      	<?php } ?>
							      </select>
							    </div>

							    <div class="col-6">
							      <input type="text" name="city" class="form-control" placeholder="City/Distric/Town">
							    </div>

							  </div>
							  <div class="form-row">
							    <div class="col-12">
							      <textarea name="address" class="form-control" rows="3" placeholder="Address"></textarea>
							    </div>
							  </div>
							 
							  <div class="form-row">
							    <div class="col-12">
							      <input type="text" name="landmark" class="form-control" placeholder="Landmark (Optional)">
							    </div>
							  </div>
							  <button type="button" ng-click="saveAddress()" class="btn pro-btn px-4">SAVE AND DELIVERY HERE</button>
						  </div>
						  <a href="#" onclick="return false;" class="btn btn-default" ng-click="openAddressForm()">+ Add a new Address</a>
						
				    </div>
				  </div>

				  <!-- Product Overview -->
				  <div class="inner-card mb-3">
				  	<div class="checklist" id="headingTwo">
				  		<a href="javascript:void(0)" class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"><i class="ti-list"></i> Order Summary</a>
				  	</div>
				    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
				      <div class="p-3">
				      	<div ng-repeat="row in cartDetails">
							<div class="row justify-content-center align-items-center">
								<div class="col-xl-2 col-md-2 col-sm-3 col-6">
									<img src="{{row.image}}" alt="nail" class="img-fluid">
								</div>
								<div class="col-xl-7 col-md-7 col-sm-6 col-7">
									<p>{{row.product_name}}  ({{row.variant_name}} x {{row.count}} )</p>
									<!-- <div class="qty">
										<span title="Delete Product" class="minus" id="minus205"><i class="ti-minus"></i></span>
										<input type="number" class="count" id="count205" name="qty" value="1" disabled="">
										<span title="Add Product" class="plus" id="plus205"><i class="ti-plus"></i></span>
									</div> -->
									<div class="qty">
										<span ng-click="addToCart2('-',row)" title="Delete Product" class="minus" id="minus204"><i class="ti-minus"></i></span>
										<input type="number" class="count" id="count204" name="qty" value="{{row.count}}" disabled="">
										<span ng-click="addToCart2('+',row)" title="Add Product" class="plus" id="plus204"><i class="ti-plus"></i></span>
									</div>
								</div>
								<div class="col-xl-3 col-md-3 col-sm-3 col-5">
									<div class="checkitem">
										<span>₹{{row.price*row.count}}</span>
										<p class="my-2"><!-- 0% --> <span>₹{{row.old_price*row.count}}</span></p>
										<!-- <span>GP 100</span> -->
										<a ng-click="removeProductFromCart(row)" href="#">Remove <i class="ti-trash"></i></a>
									</div>
								</div>
							</div>
							<hr>
						</div>
					  </div>
				    </div>
				  </div>

				  <!-- Payment Option -->
				  <div class="inner-card mb-3">
				  	<div class="checklist" id="headingThree">
				  		<a id="accordionLink3" href="javascript:void(0)" class="collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree"><i class="ti-list"></i> Payment Option</a>
				  	</div>
				  	<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
						<div class="p-3">
						
				    	<hr>
				    	<div class="row">
				    	  	<div class="col-12">
				    	  		<div class="p-2 bg-white">
				    	  			
									
									<div  class="ml-4">
										<div ng-if="multiPaymentDisplay==''">
											<h5 class="mt-3 mb-2">Payment Mode</h5>
											<div class="custom-control custom-radio custom-control-inline">
											  <input ng-change="checkWalletBalanceSufficiency()" type="radio" id="customRadio1" ng-click="checkWalletBalance()" name="payment_mode" ng-model="payment_mode" class="custom-control-input" ng-value="1" >
											  <label class="custom-control-label" for="customRadio1">Pay By Wallet</label>
											</div>
											<div class="custom-control custom-radio custom-control-inline">
											  <input ng-change="checkoutBtnActivate()" ng-model="payment_mode" type="radio" id="customRadio2"  name="payment_mode"class="custom-control-input" ng-value="2">
											  <label class="custom-control-label" for="customRadio2">CASH ON DELIVERY</label>
											</div>
											<div class="custom-control custom-radio custom-control-inline">
											  <input ng-change="checkoutBtnActivate()" ng-model="payment_mode" type="radio" id="customRadio3"  name="payment_mode"class="custom-control-input" ng-value="3">
											  <label class="custom-control-label" for="customRadio3">Pay By Card</label>
											</div>

											<input ng-if="getGrandTotal()<500" type="hidden" name="payment_amt" value="{{getGrandTotal()+35}}">
											<input ng-if="getGrandTotal()>499" type="hidden" name="payment_amt" value="{{getGrandTotal()}}">

										</div>
										<div ng-if="multiPaymentDisplay!=''">
											<input type="hidden" name="payment_mode" id="multi_payment_mode" value="{{multi_payment_mode}}" >
											<input type="hidden" name="payment_amt" value="{{multi_payment_amt}}">
											<span ng-show="multiPaymentDisplay!=''">{{multiPaymentDisplay}}</span>

											<button type="button" ng-show="multiPaymentDisplay!=''" ng-click='removeMultiPayMode()' class="btn btn-danger">Remove</button>
										</div>


										<h5 class="mt-3 mb-2">Delivery Timming Slot</h5>
										<div class="custom-control custom-control-inline">
										  <select class="form-control" name="delivery_timming" >
										  	<?php 
										  	$inactive=array();
										  	if(strtotime(date('H:i:s')) > strtotime(date('15:00:00'))){
										  		$inactive=array(1);
										  	} ?>
										  	<?php foreach($delivery_timming_list as $row){?>
										  		<option
										  	<?php if(in_array($row["id"], $inactive)){ echo ' disabled' ;}?>
										  		 value="<?php echo $row["id"];?>"><?php echo $row["name"];?></option>
										  	<?php }?>
										  </select>
										  
										</div>



										<input type="text" style="display: none;" name="address_id" ng-model="address_id"  >
										<button type="button" ng-disabled="checoutSubmitBtnActive==0" ng-click="validateCheckoutForm()" class="btn pro-btn mt-3 px-4">CHECKOUT</button>

										<input type="submit" name="checkoutSubmitBtn" value="submit" style="display: none;" >
										
										<div ng-if="checkoutMsg.payModeRequired!=''" style="color:red; padding:10px;">
				    	  						{{checkoutMsg.payModeRequired}}
				    	  				</div>
									</div>	
												
				    	  		</div>
				    	  	</div>
				    	</div>
						
						
						
						</div>
				  	</div>
				  </div>
				  
				</div>
			</div>
			<div class="col-xl-4">
				<div class="inner-card mb-5">
					<div class="inner-card mb-3">
						<div class="inner-card-header">
							<div class="card-title mr-4">
								<i class="ti-list mr-3"></i> Price Details
							</div>
						</div>
						<div class="inner-body">
							<div class="pricedetails">
								<p>Sub Total <span class="ml-auto">₹{{getGrandTotal()}}</span></p>
								<p>Shipping Free 
									<span ng-if="getGrandTotal()<500" class="ml-auto">₹35</span>
									<span ng-if="getGrandTotal()>499" class="ml-auto">₹0</span>
								</p>
								<!-- <p>Total Gain Point <span class="ml-auto">200</span></p> -->
								<p><span>Amount Payable</span> 
									<span ng-if="getGrandTotal()<500" class="ml-auto">₹{{getGrandTotal()+35}}</span>
									<span ng-if="getGrandTotal()>499" class="ml-auto">₹{{getGrandTotal()}}</span>

								</p>
								<!-- <div class="form-inline mt-2">
								  <div class="form-group">
									<input type="text" class="form-control" id="" placeholder="Enter Promo Code">
								  </div>
								  <button type="submit" class="btn ml-auto">APPLY</button>
								</div> -->
							</div>
							<!-- <p class="text-center mt-3">Your Total Saving on this order ₹199</p> -->
							<a href="#" ng-click="validateCheckoutForm();"  ng-if="checoutSubmitBtnActive==1"  data-toggle="modal" data-target="#checkout" class="pro-btn">Checkout</a>
							<input ng-if="getGrandTotal()<500" type="hidden" name="grand_total" value="{{getGrandTotal()+35}}">
							<input ng-if="getGrandTotal()>499" type="hidden" name="grand_total" value="{{getGrandTotal()}}">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php echo form_close();?>
	<!-- //end my profile page -->

	<?php $this->load->view("frontend/common/footer");?>
</main>

<!-- Login Modal -->
<?php $this->load->view("frontend/common/login_modal");?>

<!-- Cart Modal -->
<?php $this->load->view("frontend/common/cart_modal");?>


<!-- Checkout modal 
<div class="checkout">
	<div class="modal fade" id="checkout" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="row">
				
				<div class="col-12 mb-4 text-center">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">×</span>
					</button>
					<h4 class="font-weight-bold">SELECT DEALER</h4>
					<hr>
					<h5>Head Quarter (Company)</h5>
					<p class="my-2">C/5A Ramkrishna Upanibesh, Jadavpur, Kolkata - 700092</p>
					<p class="m-0">Mobile No: 01777565956 / Email ID: info@gomart16.com</p>
					<h5 class="mb-2 mt-3">Payment Mode</h5>
					<form>
					<div class="mb-3">
						<div class="custom-control custom-radio custom-control-inline">
						  <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
						  <label class="custom-control-label" for="customRadio1">Pay By Wallet</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
						  <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
						  <label class="custom-control-label" for="customRadio2">COD</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
						  <input type="radio" id="customRadio3" name="customRadio" class="custom-control-input">
						  <label class="custom-control-label" for="customRadio3">Pay By Card</label>
						</div>
					</div>
					<button type="submit" class="btn btn-success">SUBMIT</button>
					</form>
				</div>				
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
<script src="<?php echo base_url("frontend/assets/js/ng-infinite-scroll.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
<div ng-init="getAddressList()"></div>
</body>
</html>