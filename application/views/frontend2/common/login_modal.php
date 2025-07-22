<div class="login">
	<div class="modal fade" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="col-12 mb-4 text-center">
				<h5>SIGN IN</h5>
				<p>Not Registered? Sign Up</p>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="login-main">
				<div class="login-item text-center">
					<?php echo form_open("",array("id"=>"loginForm"));?>
						<input type="text" name="user_name" class="form-control" placeholder="EMAIL OR MOBILE NO" required>
						<input type="password" name="password" class="form-control" placeholder="PASSWORD" required>
						<p class="text-right">Forgot Password?</p>
						<button type="button" ng-click="submitLoginData()" class="log-btn mt-2">SIGN IN</button>
						<input style="display:none;" type="submit" value="submit" name="SubmitBtn" id="signSubmitBtn">
					<?php echo form_close();?>
				</div>
				<div class="login-item text-center">
					<?php echo form_open("Account/registration",array("id"=>"regisForm"));?>
						<div id="signup_fields_area">
							<input type="text" name="name" class="form-control" required placeholder="NAME">
							<!-- <input type="text" class="form-control" required placeholder="LAST NAME"> -->
							<input type="email" name="email" class="form-control" placeholder="EMAIL" >
							<input type="password" name="password" class="form-control" required placeholder="PASSWORD">
							<input type="text" id="su_mobile" name="mobile"  pattern="[0-9]{10}" title="Enter Valid 10 digit mobile number" class="form-control" required placeholder="MOBILE">
							<input type="text" name="referral_code" class="form-control"  placeholder="REFFERER CODE">
							<!-- <div>
								<div class="custom-control custom-radio custom-control-inline">
								  <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input" checked>
								  <label class="custom-control-label" for="customRadioInline1">MALE</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
								  <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input">
								  <label class="custom-control-label" for="customRadioInline2">FEMALE</label>
								</div>
							</div> -->
							<small>By Signing up you will agree Privacy Policy and Terms of Conditions.</small>
							<button type="button" ng-click="sendOTP();" class="log-btn mt-3">SIGN UP</button>
							<input style="display:none;" type="submit" value="submit" name="regisSubmitBtn" id="regisSubmitBtn">
						</div>

						<div id="otp_area" style="display: none;">
							<div>
								<h4>OTP Has Been Sent To Your Mobile Number. Please Check And Enter It Below</h4>
							</div>
							<input type="text" name="otp" id="otp" class="form-control" placeholder="Enter OTP Here" required>
		
							<button type="button" ng-click="verifyOTP()" class="log-btn mt-2">Submit OTP</button>
							<input style="display:none;" type="submit" value="submit" name="SubmitBtn" id="signSubmitBtn">
						</div>
					<?php echo form_close();?>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
</div>