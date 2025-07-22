var app = angular.module('myApp', ['infinite-scroll']);
angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 250);
app.controller('Ctrl', function($scope, $http,$timeout) {
	$scope.cartItemCounter=0;
	$scope.cartDetails=[];
	$scope.products=[];
	$scope.dashboardData=[];
	$scope.baseUrl=baseUrl;
	$scope.checoutSubmitBtnActive=1;
	$scope.loginFormState=1;
	//alert("dfasdfas");

	$scope.changeLoginFormState=function(state=1){
		$scope.loginFormState=state;
	}


	$scope.forgotPassword=function(){
		var data = $.param({
		 	mobile 	: $("#fp_mobile").val(),
        });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
		//alert("jhjhgj");
        $http.post(baseUrl+"Api/forgotPassword/web",data,config).success(function(response){
            if(response.status == 'success'){
            	$("#fp_mobile2").val($("#fp_mobile").val());
                $scope.loginFormState=3;
                
            }else{
				console.log(response.msg);
				alert(response.msg);
			}
			
        });
	}

	$scope.resetPasswordFormSubmit=function(){
		var data =$.param({
		 	otp 	: $("#fp_reset_code").val(),
		 	mobile 	: $("#fp_mobile2").val(),
		 	new_password	: $("#fp_password").val(),
		 	confirm_password 	: $("#fp_confirm_password").val(),
        });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
		//alert("jhjhgj");
        $http.post(baseUrl+"Api/resetPassword",data,config).success(function(response){
            if(response.status == 'success'){
            	alert(response.msg);
                $scope.loginFormState=1;
            }else{
				console.log(response.msg);
				alert(response.msg);
			}
			
        });
	}



	$scope.notify=function(msg,type="success"){
		//https://www.jqueryscript.net/other/Simple-Easy-jQuery-Notification-Plugin-NotifIt.html
		notif({
			msg: msg,
			type: type,
			position : "center"
		});
	}
	$scope.loadCartDetails=function(){
		//alert("sdasdas");
		$http.get(baseUrl+"/Api/cartDetails")
	    .then(function (response) {
			//alert(response);
			$scope.cartDetails = response.data.list;
		});
	}
	$scope.loadCartDetails();

	$scope.mobileExist=function(){
		var mobile_no=$("#su_mobile").val();
		$http.get(baseUrl+"Api/mobileNoExist/?mobile_no="+mobile_no)
	    .then(function (response) {
			//alert(response);
			if(response.data.status_code==1){
				
			}
		});
	}
	$scope.OTPTimer=120;
    $scope.startTimeout = function () { 
       if($scope.OTPTimer==0){
       	$scope.stopTimeout();
       } 
       if($scope.OTPTimer==0){return}
       $scope.OTPTimer--;
       
       $scope.mytimeout = $timeout($scope.startTimeout, 1000);  
    }

    $scope.stopTimeout = function () {  
        $timeout.cancel($scope.mytimeout);  
        //alert("Timer Stopped");  
    }

    $scope.resendOTP=function(){
    	var mobile_no=$("#su_mobile").val();
    	$http.get(baseUrl+"Api/sendOTP/?mobile_no="+mobile_no).then(function (response) {
			//alert(response);
			if(response.data.status=="success"){
				$scope.notify("Otp Has Been Sent To "+mobile_no);
			}else{
				$scope.notify("Unable To Send Otp... Please Try Again","warning");
			}
			$scope.OTPTimer=120;
			$scope.startTimeout();

		});
    }    

	$scope.sendOTP=function(){
		//alert("sdasdas");
		var mobile_no=$("#su_mobile").val();
		console.log("url : "+baseUrl+"Api/sendOTP/?mobile_no="+mobile_no);
		$http.get(baseUrl+"Api/sendOTP/?mobile_no="+mobile_no)
	    .then(function (response) {
			//alert(response);
			if(response.data.status=="success"){
				$("#signup_fields_area").hide();
				$("#otp_area").show();
				$scope.startTimeout();
			}
		});
	}


	$scope.verifyOTP=function(){
		var data = $.param({
		 	mobile_no 	: $("#su_mobile").val(),
		 	otp_code	: $("#otp").val(),
        });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
		//alert("jhjhgj");
        $http.post(baseUrl+"Api/verifyOTP/",data,config).success(function(response){
            if(response.status == 'success'){
                $scope.submitRegisData("form-submit");
            }else{
				console.log(response.msg);
				alert(response.msg);
			}
			
        });
	}

	$scope.loadDashboardData=function(){
		//alert("sdasdas");
		$http.get(baseUrl+"Api/dashboard/web")
	    .then(function (response) {
			//alert(response);
			console.log(JSON.stringify(response));

			$scope.dashboardData = response.data;
			//alert(JSON.stringify($scope.dashboardData.details));
		});
	}

	$scope.getGrandTotal=function(){
		var total=0;
		angular.forEach($scope.cartDetails, function(row, key) {
		  total+=row.price*row.count;
		});
		return total
	}
	$scope.multiPaymentDisplay="";
	$scope.multi_payment_mode="1,3";
	$scope.multi_payment_amt="";
	$scope.checkWalletBalanceSufficiency=function(){
		//alert("sdfadfasd");
		$http.get(baseUrl+"Api/WalletBalance")
	    .then(function (response) {
			//alert(response);
			var balance = response.data.balance;
			if(balance==0){
				alert("You Do Not Have Any Balance In Your Wallet... You Can Recharge Your Wallet From My Profile Section");
				return;
			}
			if($scope.getGrandTotal()<500){
				var grandTotal=$scope.getGrandTotal()+35;
			}else{
				var grandTotal=$scope.getGrandTotal();
			}
			
			console.log("balance : "+balance);
			console.log("grand Total : "+grandTotal);



			if(balance<grandTotal){
				var restAmount=grandTotal-balance;
				
				//alert("You Do Not Have Sufficient Balance In Your Wallet. Current Balance Rs."+balance);

				var res=confirm("You Have "+balance+" Rupees in Your Wallet. If You Want To Pay Rest of The Amount Which is "+restAmount+" Rupees By Online Payment Then Click/Tap On Ok Button");
				if(res){
					console.log("Need To Select Multiple");
					$scope.multiPaymentDisplay="From Wallet  "+balance+" Rupees And From Online Payment "+restAmount+" Rupees";
					$scope.multi_payment_amt=balance+","+restAmount;
				}else{
					$scope.checoutSubmitBtnActive=0;
				}
				
			}

		});
	}

	$scope.removeMultiPayMode=function(){
		$scope.multiPaymentDisplay="";
		$('input:radio[name="payment_mode"]').attr('checked',false);
		$scope.checoutSubmitBtnActive=0;
	}

	$scope.checkoutBtnActivate=function(){
		$scope.checoutSubmitBtnActive=1;
	}


	$scope.searchList=[];
	$scope.search_val="";

	$scope.searchProduct=function(){
		var data = $.param({
			 	search_val 	: $scope.search_val,
            });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
		//alert("jhjhgj");
        $http.post(baseUrl+"Api/search/",data,config).success(function(response){
            if(response.status == 'success'){
            	$("#searchResultArea").show();
                $scope.searchList=response.list;
            }else{
				console.log(response.msg);
			}
			
        });
	}


	$scope.submitRegisData=function(purpose){
		if($('#regisForm')[0].checkValidity()==false){
			$("#regisSubmitBtn").click();
			 $('#regisForm').find(':submit').click();
			 return;
		}
		//var postVars= "name:"+$scope.name+","+tokenName+":"+token+"";
            var data =$("#regisForm").serialize();
			var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
			//alert("jhjhgj");
            $http.post(baseUrl+"Account/registration/"+purpose,data,config).success(function(response){
                if(response.status == 'success'){
                	if(purpose=="validation-check"){
                		//alert(response.msg);
                		$scope.sendOTP();
                	}else{
                		alert("Signup Completed Successfully");
                    	location.reload();
                	}  
                }else{
					alert(response.msg);
				}
				
            });
	}

	$scope.submitLoginData=function(){
		if($('#loginForm')[0].checkValidity()==false){
			$("#signSubmitBtn").click();
			 $('#loginForm').find(':submit').click();
			 return;
		}
		//var postVars= "name:"+$scope.name+","+tokenName+":"+token+"";
            var data =$("#loginForm").serialize();
			var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
			//alert("jhjhgj");
            $http.post(baseUrl+"Account/login/",data,config).success(function(response){
                if(response.status == 'success'){
                    alert(response.msg);
                    $("#loginmodal").modal("hide");
                    location.reload();
                }else{
					alert(response.msg);
				}
				
            });
	}

	$scope.removeProductFromCart=function(row){
		var data = $.param({
			 	oid:row.oid
            });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
		//alert("jhjhgj");
        $http.post(baseUrl+"Api/removeFromCart/",data,config).success(function(response){
            if(response.status == 'success'){
            	//$scope.grandTotal=0;
               //alert(response.msg);
               $scope.cartDetails=response.list;
               $scope.notify("Item Has Been Removed From Cart");
            }else{
				//alert(response.msg);
				$scope.notify(response.msg,"warning");
			}
			
        });
	}

	$scope.validateCartItemQuantity=function(row){
		if(row.count==0){
			$scope.removeProductFromCart(row);
		}

	}

	$scope.addToCart2=function(operator,row,variant_type='single'){
		//var postVars= "name:"+$scope.name+","+tokenName+":"+token+"";
		if(variant_type=="single"){
			var data = $.param({
			 	product_id 	: row.product_id,
				variant_id  : row.variant_id,
				qty			: 1,
				operator	:operator,
            });
		}else{
			var data = $.param({
			 	product_id 	: row.product_id,
				variant_id  : row.selected_option.id,
				qty			: 1,
				operator	:operator,
            });
		}
            
			var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
			//alert("jhjhgj");
            $http.post(baseUrl+"Api/addToCart/",data,config).success(function(response){
                if(response.status == 'success'){
                	//$scope.grandTotal=0;
                   //alert(response.msg);
                   $scope.cartItemCounter=response.item_count;
                   $scope.cartDetails=response.list;
                   if(operator=="+"){
                   		$scope.notify("Item Added To Cart");
                   }else{
                   		$scope.notify("Item Removed From The Cart");
                   }
                    
                }else{
					if(response.type=="2"){
                		$scope.notify(response.msg,"warning");
                		//alert(response.msg);
                		$("#loginmodal").modal("show");
                		//$scope.search_val="";
                	}else{
                		//alert(response.msg);
                		$scope.notify(response.msg,"warning");
                	}
				}
				
            });
	}


	$scope.addToCart=function(values){
		//var postVars= "name:"+$scope.name+","+tokenName+":"+token+"";
		console.log(values);
            var data = values;
			var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
			//alert("jhjhgj");
            $http.post(baseUrl+"Api/addToCart/",data,config).success(function(response){
                if(response.status == 'success'){
                	//$scope.grandTotal=0;
                   //alert(response.msg);
                   $scope.cartItemCounter=response.item_count;
                   $scope.cartDetails=response.list;
                   $scope.notify("Item Added To Cart");
                }else{
                	if(response.type=="2"){
                		$scope.notify(response.msg,"warning");
                		//alert(response.msg);
                		$("#loginmodal").modal("show");
                	}else{
                		//alert(response.msg);
                		$scope.notify(response.msg,"warning");
                	}
					//alert(response.msg);
				}
				
            });
	}


	$scope.address_id="";
	$scope.openAddressForm=function(){
		$("#addressFormArea").show();
	}
	$scope.addressListData=[];
	$scope.getAddressList=function(){
		//alert("sdasdas");
		$http.get(baseUrl+"Api/addressList")
	    .then(function (response) {
			//alert(response);
			$scope.addressListData = response.data.address_list;
		});
	}
	$scope.setAddress=function(id){
		$scope.address_id=id;
	}
	$scope.saveAddress=function(){
		//var data = $("#addressFormArea").serialize();
		var data=$('#addressFormArea').find('select, textarea, input').serialize();
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
		//alert("jhjhgj");
        $http.post(baseUrl+"Api/saveAddress/",data,config).success(function(response){
            if(response.status == 'success'){
            	//$scope.grandTotal=0;
               $scope.addressListData=response.address_list;
               $scope.setAddress(response.address_id);
               $("#addressFormArea").hide();
               $("#addressForm")[0].reset()
            }else{
				//alert(response.msg);
				$scope.notify(response.msg);
			}	
        });
	}
	$scope.checkoutMsg= {
		addressRequired : '',
		payModeRequired : ''
	}

	$scope.validateCheckoutForm=function(){
		//alert("sdASDas");
		if($scope.address_id==""){
			/*if($scope.addressListData.length==0){
				$scope.checkoutMsg.addressRequired="You Must Add an Address To Complete The Order Booking";
				return;
			}*/
			$scope.checkoutMsg.addressRequired="You Must Select/Add an Address To Complete The Order Booking";
			console.log("You Must Select an Address To Complete The Order Booking");
			$("#accordionLink1").trigger('click');
			return;
		}

		/*if($('#multi_payment_mode').length > 0 && $('##multi_payment_mode').val() != ''){

		}*/
		/*console.log("val : "+$('#multi_payment_mode').val()==='undefined');
		return;*/

		if($('[name="payment_mode"]:checked').length ==0 && $('#multi_payment_mode').val()==undefined){
			$scope.checkoutMsg.payModeRequired="You Must Select Payment Mode To Complete The Order Booking";
			console.log("You Must Select Payment Mode To Complete The Order Booking");
			$("#accordionLink3").trigger('click');
			return;
		}

		$("#checkoutForm").submit();

		/*var data = $("#checkoutForm").serialize();
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
        $http.post(baseUrl+"Api/completeOrder/",data,config).success(function(response){
            if(response.status == 'success'){
            	//$scope.grandTotal=0;
               window.location.href=$scope.baseUrl+"order-success/"+response.order_id;
            }else{
				//alert(response.msg);
				$scope.notify(response.msg,"warning");
			}	
        });*/



	}
	
   /* $scope.inItFilterProductList=function(){
    	$scope.inItFilterProductList
    }*/

	/*$scope.filterProductList=function(){
		alert("sdasdas");
		$http.get(baseUrl+"Api/filterProductList?"+queryString)
	    .then(function (response) {
			//alert(response);
			$scope.products = response.data.products;
		});
	}*/
	$scope.filterMsg="";
	$scope.filterOffset=0;
	$scope.filterWait=0;
	$scope.noMoreData=0;
	$scope.filterProductList=function(offset=0){
		if(offset==0){
			$scope.noMoreData=0
		}
		console.log("filter calling "+$scope.filterOffset);
		if($scope.filterWait>0){return;}
		if($scope.noMoreData==1){return;}
		$scope.filterWait=1;
		var data = $("#productListFilterForm").serialize();
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
		//alert("jhjhgj");
        $http.post(baseUrl+"Api/filterProductList/"+offset,data,config).success(function(response){
            if(response.status == 'success'){
            	//$scope.grandTotal=0;
               if(response.products.length>0){
               	var i =$scope.products.length;
               	console.log("product count : "+i);
               	console.log("offset : "+offset);
               	//$scope.products.concat(response.products);
               		if(offset==0){
               			$scope.products=response.products;
               		}else{
               			angular.forEach(response.products, function(value, key) {
		               		$scope.products[i+key]=value
						  	console.log(key + ': ' + value);
						});
               		}
	               	
               }
               if(response.products.length==0 && offset==0){
                 $scope.products=[];
               }
               
               if($scope.products.length==0){
               		$scope.filterMsg=" No Product Available";
               }else if($scope.products.length>0 && response.products.length==0){
               		$scope.filterMsg="No More Products Available";	
               		$scope.noMoreData=1;
               }else{
               		$scope.filterMsg="";
               }
               if(response.products.length>0){
               	$scope.filterOffset++;
               }
               $scope.filterWait=0;
               
            }else{
				//alert(response.msg);
			}

			
        });
	}


	$scope.myOrderList=[];
	$scope.getOrderList=function(){
		//alert("ddfasdfas");
		var data = $.param({
            });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
		//alert("jhjhgj");
        $http.post(baseUrl+"Api/myOrders/",data,config).success(function(response){
            if(response.status == 'success'){
            	//$scope.grandTotal=0;
               //alert(response.msg);
               $scope.myOrderList=response.order_list;
            }else{
				//alert(response.msg);
				$scope.notify(response.msg,"warning");
			}
			
        });
	}

	$scope.trackOrderData=[];
	$scope.trackOrder=function(order_id){
		//alert("asdASDa");
		var data = $.param({
				order_id : order_id
            });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
        $http.post(baseUrl+"Api/trackOrder/",data,config).success(function(response){
            if(response.status == 'success'){
              $scope.trackOrderData=response.details;
            }else{
				//$scope.notify(response.msg,"warning");
			}
        });
	}

	$scope.updatePassword=function(){
		//var data = $("#changePasswordForm").serialize();
		var data = $.param({
				current_password :$scope.current_password,
				new_password :$scope.new_password,
				confirm_new_password :$scope.confirm_new_password,
            });

		console.log(data);
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
        $http.post(baseUrl+"Api/updatePassword/",data,config).success(function(response){
            if(response.status == 'success'){
             	$scope.notify(response.msg);
            }else{
				$scope.notify(response.msg,"warning");
			}
        });
	}


	$scope.updateProfileData=function(){
		//var data = $("#changePasswordForm").serialize();
		var data = $.param({
				name :$scope.myProfileDetails.md.name,
            });

		console.log(data);
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
        $http.post(baseUrl+"Api/updateProfileData/",data,config).success(function(response){
            if(response.status == 'success'){
             	$scope.notify(response.msg);
            }else{
				$scope.notify(response.msg,"warning");
			}
        });
	}


	$scope.myProfileDetails=[];
	$scope.myProfileData=function(){
		//alert("dfasdfasdf");
		var data =$.param({
            });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
        $http.post(baseUrl+"Api/myProfileData/",data,config).success(function(response){
            if(response.status == 'success'){
            	$scope.myProfileDetails=response.details;
             	//$scope.notify(response.msg);
            }else{
				$scope.notify(response.msg,"warning");
			}
        });
	}

	$scope.cancelOrder=function(order_id){
		//alert("sdfasdf");
		var confirmed=confirm("are you sure you want to cancel this order?");
		if(confirmed==false){
			return;
		}

		var reason=prompt("Please Provide Reason Of Cancel", "");

		var data =$.param({
			order_id :order_id,
			reason : reason,
            });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
        $http.post(baseUrl+"Api/cancelOrder/",data,config).success(function(response){
            if(response.status == 'success'){
             	//$scope.notify(response.msg);
             	alert(response.msg);
             	window.location.href=baseUrl+"Home/myOrder";
            }else{
				$scope.notify(response.msg,"warning");
			}
        });

	}


	$scope.editOrder=function(order_id){
		var c=confirm("Do You Really Want To Edit This Order? If You Edit This Order Your Order Items Will Be Shown In Cart...");
		if(c==false){return;}
		var data = $.param({
				order_id : order_id
            });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
        $http.post(baseUrl+"Api/editOrder/",data,config).success(function(response){
            if(response.status == 'success'){
            	alert(response.msg);
               window.location.href = baseUrl;
            }else{
				$scope.notify(response.msg,"warning");
			}
        });
	}


	$scope.filterNonNumericValue = function($event){
        if(isNaN(String.fromCharCode($event.keyCode))){
            $event.preventDefault();
        }
	};


	$scope.addWalletMoney=function(){
		if($('#walletMoneyForm')[0].checkValidity()==false){
			$("#walletAddBtn").click();
			 $('#walletAddBtn').find(':submit').click();
			 return;
		}
		$http.get(baseUrl+"Api/loginStatus").then(function (response) {
			if(response.data.status_code==0){
				$scope.notify("Invalid Login Session.... PLease Login To Continue","warning");
				$("#loginmodal").modal("show");
			}else{
				if($("#walletAddAmt").val()<10){
					$scope.notify("Minimum 10 Rupees You Can Add To Wallet At Once","warning");
					return;
				}
				$('#walletMoneyForm')[0].submit();
			}
		});



	}

	$scope.productDetails=[];
	$scope.getProductDetails=function(product_id){
		var data = $.param({
				product_id : product_id
            });
		var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
        $http.post(baseUrl+"Api/productDetails/",data,config).success(function(response){
            if(response.status == 'success'){
            	$scope.productDetails=response.details;
            }else{
				$scope.notify(response.msg,"warning");
			}
        });
	}




	$(document).on('submit','.form1',function(){

	    // Get all the forms elements and their values in one step
	    var values = $(this).serialize();
	    console.log(values);
	    $scope.addToCart(values);
	    return false;

	});






	
});


$(document).ready(function() {

})
