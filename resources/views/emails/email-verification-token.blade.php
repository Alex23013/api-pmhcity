@component('mail::message')
# Email Verification Code

Your 6-digit verification code is:

**{{ $token }}**

This code will expire in 10 minutes.

Thanks,<br>
PMHCity Team
@endcomponent