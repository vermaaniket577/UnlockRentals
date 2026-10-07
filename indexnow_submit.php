<?php
/**
 * UnlockRentals IndexNow Instant Submitter
 * Submits URL batches to Bing, Yandex, Seznam, and IndexNow API partners.
 */

$host = 'www.unlockrentals.com';
$key = 'f6c9d8a3e7b2415089c1d4e2a7b6f3c8';
$keyLocation = "https://{$host}/{$key}.txt";

$urlsFile = __DIR__ . '/urls_to_index.txt';
if (!file_exists($urlsFile)) {
    die("Error: urls_to_index.txt not found!\n");
}

$urls = array_filter(array_map('trim', explode("\n", file_get_contents($urlsFile))));
$urls = array_values(array_unique($urls));

echo "Loaded " . count($urls) . " URLs to submit to IndexNow.\n";

// IndexNow accepts up to 10,000 URLs per request
$batches = array_chunk($urls, 1000);

$endpoints = [
    'https://api.indexnow.org/indexnow',
    'https://www.bing.com/indexnow'
];

foreach ($batches as $batchIndex => $batchUrls) {
    echo "Submitting Batch #" . ($batchIndex + 1) . " (" . count($batchUrls) . " URLs)...\n";
    
    $payload = json_encode([
        'host' => $host,
        'key' => $key,
        'keyLocation' => $keyLocation,
        'urlList' => $batchUrls
    ], JSON_UNESCAPED_SLASHES);

    foreach ($endpoints as $endpoint) {
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "  [{$endpoint}] Response Code: {$httpCode}";
        if ($httpCode === 200 || $httpCode === 202) {
            echo " (SUCCESS: URLs accepted for indexing!)\n";
        } else {
            echo " (Response: {$response})\n";
        }
    }
}

echo "\nIndexNow submission complete!\n";
