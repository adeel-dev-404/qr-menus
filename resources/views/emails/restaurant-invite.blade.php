{{-- resources/views/emails/restaurant-invite.blade.php --}}
@component('mail::message')
# Welcome, {{ $ownerName }}!

You have been added as the owner of **{{ $restaurantName }}** on {{ config('app.name') }}.

Click the button below to set your password and access your restaurant dashboard:

@component('mail::button', ['url' => $inviteUrl, 'color' => 'primary'])
Accept Invite & Set Password
@endcomponent

This link is unique to you. Once you set your password, it will expire automatically.

If you did not expect this email, you can safely ignore it.

Thanks,
**{{ config('app.name') }} Team**
@endcomponent