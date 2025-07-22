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
						<li class="breadcrumb-item active" aria-current="page">My Profile</li>
					  </ol>
					</div>
				</div>
				<div class="col-xl-12 mt-2 text-center">
					<h2 class="title">My Profile</h2>
				</div>
			</div>
		</div>
	</section>
	<!-- /end inner title part -->

	<!-- my profile page -->
	<section class="container">
		<div class="row">
			<div class="col-12">
				<div class="inner-card p-xl-4 p-3 mb-5">
					<div class="row justify-content-between">
						<div class="col-xl-7">
							<h4>Profile Details</h4>
							<hr>
							<form class="mb-5">
								<div class="form-row">
								  <div class="col-md-12">
									 <div class="form-group">
										<label>Full Name</label>
										<input class="form-control" id="" name="name" ng-model="myProfileDetails.md.name" placeholder="Full Name" type="text">
									 </div>
								  </div>
								</div>
								<div class="form-row">

								<div class="col-md-6">

								 <div class="form-group">
									<label>Email ID</label>
									<input class="form-control" name="" placeholder="Email ID" type="email" ng-model="myProfileDetails.md.email" disabled="disabled" >
								 </div>
								  </div>
								  <div class="col-md-6">
									 <div class="form-group">
										<label>Phone Number</label>
										<input class="form-control" id="" name="email" placeholder="10-digit Mobile No" type="text" ng-model="myProfileDetails.md.mobile" disabled="disabled">
									 </div>
								  </div>
								 
							   </div>
							   
							 
							  <div class="text-center">
							  	<!-- <button type="submit" class="btn btn-success">Edit</button> -->
							  	<button type="button" ng-click="updateProfileData()" class="btn btn-info">Update</button>
							  </div>
							</form>
							<h4>Change Password</h4>
							<hr>
							<?php form_open("",array("id"=>"changePasswordForm"));?>
								<div class="form-group row">
								    <label class="col-sm-4">Old Password</label>
								    <div class="col-sm-8">
								      <input type="password" class="form-control" id="current_password" name="current_password" ng-model="current_password"  placeholder="Old Password">
								    </div>
								</div>
								<div class="form-group row">
								    <label class="col-sm-4">New Password</label>
								    <div class="col-sm-8">
								      <input type="password" class="form-control" id="new_password" name="new_password" ng-model="new_password"
								      placeholder="New Password">
								    </div>
								</div>
								<div class="form-group row">
								    <label class="col-sm-4">Confirm New Password</label>
								    <div class="col-sm-8">
								      <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm New Password" ng-model="confirm_new_password">
								    </div>
								</div>
								<div class="text-center">
								  	<!-- <button type="submit" class="btn btn-success">Edit</button> -->
								  	<button type="button" ng-click="updatePassword()" class="btn btn-info">Update</button>
								</div>
							<?php echo form_close();?>
						</div>
						<div class="col-xl-4">
							<div class="inner-card mb-3">
								<div class="inner-card-header">
									<div class="card-title mr-4">
										<i class="ti-wallet mr-3"></i> Wallet Balance
									</div>
								</div>
								<?php echo form_open("Home/add_wallet_money",array("id"=>"walletMoneyForm"));?>
								<div class="inner-body">
									<div class="pricedetails">
										<p>Current Balance<span class="ml-auto">₹{{myProfileDetails.wallet_balance}}</span></p>
										<div class="form-inline mt-2">
										  <div class="form-group">
											<input type="text" name="amt" class="form-control" id="walletAddAmt" placeholder="Add Money to Wallet" Required ng-keypress="filterNonNumericValue($event)" >
										  </div>
										  <button type="button" ng-click="addWalletMoney()"  class="btn btn-success ml-auto">ADD</button>
										  <button style="display: none;" type="submit" id="walletAddBtn">Add Hidden</button>
										</div>
									</div>
								</div>
								<?php echo form_close();?>
							</div>


							<div class="inner-card mb-3">
								<div class="inner-card-header">
									<div class="card-title mr-4">
										<i class="ti-wallet mr-3"></i> Refer & Earn 
									</div>
								</div>
								<div class="inner-body">
									<div class="pricedetails">
										<p>
											Your Referral Code
											<span class="ml-auto">{{myProfileDetails.md.own_referral_code}}</span>
										</p>
										<!-- <div class="form-inline mt-2">
										  <div class="form-group">
											<input type="text" class="form-control" id="" placeholder="Add Money to Wallet">
										  </div>
										  <button type="submit" class="btn btn-success ml-auto">ADD</button>
										</div> -->
									</div>
								</div>
							</div>




						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //end my profile page -->

	<!-- app part -->
	<?php $this->load->view("frontend/common/footer");?>
</main>
<!-- Login Modal -->
<?php $this->load->view("frontend/common/login_modal");?>
<!-- Cart Modal -->
<?php $this->load->view("frontend/common/cart_modal");?>

<script src="assets/js/jquery.mobile-menu.min.js"></script>    
<script  src="assets/js/scripts.js"></script>
<script  src="<?php echo base_url("frontend/assets/js/notifIt.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/ng-infinite-scroll.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/common.js");?>"></script> 
<div ng-init="myProfileData()"></div>
</body>
</html>