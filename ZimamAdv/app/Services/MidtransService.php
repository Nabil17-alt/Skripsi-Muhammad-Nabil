<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Get Midtrans Snap Token for a transaction
     */
    public function getSnapToken($transactionDetails, $customerDetails, $itemDetails = [], $options = [])
    {
        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        ];

        if (!empty($itemDetails)) {
            $params['item_details'] = $itemDetails;
        }

        // Merge additional options (e.g., enabled_payments) into params at top-level
        if (!empty($options) && is_array($options)) {
            $params = array_merge($params, $options);
        }

        try {
            \Log::info('Midtrans Payload', $params);

            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            // Log error
            \Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle incoming Midtrans Webhook Notification
     */
    public function handleNotification()
    {
        try {
            return new Notification();
        } catch (\Exception $e) {
            \Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get transaction status from Midtrans API
     */
    public function getTransactionStatus($orderId)
    {
        try {
            return \Midtrans\Transaction::status($orderId);
        } catch (\Exception $e) {
            \Log::error('Midtrans Transaction Status Error: ' . $e->getMessage());
            return null;
        }
    }
}
