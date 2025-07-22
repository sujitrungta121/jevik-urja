<?php echo form_open("",array("id"=>"loginForm"));?>
<input type="text" name="user_name" class="form-control" placeholder="EMAIL OR MOBILE NO" required>
<input type="password" name="password" class="form-control" placeholder="PASSWORD" required>
<a ng-click="changeLoginFormState(2)" href="#"><p class="text-right">Forgot Password?</p></a>
<button type="button" ng-click="submitLoginData()" class="log-btn mt-2">SIGN IN</button>
<input style="display:none;" type="submit" value="submit" name="SubmitBtn" id="signSubmitBtn">
<?php echo form_close();?>