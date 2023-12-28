<!DOCTYPE html>
<html>
<head>
    <title>Order Status Update</title>
</head>
<body>
    <h1>Order Status Update</h1>

    <p>Dear {{ $mail_data['customer_name'] }},</p>

    <p>We are writing to inform you about the status of your order.</p>

    <table>
        <tr>
            <td>Order ID:</td>
            <td>{{ $mail_data['order']['id'] }}</td>
        </tr>
        <tr>
            <td>Order Status:</td>
            <td>{{ $mail_data['order']['status'] }}</td>
        </tr>
        <tr>
            <td>Order Date:</td>
            <td>{{ $mail_data['order']['created_at'] }}</td>
        </tr>
    </table>

    <p>Thank you for shopping with us.</p>

    <p>Best Regards,</p>
    <p>{{config('app.name')}}</p>
</body>
</html>