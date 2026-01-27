<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MpesaTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MpesaCallbackController extends Controller
{
    /**
     * Handle STK Push callback
     */
    public function stkCallback(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('STK Push Callback Received', $data);

            // Extract callback data
            $body = $data['Body'] ?? [];
            $stkCallback = $body['stkCallback'] ?? [];
            
            $merchantRequestId = $stkCallback['MerchantRequestID'] ?? null;
            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;
            $resultCode = $stkCallback['ResultCode'] ?? null;
            $resultDesc = $stkCallback['ResultDesc'] ?? null;
            $callbackMetadata = $stkCallback['CallbackMetadata'] ?? null;

            // Log transaction
            $transaction = MpesaTransaction::updateOrCreate(
                [
                    'checkout_request_id' => $checkoutRequestId,
                    'merchant_request_id' => $merchantRequestId,
                ],
                [
                    'transaction_type' => 'stk_push',
                    'result_code' => $resultCode,
                    'result_description' => $resultDesc,
                    'status' => $resultCode == 0 ? 'completed' : 'failed',
                    'callback_data' => $data,
                    'processed_at' => now(),
                ]
            );

            // Extract transaction details if successful
            if ($resultCode == 0 && $callbackMetadata) {
                $items = $callbackMetadata['Item'] ?? [];
                $metadata = [];
                foreach ($items as $item) {
                    $metadata[$item['Name']] = $item['Value'] ?? null;
                }

                $transaction->update([
                    'amount' => $metadata['Amount'] ?? null,
                    'mpesa_receipt_number' => $metadata['MpesaReceiptNumber'] ?? null,
                    'transaction_date' => isset($metadata['TransactionDate']) 
                        ? $this->parseTransactionDate($metadata['TransactionDate']) 
                        : null,
                    'phone_number' => $metadata['PhoneNumber'] ?? null,
                    'balance' => $metadata['Balance'] ?? null,
                    'metadata' => $metadata,
                ]);
            }

            // Return success response to M-PESA
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Callback processed successfully',
            ], 200);

        } catch (\Exception $e) {
            Log::error('STK Callback processing failed', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            // Still return success to M-PESA to prevent retries
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Callback received',
            ], 200);
        }
    }

    /**
     * Handle STK Push timeout
     */
    public function stkTimeout(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::warning('STK Push Timeout', $data);

            $body = $data['Body'] ?? [];
            $stkCallback = $body['stkCallback'] ?? [];
            
            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;
            $merchantRequestId = $stkCallback['MerchantRequestID'] ?? null;

            if ($checkoutRequestId) {
                MpesaTransaction::where('checkout_request_id', $checkoutRequestId)
                    ->update([
                        'status' => 'timeout',
                        'result_description' => 'Transaction timeout',
                        'callback_data' => $data,
                        'processed_at' => now(),
                    ]);
            }

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Timeout processed',
            ], 200);

        } catch (\Exception $e) {
            Log::error('STK Timeout processing failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Timeout received',
            ], 200);
        }
    }

    /**
     * Handle C2B validation
     */
    public function c2bValidate(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('C2B Validation Request', $data);

            // C2B validation should return a response indicating if the transaction is valid
            // You can add business logic here to validate the transaction
            
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted',
            ], 200);

        } catch (\Exception $e) {
            Log::error('C2B Validation failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Rejected',
            ], 200);
        }
    }

    /**
     * Handle C2B confirmation
     */
    public function c2bConfirm(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('C2B Confirmation Received', $data);

            // Extract C2B transaction data
            $transactionType = $data['TransactionType'] ?? null;
            $transId = $data['TransID'] ?? null;
            $transTime = $data['TransTime'] ?? null;
            $transAmount = $data['TransAmount'] ?? null;
            $businessShortCode = $data['BusinessShortCode'] ?? null;
            $billRefNumber = $data['BillRefNumber'] ?? null;
            $invoiceNumber = $data['InvoiceNumber'] ?? null;
            $orgAccountBalance = $data['OrgAccountBalance'] ?? null;
            $thirdPartyTransID = $data['ThirdPartyTransID'] ?? null;
            $msisdn = $data['MSISDN'] ?? null;
            $firstName = $data['FirstName'] ?? null;
            $middleName = $data['MiddleName'] ?? null;
            $lastName = $data['LastName'] ?? null;

            // Log transaction
            MpesaTransaction::updateOrCreate(
                [
                    'transaction_id' => $transId,
                ],
                [
                    'transaction_type' => 'c2b',
                    'amount' => $transAmount,
                    'phone_number' => $msisdn,
                    'account_reference' => $billRefNumber,
                    'business_short_code' => $businessShortCode,
                    'transaction_date' => isset($transTime) ? $this->parseTransactionDate($transTime) : null,
                    'status' => 'completed',
                    'result_code' => 0,
                    'result_description' => 'Payment received',
                    'metadata' => [
                        'first_name' => $firstName,
                        'middle_name' => $middleName,
                        'last_name' => $lastName,
                        'invoice_number' => $invoiceNumber,
                        'org_account_balance' => $orgAccountBalance,
                        'third_party_trans_id' => $thirdPartyTransID,
                    ],
                    'callback_data' => $data,
                    'processed_at' => now(),
                ]
            );

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Confirmation processed successfully',
            ], 200);

        } catch (\Exception $e) {
            Log::error('C2B Confirmation processing failed', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Confirmation received',
            ], 200);
        }
    }

    /**
     * Handle B2C result
     */
    public function b2cResult(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('B2C Result Received', $data);

            $result = $data['Result'] ?? [];
            $resultParameters = $result['ResultParameters'] ?? [];
            $resultCode = $result['ResultCode'] ?? null;
            $resultDesc = $result['ResultDesc'] ?? null;
            $originatorConversationId = $result['OriginatorConversationID'] ?? null;
            $conversationId = $result['ConversationID'] ?? null;
            $transactionId = $result['TransactionID'] ?? null;

            // Extract result parameters
            $resultData = [];
            if (isset($resultParameters['ResultParameter'])) {
                foreach ($resultParameters['ResultParameter'] as $param) {
                    $resultData[$param['Key']] = $param['Value'] ?? null;
                }
            }

            // Log transaction
            MpesaTransaction::updateOrCreate(
                [
                    'transaction_id' => $transactionId,
                    'conversation_id' => $conversationId,
                ],
                [
                    'transaction_type' => 'b2c',
                    'originator_conversation_id' => $originatorConversationId,
                    'result_code' => $resultCode,
                    'result_description' => $resultDesc,
                    'status' => $resultCode == 0 ? 'completed' : 'failed',
                    'amount' => $resultData['Amount'] ?? null,
                    'phone_number' => $resultData['ReceiverPartyPublicName'] ?? null,
                    'metadata' => $resultData,
                    'callback_data' => $data,
                    'processed_at' => now(),
                ]
            );

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Result processed successfully',
            ], 200);

        } catch (\Exception $e) {
            Log::error('B2C Result processing failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Result received',
            ], 200);
        }
    }

    /**
     * Handle B2C timeout
     */
    public function b2cTimeout(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::warning('B2C Timeout', $data);

            $result = $data['Result'] ?? [];
            $conversationId = $result['ConversationID'] ?? null;

            if ($conversationId) {
                MpesaTransaction::where('conversation_id', $conversationId)
                    ->update([
                        'status' => 'timeout',
                        'result_description' => 'Transaction timeout',
                        'callback_data' => $data,
                        'processed_at' => now(),
                    ]);
            }

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Timeout processed',
            ], 200);

        } catch (\Exception $e) {
            Log::error('B2C Timeout processing failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Timeout received',
            ], 200);
        }
    }

    /**
     * Parse M-PESA transaction date
     */
    protected function parseTransactionDate($dateString)
    {
        try {
            // M-PESA date format: YYYYMMDDHHmmss
            if (strlen($dateString) == 14) {
                $year = substr($dateString, 0, 4);
                $month = substr($dateString, 4, 2);
                $day = substr($dateString, 6, 2);
                $hour = substr($dateString, 8, 2);
                $minute = substr($dateString, 10, 2);
                $second = substr($dateString, 12, 2);
                
                return \Carbon\Carbon::create(
                    $year, $month, $day, $hour, $minute, $second
                );
            }
        } catch (\Exception $e) {
            Log::warning('Failed to parse transaction date', [
                'date' => $dateString,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }
}






