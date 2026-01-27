<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MpesaService
{
    protected $settings = [];
    protected $environment;
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortcode;
    protected $passkey;
    protected $securityCredential;

    public function __construct()
    {
        $this->loadSettings();
    }

    /**
     * Load M-PESA settings from database
     */
    protected function loadSettings()
    {
        try {
            $mpesaSettings = SystemSetting::where('group', 'mpesa_daraja')->get()->keyBy('key');
            foreach ($mpesaSettings as $key => $setting) {
                $this->settings[$key] = $setting->value;
            }
        } catch (\Exception $e) {
            Log::warning('Failed to load MPESA settings: ' . $e->getMessage());
        }

        $this->environment = $this->settings['environment'] ?? env('MPESA_ENVIRONMENT', 'sandbox');
        $this->consumerKey = $this->settings['consumer_key'] ?? env('MPESA_CONSUMER_KEY', '');
        $this->consumerSecret = $this->settings['consumer_secret'] ?? env('MPESA_CONSUMER_SECRET', '');
        $this->shortcode = $this->settings['shortcode'] ?? env('MPESA_SHORTCODE', '');
        $this->passkey = $this->settings['passkey'] ?? env('MPESA_PASSKEY', '');
        $this->securityCredential = $this->settings['security_credential'] ?? env('MPESA_SECURITY_CREDENTIAL', '');
    }

    /**
     * Get OAuth access token
     */
    public function getAccessToken()
    {
        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($this->consumerKey . ':' . $this->consumerSecret)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            if (isset($data['access_token'])) {
                return $data['access_token'];
            }
        }

        Log::error('Failed to get MPESA access token', [
            'http_code' => $httpCode,
            'response' => $response,
        ]);

        return null;
    }

    /**
     * Generate STK Push password
     */
    protected function generatePassword()
    {
        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        return ['password' => $password, 'timestamp' => $timestamp];
    }

    /**
     * Initiate STK Push (Lipa na M-PESA)
     */
    public function stkPush($phoneNumber, $amount, $accountReference = null, $transactionDesc = null, $callbackUrl = null)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token',
            ];
        }

        $passwordData = $this->generatePassword();
        $shortcodeType = $this->settings['shortcode_type'] ?? 'paybill';
        $transactionType = ($shortcodeType === 'till_number' || $shortcodeType === 'buy_goods')
            ? 'CustomerBuyGoodsOnline'
            : 'CustomerPayBillOnline';

        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
            : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $callbackUrl = $callbackUrl ?? $this->settings['stk_callback_url'] ?? url('/api/mpesa/stk/callback');

        $requestBody = [
            'BusinessShortCode' => (int)$this->shortcode,
            'Password' => $passwordData['password'],
            'Timestamp' => $passwordData['timestamp'],
            'TransactionType' => $transactionType,
            'Amount' => (int)$amount,
            'PartyA' => $phoneNumber,
            'PartyB' => (int)$this->shortcode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => $callbackUrl,
            'AccountReference' => $accountReference ?? 'Payment',
            'TransactionDesc' => $transactionDesc ?? 'Payment',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode === 200 && isset($data['ResponseCode']) && $data['ResponseCode'] == '0') {
            return [
                'success' => true,
                'data' => $data,
                'message' => $data['CustomerMessage'] ?? 'STK Push initiated successfully',
            ];
        }

        return [
            'success' => false,
            'message' => $data['errorMessage'] ?? $data['ResponseDescription'] ?? 'STK Push failed',
            'data' => $data,
        ];
    }

    /**
     * Query STK Push transaction status
     */
    public function queryStkStatus($checkoutRequestId)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token',
            ];
        }

        $passwordData = $this->generatePassword();

        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query'
            : 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';

        $requestBody = [
            'BusinessShortCode' => (int)$this->shortcode,
            'Password' => $passwordData['password'],
            'Timestamp' => $passwordData['timestamp'],
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode === 200) {
            return [
                'success' => true,
                'data' => $data,
            ];
        }

        return [
            'success' => false,
            'message' => $data['errorMessage'] ?? 'Query failed',
            'data' => $data,
        ];
    }

    /**
     * Register C2B URLs
     */
    public function registerC2bUrls($validationUrl, $confirmationUrl, $shortcode = null)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token',
            ];
        }

        $shortcode = $shortcode ?? $this->settings['c2b_shortcode'] ?? $this->shortcode;

        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl'
            : 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        $requestBody = [
            'ShortCode' => (int)$shortcode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode === 200 && isset($data['ResponseCode']) && $data['ResponseCode'] == '0') {
            return [
                'success' => true,
                'data' => $data,
                'message' => 'C2B URLs registered successfully',
            ];
        }

        return [
            'success' => false,
            'message' => $data['errorMessage'] ?? 'C2B registration failed',
            'data' => $data,
        ];
    }

    /**
     * Initiate B2C payment
     */
    public function b2cPayment($phoneNumber, $amount, $remarks = null, $occasion = null)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token',
            ];
        }

        $initiatorUsername = $this->settings['b2c_initiator_username'] ?? '';
        $securityCredential = $this->settings['b2c_initiator_password'] ?? $this->securityCredential;
        $shortcode = $this->settings['b2c_shortcode'] ?? $this->shortcode;
        $resultUrl = $this->settings['b2c_result_url'] ?? url('/api/mpesa/b2c/result');
        $timeoutUrl = $this->settings['b2c_timeout_url'] ?? url('/api/mpesa/b2c/timeout');
        $remarks = $remarks ?? $this->settings['b2c_remarks'] ?? 'Payment';

        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest'
            : 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';

        $requestBody = [
            'InitiatorName' => $initiatorUsername,
            'SecurityCredential' => $securityCredential,
            'CommandID' => 'BusinessPayment',
            'Amount' => (int)$amount,
            'PartyA' => (int)$shortcode,
            'PartyB' => $phoneNumber,
            'Remarks' => $remarks,
            'QueueTimeOutURL' => $timeoutUrl,
            'ResultURL' => $resultUrl,
            'Occasion' => $occasion ?? 'Payment',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode === 200 && isset($data['ResponseCode']) && $data['ResponseCode'] == '0') {
            return [
                'success' => true,
                'data' => $data,
                'message' => 'B2C payment initiated successfully',
            ];
        }

        return [
            'success' => false,
            'message' => $data['errorMessage'] ?? 'B2C payment failed',
            'data' => $data,
        ];
    }

    /**
     * Query account balance
     */
    public function accountBalance()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token',
            ];
        }

        $initiatorUsername = $this->settings['b2c_initiator_username'] ?? '';
        $securityCredential = $this->settings['b2c_initiator_password'] ?? $this->securityCredential;
        $shortcode = $this->settings['b2c_shortcode'] ?? $this->shortcode;
        $resultUrl = $this->settings['b2c_result_url'] ?? url('/api/mpesa/b2c/result');
        $timeoutUrl = $this->settings['b2c_timeout_url'] ?? url('/api/mpesa/b2c/timeout');

        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query'
            : 'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';

        $requestBody = [
            'Initiator' => $initiatorUsername,
            'SecurityCredential' => $securityCredential,
            'CommandID' => 'AccountBalance',
            'PartyA' => (int)$shortcode,
            'IdentifierType' => '4',
            'Remarks' => 'Balance Query',
            'QueueTimeOutURL' => $timeoutUrl,
            'ResultURL' => $resultUrl,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode === 200 && isset($data['ResponseCode']) && $data['ResponseCode'] == '0') {
            return [
                'success' => true,
                'data' => $data,
                'message' => 'Balance query initiated successfully',
            ];
        }

        return [
            'success' => false,
            'message' => $data['errorMessage'] ?? 'Balance query failed',
            'data' => $data,
        ];
    }

    /**
     * Reverse transaction
     */
    public function reverseTransaction($transactionId, $amount, $receiverParty = null, $remarks = null)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token',
            ];
        }

        $initiatorUsername = $this->settings['b2c_initiator_username'] ?? '';
        $securityCredential = $this->settings['b2c_initiator_password'] ?? $this->securityCredential;
        $shortcode = $this->settings['b2c_shortcode'] ?? $this->shortcode;
        $receiverParty = $receiverParty ?? $shortcode;
        $resultUrl = $this->settings['b2c_result_url'] ?? url('/api/mpesa/b2c/result');
        $timeoutUrl = $this->settings['b2c_timeout_url'] ?? url('/api/mpesa/b2c/timeout');

        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/reversal/v1/request'
            : 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';

        $requestBody = [
            'Initiator' => $initiatorUsername,
            'SecurityCredential' => $securityCredential,
            'CommandID' => 'TransactionReversal',
            'TransactionID' => $transactionId,
            'Amount' => (int)$amount,
            'ReceiverParty' => (string)$receiverParty,
            'RecieverIdentifierType' => '4',
            'ResultURL' => $resultUrl,
            'QueueTimeOutURL' => $timeoutUrl,
            'Remarks' => $remarks ?? 'Reversal',
            'Occasion' => 'Reversal',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode === 200 && isset($data['ResponseCode']) && $data['ResponseCode'] == '0') {
            return [
                'success' => true,
                'data' => $data,
                'message' => 'Reversal initiated successfully',
            ];
        }

        return [
            'success' => false,
            'message' => $data['errorMessage'] ?? 'Reversal failed',
            'data' => $data,
        ];
    }
}






