<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->
<script
  src="https://code.jquery.com/jquery-3.5.1.slim.js"
  integrity="sha256-DrT5NfxfbHvMHux31Lkhxg42LY6of8TaYyK50jnxRnM="
  crossorigin="anonymous"></script>
<form action="<?php echo base_url("index.php/Home/verify_payment");?>" method="POST" id="form1" name="form1">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="GOMART"
    data-image="http://gomart.in/frontend/assets/images/logo.png"
    data-description="Ecommerce Platform"
    data-prefill.name="customer name"
    data-prefill.email="debasish.1911@gmail.com"
    data-prefill.contact="+917003782339"
    data-notes.shopping_order_id="3456"
    data-order_id="<?php echo $data['order_id'];?>"
    data-buttontext="Pay with Razorpay"
  >
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <button type="button">Cancel</button>
  <input type="hidden" name="shopping_order_id" value="3456">
</form>

<script>
  $(window).on('load', function() {
   $('.razorpay-payment-button').click();
  });
</script>
