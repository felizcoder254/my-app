<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MpesaHelper
{
    public static function stkPush($phone, $amount, $reference)
    {
        $consumerKey = env('MPESA_CONSUMER_KEY');
        $consumerSecret = env('MPESA_CONSUMER_SECRET');
        $shortCode = env('MPESA_SHORTCODE');
        $passkey = env('MPESA_PASSKEY');
        $callbackUrl = env('MPESA_CALLBACK_URL');

        $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $stk_push_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        // Get access token
        $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
        $ch = curl_init($access_token_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ← Allow self-signed certs in dev

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            Log::error('M-Pesa cURL error while fetching token: ' . $error);
            throw new \Exception('cURL Error: ' . $error);
        }

        $data = json_decode($response, true);
        if (!isset($data['access_token'])) {
            Log::error('Failed to get access token from M-Pesa. Response: ' . $response);
            throw new \Exception('M-Pesa access token retrieval failed. Check credentials or API status.');
        }

        $access_token = $data['access_token'];

        // Generate timestamp and password
        $timestamp = date('YmdHis');
        $password = base64_encode($shortCode . $passkey . $timestamp);

        // STK Push payload
        $payload = [
            'BusinessShortCode' => $shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $shortCode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $callbackUrl,
            'AccountReference' => $reference,
            'TransactionDesc' => 'Payment'
        ];

        // Initiate STK Push
        $ch = curl_init($stk_push_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ← Allow self-signed certs in dev

        $stk_response = curl_exec($ch);

        if ($stk_response === false) {
            $error = curl_error($ch);
            Log::error('M-Pesa cURL error during STK push: ' . $error);
            throw new \Exception('cURL Error: ' . $error);
        }

        return json_decode($stk_response, true);
    }
}
