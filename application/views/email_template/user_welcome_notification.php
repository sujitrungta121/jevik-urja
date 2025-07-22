<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Welcome to <?php echo SITE_TITLE; ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 40px 0;
    }

    .email-wrapper {
      max-width: 620px;
      margin: 0 auto;
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .email-header {
      background-color: #007bff;
      color: #ffffff;
      padding: 20px;
      font-size: 22px;
      text-align: center;
    }

    .email-body {
      padding: 30px;
      color: #333;
    }

    .email-body p {
      font-size: 16px;
      line-height: 1.6;
    }

    .email-footer {
      background-color: #f9f9f9;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #888;
      border-top: 1px solid #eee;
    }

    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 25px;
      background-color: #007bff;
      color: #ffffff !important;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="email-wrapper">
    <div class="email-header">
      Welcome to <?php echo SITE_TITLE; ?>
    </div>
    <div class="email-body">
      <p>Hi <?php echo $user->name; ?>,</p>
      <p>Thank you for registering with <strong><?php echo SITE_TITLE; ?></strong>. We're thrilled to have you on board!</p>
      <p>You can now log in and start exploring our platform.</p>
      <p>If you have any questions or need support, feel free to reply to this email.</p>
    </div>
    <div class="email-footer">
      &copy; <?php echo date('Y'); ?> <?php echo SITE_TITLE; ?>. All rights reserved.
    </div>
  </div>

</body>
</html>
