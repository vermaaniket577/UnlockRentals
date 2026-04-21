<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f6fa; padding: 40px; }
        .card { background: #ffffff; padding: 40px; border-radius: 16px; max-width: 500px; margin: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .logo { margin-bottom: 30px; }
        h1 { font-size: 22px; color: #1e3a8a; margin-bottom: 20px; }
        p { color: #4a5568; line-height: 1.6; font-size: 15px; }
        .btn { display: inline-block; padding: 14px 28px; background: #2563EB; color: #ffffff !important; text-decoration: none; border-radius: 10px; font-weight: bold; margin-top: 25px; }
        .footer { margin-top: 35px; border-top: 1px solid #e2e8f0; pt: 25px; font-size: 13px; color: #718096; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Reset Your Password</h1>
        <p>You are receiving this email because we received a password reset request for your account at UnlockRentals.</p>
        <p>Please click the button below to choose a new password. This link will expire in 60 minutes.</p>
        
        <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" class="btn">Reset Password</a>
        
        <div class="footer">
            If you did not request a password reset, no further action is required.<br><br>
            Regards,<br>
            UnlockRentals Team
        </div>
    </div>
</body>
</html>
