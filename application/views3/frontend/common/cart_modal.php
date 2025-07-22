<div class="cart">
	<div class="modal right fade" id="cartpanel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <i class="ti-angle-left"></i>
					</button>
					Shopping Cart({{cartDetails.length}})	
				</div>
                <div class="modal-body">
                    <div class="cart-item-main" ng-repeat="row in cartDetails">
                    	<?php echo form_open("",array("class"=>"form1"));?>
						<div class="cart-view">
							<div><img src="{{row.image}}" alt="nail" width="100px"></div>
							<div>{{row.product_name}}  ({{row.variant_name}} x {{row.price}} )</div>

							<div><a  ng-click="removeProductFromCart(row)" href="#"><i class="ti-trash"></i></a></div>
						</div>
						<article class="row">
							<div class="col-5">
								<div class="qty">
									<span ng-if="row.count>0" ng-click="addToCart2('-',row)" title="Delete Product" class="minus" id="minus204"><i class="ti-minus"></i></span>
									<input type="number" class="count" id="count204" name="qty" value="{{row.count}}" disabled="">
									<span ng-click="addToCart2('+',row)" title="Add Product" class="plus" id="plus204"><i class="ti-plus"></i></span>
								</div>
							</div>
							<div class="col-7" >
								<h6><span>₹{{row.old_price*row.count}}</span> ₹{{row.price*row.count}}</h6>
							</div>
						</article>
						<?php echo form_close();?>
					</div>
					<!-- cart payment details -->
					<div class="cart-payment">
						<div class="heading text-center">Payment Details</div>
						<table class="table">
							<!-- <tr>
								<td>Bag Total</td>
								<td class="text-right">₹1198</td>
							</tr>
							<tr>
								<td>Sub Total</td>
								<td class="text-right">₹1198</td>
							</tr>
							<tr>
								<td>Shipping Charge</td>
								<td class="text-right">Free</td>
							</tr> -->
							<tr>
								<td>Grand Total</td>
								<td class="text-right"><b>₹{{getGrandTotal()}}</b></td>
							</tr>
						</table>
						<!-- <p>Earn <span>2324</span> Reward Points on this order</p> -->
					</div>
					<!-- //end cart payment details -->
                </div>
				<div class="modal-footer">
					<div class="item" ng-if="getGrandTotal()>0">
						Grand Total:
						<span>₹ {{getGrandTotal()}}</span>
					</div>
					<div class="item" ng-if="getGrandTotal()>0">
						<a  href="<?php echo base_url("Home/checkout");?>">PROCEED <i class="ti-angle-right"></i></a>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>