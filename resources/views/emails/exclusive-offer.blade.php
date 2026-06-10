<!DOCTYPE html>
<html>
<head>
    <title>Exclusive Offer Just For You!</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-w: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4F46E5; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 5px 5px; }
        .plan-box { background-color: #EEF2FF; border: 1px solid #C7D2FE; padding: 15px; border-radius: 5px; margin: 20px 0; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #4F46E5; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Exclusive Custom Offer!</h2>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            
            <p>We've assigned a special, private subscription plan exclusively to your account. This plan is not available to the general public!</p>

            <div class="plan-box">
                <h3 style="margin-top: 0; color: #4F46E5;">{{ $plan->name }}</h3>
                <p style="font-size: 24px; font-weight: bold; margin: 10px 0;">
                    @if($effectivePrice < $plan->price)
                        <span style="text-decoration: line-through; color: #999; font-size: 16px; margin-right: 8px;">₹{{ number_format($plan->price, 0) }}</span>
                    @endif
                    ₹{{ number_format($effectivePrice, 0) }}
                </p>
                <p>Duration: {{ $plan->duration_days }} Days<br>Contact Limit: {{ $plan->contact_limit }} Unlocks</p>
                
                @if($plan->features)
                <ul style="text-align: left; display: inline-block;">
                    @foreach($plan->features as $feature)
                        <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                @endif
            </div>

            <p style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">Log In & Claim Offer</a>
            </p>

            <p>If you have any questions, feel free to reply to this email.</p>
            
            <p>Best regards,<br>The UnlockRentals Team</p>
        </div>
    </div>
</body>
</html>
