<?php echo form_open("",array("id"=>"loginForm"));?>
	<input type="text" name="mobile" id="fp_mobile" class="form-control"  placeholder="MOBILE NO" required>
	<a ng-click="changeLoginFormState(1)" href="#"><p class="text-right">Back To Login?</p></a>
	<button type="button" ng-click="forgotPassword()" class="log-btn mt-2">SUBMIT</button>
	<input style="display:none;" type="submit" value="submit" name="SubmitBtn" id="signSubmitBtn">
<?php echo form_close();?>
