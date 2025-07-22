<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Your Order Confirmation</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 40px 0; }
    .email-wrapper {
      max-width: 620px;
      margin: auto;
      background: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .email-header {
      background-color: #007bff;
      color: #fff;
      padding: 20px;
      text-align: center;
      font-size: 20px;
    }
    .email-body {
      padding: 30px;
      color: #333;
    }
    .email-body p {
      font-size: 16px;
      line-height: 1.6;
    }
    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 25px;
      background-color: #007bff;
      color: #fff;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
    }
    .email-footer {
      background-color: #f9f9f9;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #888;
      border-top: 1px solid #eee;
    }
  </style>
</head>
<body>
  <div class="email-wrapper">
    <div class="email-header">Order Confirmation</div>
    <div class="email-body">
      <p>Hi <?php echo $order->customer_name; ?>,</p>
      <p>Thank you for your order <strong>#<?php echo $order->order_id; ?></strong> placed on <strong><?php echo $order->created_at; ?></strong>.</p>
      <p>You can download or view your invoice by clicking the button below:</p>

      <a href="<?php echo $order->invoice_url; ?>" class="btn" target="_blank">View Invoice</a>

      <p>If you have any questions, just reply to this email — we’re here to help!</p>
    </div>
    <div class="email-footer">
      &copy; <?php echo date('Y'); ?> <?php echo SITE_TITLE; ?>. All rights reserved.
    </div>
  </div>
</body>
</html>
