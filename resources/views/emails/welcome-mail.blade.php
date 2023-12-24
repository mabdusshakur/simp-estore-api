<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Welcome to {{$mail_data['company_name']}}!</h1>
    <p>Dear {{$mail_data['name']}},</p>
    <p>Thank you for joining us. We are excited to have you as a member of our community.</p>
    <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
    <p>Best regards,</p>
    <p>The Team {{$mail_data['company_name']}}</p>
</body>
</html>
