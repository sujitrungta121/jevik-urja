<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>New User Registration</title>
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
      font-size: 20px;
      text-align: center;
    }

    .email-body {
      padding: 30px;
      color: #333;
    }

    .email-body p {
      font-size: 16px;
      line-height: 1.5;
    }

    .info-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 15px;
    }

    .info-table td {
      padding: 10px;
      border-bottom: 1px solid #eeeeee;
    }

    .info-table td:first-child {
      font-weight: bold;
      width: 35%;
      color: #555;
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
    <div class="email-header">
      New User Registration Alert
    </div>
    <div class="email-body">
      <p>A new user has just registered on your platform. Here are the details:</p>

      <table class="info-table">
        <tr>
          <td>Name:</td>
          <td><?php echo $user->name; ?></td>
        </tr>
        <tr>
          <td>Email:</td>
          <td><?php echo $user->email; ?></td>
        </tr>
        <tr>
          <td>Phone:</td>
          <td><?php echo $user->phone; ?></td>
        </tr>
        <tr>
          <td>Registered At:</td>
          <td><?php echo $user->created_at; ?></td>
        </tr>
      </table>
    </div>
    <div class="email-footer">
      This is an automated message from <?php echo SITE_TITLE; ?>. Please do not reply.
    </div>
  </div>

</body>
</html>
