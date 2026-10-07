<?php
/**
 * Google Indexing API Batch Submitter
 * Uses Google Service Account to submit URLs directly to Google Indexing API.
 * 
 * Setup:
 * 1. Go to Google Cloud Console (https://console.cloud.google.com/)
 * 2. Enable "Web Search Indexing API"
 * 3. Create a Service Account, generate a JSON Key, and save it as "service_account.json" in this directory.
 * 4. Add the Service Account email (e.g. name@project.iam.gserviceaccount.com) as an "Owner" in Google Search Console.
 * 5. Run: php google_indexing.php
 */

$serviceAccountFile = __DIR__ . '/service_account.json';
$urlsFile = __DIR__ . '/urls_to_index.txt';

if (!file_exists($urlsFile)) {
    die("Error: urls_to_index.txt not found!\n");
}

$urls = array_filter(array_map('trim', explode("\n", file_get_contents($urlsFile))));
$urls = array_values(array_unique($urls));

echo "====================================================\n";
echo "UnlockRentals - Google Indexing API Batch Submitter\n";
echo "====================================================\n";
echo "Total URLs to process: " . count($urls) . "\n\n";

if (!file_exists($serviceAccountFile)) {
    echo "NOTICE: 'service_account.json' not found in this folder.\n";
    echo "----------------------------------------------------\n";
    echo "To submit URLs directly to Google Indexing API:\n";
    echo "1. Visit Google Cloud Console -> APIs & Services -> Enable 'Web Search Indexing API'\n";
    echo "2. Create a Service Account -> Keys -> Add Key -> JSON\n";
    echo "3. Save the downloaded file as 'service_account.json' here in this folder.\n";
    echo "4. Copy the service account email (e.g. indexing-bot@your-project.iam.gserviceaccount.com)\n";
    echo "   and add it as an 'Owner' in Google Search Console (Settings -> Users and permissions).\n";
    echo "5. Re-run: php google_indexing.php\n";
    echo "----------------------------------------------------\n";
    echo "In the meantime, you can:\n";
    echo "a) Resubmit https://www.unlockrentals.com/sitemap.xml in Google Search Console.\n";
    echo "b) Use 'urls_to_index.txt' in Google Search Console or IndexNow.\n";
    exit(0);
}

// 1. Parse service account credentials
$cred = json_decode(file_get_contents($serviceAccountFile), true);
if (!$cred || empty($cred['client_email']) || empty($cred['private_key'])) {
    die("Error: Invalid service_account.json file format.\n");
}

echo "Authenticated Service Account: " . $cred['client_email'] . "\n";
echo "Requesting OAuth2 access token from Google...\n";

// 2. Generate JWT for Google OAuth2
$now = time();
$header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
$claim = json_encode([
    'iss' => $cred['client_email'],
    'scope' => 'https://www.googleapis.com/auth/indexing',
    'aud' => 'https://oauth2.googleapis.com/token',
    'exp' => $now + 3600,
    'iat' => $now
]);

$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$base64UrlClaim = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claim));
$signatureInput = $base64UrlHeader . '.' . $base64UrlClaim;

$privateKey = openssl_pkey_get_private($cred['private_key']);
if (!$privateKey) {
    die("Error: Failed to read private key from service_account.json\n");
}

openssl_sign($signatureInput, $signature, $privateKey, 'SHA256');
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$jwt = $signatureInput . '.' . $base64UrlSignature;

// 3. Exchange JWT for Access Token
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $jwt
]));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$tokenResponse = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($tokenResponse, true);
if (empty($tokenData['access_token'])) {
    die("OAuth Error: " . $tokenResponse . "\n");
}

$accessToken = $tokenData['access_token'];
echo "OAuth2 access token successfully acquired! Expiry: 3600s\n\n";

// 4. Submit URLs to Google Indexing API (Daily quota is typically 200 URLs per day)
echo "Submitting URLs to Google Indexing API...\n";
$endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

$count = 0;
foreach ($urls as $url) {
    $count++;
    $body = json_encode([
        'url' => $url,
        'type' => 'URL_UPDATED'
    ]);

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo "[$count/" . count($urls) . "] HTTP 200 OK: $url\n";
    } else {
        echo "[$count/" . count($urls) . "] HTTP $httpCode: $url -> $res\n";
        if ($httpCode === 429) {
            echo "Daily quota limit reached (Google allows 200 requests/day per service account).\n";
            break;
        }
    }

    usleep(100000); // 100ms pause
}

echo "\nGoogle Indexing submission batch completed!\n";
