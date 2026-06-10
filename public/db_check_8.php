<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: application/json');

try {
    $user = \App\Models\User::where('email', 'vermaaniket577@gmail.com')->first();
    if (!$user) {
        $user = \App\Models\User::first();
    }
    
    $paymentLogs = \App\Models\PaymentLog::where('user_id', $user->id)->latest()->get()->toArray();
    $userPlans = \App\Models\UserPlan::where('user_id', $user->id)->latest()->get()->toArray();
    
    echo json_encode([
        'user' => $user->toArray(),
        'payment_logs' => $paymentLogs,
        'user_plans' => $userPlans,
    ], JSON_PRETTY_PRINT);
} catch (\Throwable $e) {
    echo json_encode([
        'error' => get_class($e),
        'message' => $e->getMessage(),
    ], JSON_PRETTY_PRINT);
}
