<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Signing you in - UnlockRentals</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        body {
            background: #090d16;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
            position: relative;
        }
        .bg-glow {
            position: absolute;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(37,99,235,0.3) 0%, rgba(37,99,235,0) 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            pointer-events: none;
            filter: blur(40px);
        }
        .card {
            background: rgba(18, 24, 38, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 28px;
            padding: 36px 28px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
            position: relative;
            z-index: 10;
        }
        .logo-ring {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 24px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 30px rgba(37,99,235,0.4);
            animation: pulse 2s infinite ease-in-out;
        }
        .logo-ring img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 12px 30px rgba(37,99,235,0.4); }
            50% { transform: scale(1.05); box-shadow: 0 16px 40px rgba(37,99,235,0.6); }
        }
        h1 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #fff;
            letter-spacing: -0.5px;
        }
        p {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        .btn-app {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            border-radius: 16px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
            transition: all 0.2s ease;
        }
        .btn-app:active {
            transform: scale(0.98);
        }
        .btn-web {
            display: inline-block;
            margin-top: 14px;
            font-size: 12px;
            color: #64748b;
            text-decoration: underline;
            cursor: pointer;
            background: none;
            border: none;
        }
        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="card">
        <div class="logo-ring">
            <img src="{{ asset('images/icons/icon-192x192.png') }}" alt="UnlockRentals" onerror="this.style.display='none'">
        </div>
        <h1>Welcome, {{ $user->name }}!</h1>
        <p id="status-text">Returning to your UnlockRentals Mobile Application...</p>

        <a id="btn-open-app" href="#" class="btn-app">
            <span class="spinner" id="btn-spinner"></span>
            <span id="btn-label">Opening App...</span>
        </a>

        <div>
            <button type="button" class="btn-web" onclick="continueToWeb()">Continue in browser instead</button>
        </div>
    </div>

    <script>
        (function() {
            var token = @json($loginToken);
            var targetPath = @json($redirectPath);
            var appSchemeUrl = "unlockrentals://auth/callback?token=" + encodeURIComponent(token);
            var appIntentUrl = "intent://unlockrentals.com/auth/token-login?token=" + encodeURIComponent(token) + "#Intent;scheme=https;package=com.example.unlockrentals;end;";
            var tokenLoginWebUrl = "{{ url('/auth/token-login') }}?token=" + encodeURIComponent(token);

            var btn = document.getElementById('btn-open-app');
            var btnLabel = document.getElementById('btn-label');
            var btnSpinner = document.getElementById('btn-spinner');

            btn.href = appSchemeUrl;

            function triggerAppOpen() {
                // 1. Try Custom Scheme first (Instant match for installed Android/iOS app)
                window.location.href = appSchemeUrl;

                // 2. Try Android Intent scheme after small delay
                setTimeout(function() {
                    window.location.href = appIntentUrl;
                }, 300);
            }

            window.continueToWeb = function() {
                window.location.replace(targetPath || '/');
            };

            // Detect if inside an already-authenticated WebView or Mobile wrapper
            var isInsideWebView = /UnlockRentals|wv|Version\/[0-9.]+/i.test(navigator.userAgent);

            if (isInsideWebView) {
                // Inside app WebView: directly load the target path without launching external intent
                window.location.replace(targetPath || '/');
            } else {
                // In Chrome or external browser after OAuth: trigger handoff to the mobile app
                triggerAppOpen();

                // If app is not installed or user doesn't switch within 4 seconds, update button text
                setTimeout(function() {
                    if (btnSpinner) btnSpinner.style.display = 'none';
                    if (btnLabel) btnLabel.innerText = 'Tap to Open App';
                    var status = document.getElementById('status-text');
                    if (status) status.innerText = 'Logged in successfully! Tap above to open in UnlockRentals App.';
                }, 1500);
            }
        })();
    </script>
</body>
</html>
