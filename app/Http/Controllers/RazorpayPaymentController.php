<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError;
use Razorpay\Api\Errors\Error;

class RazorpayPaymentController extends Controller
{
    public function createOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'integer', 'min:100'],
            'currency' => ['nullable', 'string', 'size:3'],
            'receipt' => ['nullable', 'string', 'max:40'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid order request.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = $validator->validated();
        $keyId = config('services.razorpay.key_id');
        $keySecret = config('services.razorpay.key_secret');

        if (!$keyId || !$keySecret) {
            return response()->json(['message' => 'Razorpay credentials are not configured.'], 401);
        }

        try {
            $api = new Api($keyId, $keySecret);
            $order = $api->order->create([
                'amount' => $data['amount'],
                'currency' => strtoupper($data['currency'] ?? 'INR'),
                'receipt' => $data['receipt'] ?? 'receipt_' . now()->timestamp,
            ]);

            return response()->json([
                'order_id' => $order['id'],
                'amount' => $order['amount'],
                'currency' => $order['currency'],
                'key_id' => $keyId,
            ]);
        } catch (BadRequestError $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (Error $e) {
            $status = str_contains(strtolower($e->getMessage()), 'authentication') ? 401 : 500;
            return response()->json(['message' => 'Unable to create Razorpay order.'], $status);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Unable to create Razorpay order.'], 500);
        }
    }

    public function verifyPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Missing required Razorpay payment fields.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = $validator->validated();
        $keySecret = config('services.razorpay.key_secret');

        if (!$keySecret) {
            return response()->json(['message' => 'Razorpay credentials are not configured.'], 401);
        }

        $payload = $data['razorpay_order_id'] . '|' . $data['razorpay_payment_id'];
        $expectedSignature = hash_hmac('sha256', $payload, $keySecret);

        if (!hash_equals($expectedSignature, $data['razorpay_signature'])) {
            return response()->json(['message' => 'Payment signature mismatch.'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
            'payment_id' => $data['razorpay_payment_id'],
            'order_id' => $data['razorpay_order_id'],
        ]);
    }
}
