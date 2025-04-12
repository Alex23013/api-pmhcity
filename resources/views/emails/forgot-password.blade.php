<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
</head>
<body>
    <p>Hello {{ $name }},</p>

    <p>You requested a password reset. Click the button below to reset your password:</p>

    <p style="text-align: center;">
        <a href="{{ $url }}" style="background-color: #6CE5E8; color: black; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Reset Password
        </a>
    </p>
    <br>
    <p>This link will expire in 10 minutes.</p>

    <p>If you have any questions, please contact our support team at support@pmhcity.com</p>

    <p>Best regards,</p>
    <p>PMHCity Team</p>
</body>
</html>
