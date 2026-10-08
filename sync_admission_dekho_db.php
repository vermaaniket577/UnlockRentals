<?php
/**
 * ============================================================================
 * Admission Dekho Database Sync & Migration Script
 * ============================================================================
 * Transfers lead enquiries from Admission Dekho website's database
 * to the UnlockRentals CRM database via the Lead Intake API.
 * 
 * Usage:
 *   CLI: php sync_admission_dekho_db.php
 *   Web: Open in browser (e.g. http://localhost/sync_admission_dekho_db.php)
 * ============================================================================
 */

// 1. Admission Dekho Database Configuration (Update with your credentials)
$sourceDb = [
    'host'     => getenv('ADMISSION_DEKHO_DB_HOST') ?: '127.0.0.1',
    'port'     => getenv('ADMISSION_DEKHO_DB_PORT') ?: '3306',
    'database' => getenv('ADMISSION_DEKHO_DB_NAME') ?: 'admission_dekho',
    'username' => getenv('ADMISSION_DEKHO_DB_USER') ?: 'root',
    'password' => getenv('ADMISSION_DEKHO_DB_PASS') ?: '',
    'table'    => getenv('ADMISSION_DEKHO_DB_TABLE') ?: 'enquiries', // Table containing leads (e.g. enquiries, leads, wp_posts)
];

// 2. UnlockRentals API Target
$targetApiUrl = getenv('UNLOCKRENTALS_API_URL') 
    ?: 'https://www.unlockrentals.com/api/admission-dekho/bulk';

$batchSize = 50; // Process 50 leads per API request

echo "========================================================\n";
echo " Admission Dekho -> UnlockRentals CRM Database Sync \n";
echo "========================================================\n\n";

// Connect to Admission Dekho Database
try {
    $dsn = "mysql:host={$sourceDb['host']};port={$sourceDb['port']};dbname={$sourceDb['database']};charset=utf8mb4";
    $pdo = new PDO($dsn, $sourceDb['username'], $sourceDb['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo " Connected to Admission Dekho database: {$sourceDb['database']}\n";
} catch (\PDOException $e) {
    echo " [ERROR] Cannot connect to Admission Dekho database: " . $e->getMessage() . "\n";
    echo " Please edit the credentials in sync_admission_dekho_db.php or set environment variables.\n";
    exit(1);
}

// Check if table exists
try {
    $checkStmt = $pdo->query("SHOW TABLES LIKE '{$sourceDb['table']}'");
    if (!$checkStmt->fetch()) {
        // List available tables to help the user identify the right one
        $allTables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo " [WARNING] Table '{$sourceDb['table']}' not found.\n";
        echo " Available tables in database: " . implode(', ', $allTables) . "\n";
        exit(1);
    }
} catch (\Throwable $e) {
    echo " [ERROR] " . $e->getMessage() . "\n";
    exit(1);
}

// Fetch all rows from table
echo " Fetching leads from '{$sourceDb['table']}'...\n";
$stmt = $pdo->query("SELECT * FROM `{$sourceDb['table']}` ORDER BY 1 ASC");
$rows = $stmt->fetchAll();
$totalRows = count($rows);
echo " Found {$totalRows} total lead records.\n\n";

if ($totalRows === 0) {
    echo "No lead records to sync. Exiting.\n";
    exit(0);
}

// Prepare leads into standardized schema
$formattedLeads = [];
foreach ($rows as $row) {
    // Flexible column auto-detection
    $phone = $row['contact'] 
        ?? $row['phone'] 
        ?? $row['mobile'] 
        ?? $row['phone_number'] 
        ?? $row['student_mobile'] 
        ?? $row['tel'] 
        ?? '';

    $cleanPhone = preg_replace('/[^0-9]/', '', (string)$phone);
    if (strlen($cleanPhone) >= 10) {
        $cleanPhone = substr($cleanPhone, -10);
    }

    if (empty($cleanPhone) || strlen($cleanPhone) < 10) {
        continue; // Skip invalid phone numbers
    }

    $name = $row['name'] 
        ?? trim(($row['first_name'] ?? $row['firstName'] ?? '') . ' ' . ($row['last_name'] ?? $row['lastName'] ?? ''))
        ?? $row['student_name']
        ?? $row['candidate_name']
        ?? 'Admission Enquirer';

    $email = $row['email'] ?? $row['student_email'] ?? null;
    $course = $row['course'] ?? $row['stream'] ?? $row['program'] ?? $row['subject'] ?? '';
    $city = $row['city'] ?? $row['preferred_city'] ?? $row['location'] ?? '';
    $state = $row['state'] ?? $row['region'] ?? '';
    $message = $row['message'] ?? $row['query'] ?? $row['enquiry'] ?? $row['notes'] ?? '';
    $createdAt = $row['created_at'] ?? $row['date'] ?? $row['submission_date'] ?? $row['datetime'] ?? null;
    $id = $row['id'] ?? $row['_id'] ?? $row['lead_id'] ?? null;

    $formattedLeads[] = [
        'id'         => $id,
        'name'       => $name,
        'contact'    => $cleanPhone,
        'email'      => $email,
        'course'     => $course,
        'stream'     => $course,
        'city'       => $city,
        'state'      => $state,
        'message'    => $message,
        'created_at' => $createdAt,
        'source'     => 'Admission Dekho',
    ];
}

$validCount = count($formattedLeads);
echo " {$validCount} valid leads ready for migration (skipped " . ($totalRows - $validCount) . " invalid/empty phone records).\n\n";

// Dispatch in chunks to UnlockRentals API
$chunks = array_chunk($formattedLeads, $batchSize);
$totalImported = 0;
$totalUpdated = 0;

foreach ($chunks as $index => $chunk) {
    $batchNum = $index + 1;
    echo " Dispatching Batch #{$batchNum} (" . count($chunk) . " leads) to UnlockRentals CRM API...\n";

    $payload = json_encode(['leads' => $chunk]);

    $ch = curl_init($targetApiUrl);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: AdmissionDekho-SyncScript/1.0',
        ],
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($curlErr) {
        echo "   [ERROR] cURL failure: {$curlErr}\n";
        continue;
    }

    $result = json_decode($response, true);
    if ($httpCode >= 200 && $httpCode < 300 && ($result['success'] ?? false)) {
        $imp = $result['imported_count'] ?? 0;
        $upd = $result['updated_count'] ?? 0;
        $totalImported += $imp;
        $totalUpdated += $upd;
        echo "   [SUCCESS] Created: {$imp} new leads | Updated: {$upd} existing leads.\n";
    } else {
        echo "   [NOTICE] Response code {$httpCode}: " . ($result['message'] ?? substr($response, 0, 150)) . "\n";
    }
}

echo "\n========================================================\n";
echo " Synchronization Complete!\n";
echo " Newly Created Leads in CRM : {$totalImported}\n";
echo " Updated Existing Leads     : {$totalUpdated}\n";
echo " All leads are now live in UnlockRentals CRM Pipeline.\n";
echo " View them at: https://www.unlockrentals.com/admin/leads\n";
echo "========================================================\n";
