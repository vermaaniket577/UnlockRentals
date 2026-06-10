<x-mail::message>
# Subscription Activated!

Hi {{ $userPlan->user->name }},

Great news! Your **{{ $userPlan->plan->name }}** subscription has been successfully activated. 

**Plan Details:**
- **Price:** ₹{{ number_format($userPlan->plan->price, 0) }}
- **Duration:** {{ $userPlan->plan->duration_days }} Days
- **Expires On:** {{ $userPlan->expires_at->format('d M, Y h:i A') }}
- **Contact Unlocks:** {{ $userPlan->plan->contact_limit }} Owner Contacts

<x-mail::button :url="route('dashboard')">
Go to Dashboard
</x-mail::button>

Thank you for choosing {{ config('app.name') }}!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
