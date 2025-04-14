@component('mail::message')
# Hello {{ $name }},

You requested a password reset. Click the button below to reset your password:

@component('mail::button', ['url' => $url, 'color' => 'cyan'])
Reset Password
@endcomponent

This link will expire in **10 minutes**.

If you have any questions, feel free to contact our support team at<br> [support@pmhcity.com](mailto:support@pmhcity.com).

Thanks,<br>
**PMHCity Team**
@endcomponent
