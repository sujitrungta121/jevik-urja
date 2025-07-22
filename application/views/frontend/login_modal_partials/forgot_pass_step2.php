<?php echo form_open("",array("id"=>"resetPasswordForm"));?>
	<input type="text" name="otp" id="fp_reset_code" class="form-control" placeholder="Password Reset Code" required>
	<input type="password" name="new_password" id="fp_password" class="form-control" placeholder="PASSWORD" required>
	<input type="password" name="confirm_password" id="fp_confirm_password" class="form-control" placeholder="CONFIRM PASSWORD" required>
	
	<button type="button" ng-click="resetPasswordFormSubmit()" class="log-btn mt-2">Reset Password</button>
	<input style="display:none;" type="submit" value="submit" name="SubmitBtn" id="signSubmitBtn">
<?php echo form_close();?>