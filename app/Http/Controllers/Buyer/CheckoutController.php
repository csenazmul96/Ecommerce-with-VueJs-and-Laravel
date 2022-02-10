<?php

namespace App\Http\Controllers\Buyer;

use App\Model\Order;
use App\Model\AuthorizeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Omnipay\Common\CreditCard as NewCreditCard;
use Omnipay\Omnipay;
use Square\SquareClient;
use Square\Environment;
use SquareConnect\Configuration;
use SquareConnect\ApiClient;
use DB;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
class CheckoutController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    }
    public function StripeAuthorizeAndCapture(Request $request)
    {
        $order = Order::where('id', $request->id)->with('user', 'items')->first();
        $date = decrypt($order->card_expire);
        $new_date = explode('/',$date);
        $orderDesc = '';
        $oInc = 1;
        foreach ($order->items as $oitem){
            $orderDesc.= $oInc.'. Style No: '. $oitem->style_no .', ';
            $oInc = $oInc + 1;
        }
        try {
            $cardToken = $this->stripe->tokens->create([
                'card' => [
                    'number' => decrypt($order->card_number),
                    'exp_month' => $new_date[0],
                    'exp_year' => $new_date[1],
                    'cvc' => decrypt($order->card_cvc),
                    'name' => decrypt($order->card_full_name),
                ]
            ]);
            $order->token = $cardToken->id;
            $order->save();

            $amount = (float)(implode('.', explode('.', $order->total)));
            $response = $this->stripe->charges->create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'source' => $cardToken->id,
                'description' => $orderDesc,
                'capture' => true,
                'shipping' => [
                    "address" => [
                        "city" => $order->shipping_city,
                        "country" => $order->shipping_country,
                        "line1" => $order->shipping_address,
                        "postal_code" => $order->shipping_zip,
                        "state" => $order->shipping_state ?? $order->shipping_state_text
                    ],
                    "name" => $order->name,
                ]
            ]);


            $order->payment_id = $response->id ?? null;
            $data['transaction_code'] = $response->balance_transaction ?? null;
            $data['authorized_time'] = Carbon::now();
            $data['status'] = 'Success';
            if ($response->status === 'succeeded') {
                $order->aStatus = 'Success';
                $order->authorize_info = json_encode(['status' => 'Success', 'message' => 'Purchase transaction was successful!', 'response_data' => $data]);
                $order->save();
                return response()->json(['success' => true, 'message' => 'Purchase transaction was successful!', 'response_data' => $data]);
            } else {
                $order->aStatus = 'Failed';
                $order->authorize_info = json_encode(['status' => 'Failed', 'message' => 'Purchase transaction was successful!']);
                $order->save();
                return response()->json(['success' => false, 'message' => 'Purchase transaction was Unsuccessful!', 'response_data' => $data]);
            }

        }catch (\Exception $e){
            $order->aStatus = 'Failed';
            $order->authorize_info = json_encode(['status' => 'Failed', 'message' => 'Purchase transaction was Not successful!']);
            $order->save();
            return response()->json(['success' => false, 'message' => 'Exception caught while attempting purchase. Error Message: ' . $e->getMessage(), 'exception_type' => get_class($e)]);
        }
    }
    /* Authorize .NET */
    public function authorizeAndCapture(Request $request) {
        $orderId = $request->order;


        $order = Order::where('id', $orderId)->with('user', 'items')->first();

        $orderDesc = 'Style No: ';
        foreach ($order->items as $oitem){
            $orderDesc.= $oitem->style_no .', ';
        }

        $invoiceId = $order->order_number;

        $authorize_info = $order->authorize_info;

        $cardNumber = '';
        $cardFullName = '';
        $cardExpire = '';
        $cardCvc = '';

        try {
            $cardNumber = decrypt($order->card_number);
            $cardFullName = decrypt($order->card_full_name);
            $cardExpire = decrypt($order->card_expire);
            $cardCvc = decrypt($order->card_cvc);
        } catch (DecryptException $e) {

        }
        $fName =  $order->user->first_name;
        $lName =  $order->user->last_name;
        $b_address =  $order->billing_address;
        $b_city =  $order->billing_city;
        $b_state =  $order->billing_state;
        $b_zip =  $order->billing_zip;
        $b_country =  $order->billing_country;
        $user_id = $order->user->id;
        $user_email = $order->user->email;


        $s_address =  $order->shipping_address;
        $s_city =  $order->shipping_city;
        $s_state =  $order->shipping_state;
        $s_zip =  $order->shipping_zip;
        $s_country =  $order->shipping_country;


        $amount = $order->total;
        $expireData = explode('/', $cardExpire);
        $exYear = 2000 + intval($expireData[1]);
        $exMonth = $expireData[0];
        $expiry = $exYear.'-'.$exMonth;

        // Common setup for API credentials
        // define("AUTHORIZENET_LOG_FILE", storage_path('logs/laravel.log'));
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.login'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.key'));
        $refId = 'ref'.time();


        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($expiry);
        $creditCard->setCardCode($cardCvc);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);


        // Create order information
        $orderInfo = new AnetAPI\OrderType();
        $orderInfo->setInvoiceNumber($invoiceId);
        $orderInfo->setDescription($orderDesc);



        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($fName);
        $customerAddress->setLastName($lName);
        //$customerAddress->setCompany("Souveniropolis");
        $customerAddress->setAddress($b_address);
        $customerAddress->setCity($b_city);
        $customerAddress->setState($b_state);
        $customerAddress->setZip($b_zip);
        $customerAddress->setCountry($b_country);


        // Set the customer's Shipping Address
        $customerSAddress = new AnetAPI\CustomerAddressType();
        $customerSAddress->setFirstName($fName);
        $customerSAddress->setLastName($lName);
        //$customerAddress->setCompany("Souveniropolis");
        $customerSAddress->setAddress($s_address);
        $customerSAddress->setCity($s_city);
        $customerSAddress->setState($s_state);
        $customerSAddress->setZip($s_zip);
        $customerSAddress->setCountry($s_country);

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($user_id);
        $customerData->setEmail($user_email);

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        //$transactionRequestType->setTransactionType("authOnlyTransaction");
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        //$transactionRequestType->setShipping($amount);
        $transactionRequestType->setAmount(round($amount, 2));
        $transactionRequestType->setOrder($orderInfo);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setShipTo($customerSAddress);
        $transactionRequestType->setCustomer($customerData);


        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(config('services.authorize.endpoint'));

        $status_avs_hint = array("A" => 'Address (street) matches, ZIP code does not', 'B' => 'Address information not provided for AVS check',
            'E' => 'AVS error', 'G' => 'Non-U.S. card issuing bank', 'N' => 'No match on address (street) and ZIP code',
            'P' => 'AVS not applicable for this transaction', 'R' => 'Retry – System unavailable or timed out', 'S' => 'Service not supported by issuer',
            'U' => 'Address information is unavailable', 'W' => '9 digit ZIP code matches, address (street) does not','X'=>'Street address and 9-digit postal code match', 'Y' => 'Address (street) and 5 digit ZIP code match',
            'Z' => '5 digit ZIP matches, Address (Street) does not'
        );

        $status_cvv_hint = array('M' => 'Successful Match', 'N' => 'Does NOT Match', 'P'=> 'Is NOT Processed',
            'S' => 'Should be on card, but is not indicated', 'U' => 'Issuer is not certified or has not provided encryption key'
        );

        $transactionInfo = (object) array();
        // Log::error('authorize error : ' . json_encode($response));
        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode()) {
                //pr($response);
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $transactionInfo->status = 'Success';
                    $transactionInfo->message = date('m-d-Y H:i:s') . " Authorized and Captured with ID: " . $tresponse->getTransId();
                    $transactionInfo->transaction_code = $tresponse->getTransId();
                    $transactionInfo->transaction_response_code = $tresponse->getResponseCode();
                    $transactionInfo->authorized_time = date('Y-m-d H:i:s');
                    $transactionInfo->message_code = $tresponse->getMessages()[0]->getCode();
                    $transactionInfo->auth_code = $tresponse->getAuthCode();
                    $transactionInfo->avs_code = $tresponse->getAvsResultCode();
                    $transactionInfo->cvv_code = $tresponse->getCvvResultCode();
                    $transactionInfo->desc = $tresponse->getMessages()[0]->getDescription();
                    if($transactionInfo->avs_code){
                        $transactionInfo->avs_message = $status_avs_hint[$transactionInfo->avs_code];
                    }
                    if($transactionInfo->cvv_code){
                        $transactionInfo->cvv_message = $status_cvv_hint[$transactionInfo->cvv_code];
                    }
                } else {
                    $transactionInfo->status = 'Failed';
                    $transactionInfo->message = date('m-d-Y H:i:s') . ' Auth Failed';
                    if(!empty($tresponse)){
                        $transactionInfo->error_code = $tresponse->getErrors()[0]->getErrorCode();
                        $transactionInfo->error_message = $tresponse->getErrors()[0]->getErrorText();
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                $transactionInfo->status = 'Failed';
                $transactionInfo->message = date('m-d-Y H:i:s') . ' Auth Failed';
                $tresponse = $response->getTransactionResponse();

                if(!empty($tresponse)){
                    $transactionInfo->error_code = $tresponse->getErrors()[0]->getErrorCode();
                    $transactionInfo->error_message = $tresponse->getErrors()[0]->getErrorText();
                }
            }
        } else {
            $transactionInfo->status = 'Failed';
            $transactionInfo->message = 'No Response(Failed)';
        }

        $tInfo = json_encode($transactionInfo);

        DB::table('orders')->where('id', $orderId)->update(['authorize_info' => $tInfo,'aStatus'=>$transactionInfo->status]);

        //DB::table('orders')->where('id', $orderId)->update(['authorize_info' => $tInfo]);
        AuthorizeLog::create(['order_id' => $orderId,'authorize_type'=>'ShippingCharge',
            'authorize_info'=>$tInfo,'status'=>$transactionInfo->status,'responseCode'=>(isset($transactionInfo->transaction_response_code))?$transactionInfo->transaction_response_code:null,
            'authorize_message'=>(isset($transactionInfo->message_code))?$transactionInfo->message_code:null]);
        $redirectUrl = route('admin_order_details', ['order' => $orderId]);
        return response()->json(['success' => true, 'url' => $redirectUrl, 'transaction' => $transactionInfo]);
    }

    public function refundPayment(Request $request)
    {
        $orderId = $request->order;
        $order = Order::where('id', $orderId)->with('user', 'items')->first();
        $transactionId = $order->payment_id;
        try {
            $refund = $this->stripe->refunds->create(['charge' => $transactionId]);
            $data['transaction_code'] =   null;
            $data['authorized_time'] = null;
            $data['status'] = 'Refund';
            $order->aStatus = 'Refund';
            $order->authorize_info = json_encode(['status' => 'Refund', 'message' => 'Refund successful!']);
            $order->save();
            return response()->json(['success' => true, 'message' => 'refund response data.', 'data' => 'refunded successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Someyhing went wrong please check card number or try another'], 422);
        }
    }

    public function refundPaymentAuthorized(Request $request) {

        $orderId = $request->order;
        $order = Order::where('id', $orderId)->with('user', 'items')->first();

        $orderDesc = 'Style No: ';
        foreach ($order->items as $oitem){
            $orderDesc.=  $oitem->style_no .', ';
        }

        $invoiceId = $order->order_number;

        $authorize_info = json_decode($order->authorize_info, true);
        $transactionid = (isset($authorize_info['transaction_code']))? $authorize_info['transaction_code']:null;

        $cardNumber = '';
        $cardFullName = '';
        $cardExpire = '';
        $cardCvc = '';

        try {
            $cardNumber = decrypt($order->card_number);
            $cardFullName = decrypt($order->card_full_name);
            $cardExpire = decrypt($order->card_expire);
            $cardCvc = decrypt($order->card_cvc);
        } catch (DecryptException $e) {

        }

        $fName =  $order->user->first_name;
        $lName =  $order->user->last_name;
        $b_address =  $order->billing_address;
        $b_city =  $order->billing_city;
        $b_state =  $order->billing_state;
        $b_zip =  $order->billing_zip;
        $b_country =  $order->billing_country;
        $user_id = $order->user->id;
        $user_email = $order->user->email;

        $s_address =  $order->shipping_address;
        $s_city =  $order->shipping_city;
        $s_state =  $order->shipping_state;
        $s_zip =  $order->shipping_zip;
        $s_country =  $order->shipping_country;

        $amount = $order->total;

        $expireData = explode('/', $cardExpire);
        $exYear = 2000 + intval($expireData[1]);
        $exMonth = $expireData[0];
        $expiry = $exYear.'-'.$exMonth;

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.login'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.key'));
        $refId = 'ref'.time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($expiry);
        $creditCard->setCardCode($cardCvc);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create order information
        $orderInfo = new AnetAPI\OrderType();
        $orderInfo->setInvoiceNumber($invoiceId);
        $orderInfo->setDescription($orderDesc);

        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($fName);
        $customerAddress->setLastName($lName);
        // $customerAddress->setCompany("Souveniropolis");
        $customerAddress->setAddress($b_address);
        $customerAddress->setCity($b_city);
        $customerAddress->setState($b_state);
        $customerAddress->setZip($b_zip);
        $customerAddress->setCountry($b_country);

        // Set the customer's Shipping Address
        $customerSAddress = new AnetAPI\CustomerAddressType();
        $customerSAddress->setFirstName($fName);
        $customerSAddress->setLastName($lName);
        $customerSAddress->setAddress($s_address);
        $customerSAddress->setCity($s_city);
        $customerSAddress->setState($s_state);
        $customerSAddress->setZip($s_zip);
        $customerSAddress->setCountry($s_country);

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($user_id);
        $customerData->setEmail($user_email);

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("refundTransaction");
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setRefTransId($transactionid);
        $transactionRequestType->setAmount(round($amount, 2));
        $transactionRequestType->setOrder($orderInfo);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setShipTo($customerSAddress);
        $transactionRequestType->setCustomer($customerData);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        //  $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(config('services.authorize.endpoint'));

        $status_avs_hint = array("A" => 'Address (street) matches, ZIP code does not', 'B' => 'Address information not provided for AVS check',
            'E' => 'AVS error', 'G' => 'Non-U.S. card issuing bank', 'N' => 'No match on address (street) and ZIP code',
            'P' => 'AVS not applicable for this transaction', 'R' => 'Retry – System unavailable or timed out', 'S' => 'Service not supported by issuer',
            'U' => 'Address information is unavailable', 'W' => '9 digit ZIP code matches, address (street) does not','X'=>'Street address and 9-digit postal code match', 'Y' => 'Address (street) and 5 digit ZIP code match',
            'Z' => '5 digit ZIP matches, Address (Street) does not'
        );

        $status_cvv_hint = array('M' => 'Successful Match', 'N' => 'Does NOT Match', 'P'=> 'Is NOT Processed',
            'S' => 'Should be on card, but is not indicated', 'U' => 'Issuer is not certified or has not provided encryption key'
        );

        $transactionInfo = (object) array();
        $aStatus = '';
        if ($response != null) {
            if ($response->getMessages()->getResultCode()) {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $transactionInfo->status = 'Refund';
                    $transactionInfo->card_type = $tresponse->getAccountType();
                    $transactionInfo->card_number = $tresponse->getAccountNumber();
                    $transactionInfo->card_holder_name = $cardFullName;
                    $transactionInfo->authorized_time = date('Y-m-d H:i:s');
                    $transactionInfo->authorized_amount = $amount;
                    $transactionInfo->message = 'Refund (Success)';
                    $transactionInfo->refId = $refId;
                    $transactionInfo->transaction_code = $tresponse->getTransId();
                    $transactionInfo->transaction_response_code = $tresponse->getResponseCode();
                    $transactionInfo->message_code = $tresponse->getMessages()[0]->getCode();
                    $transactionInfo->auth_code = $tresponse->getAuthCode();
                    $transactionInfo->avs_code = $tresponse->getAvsResultCode();
                    $transactionInfo->cvv_code = $tresponse->getCvvResultCode();
                    $transactionInfo->desc = $tresponse->getMessages()[0]->getDescription();
                    if($transactionInfo->avs_code){
                        $transactionInfo->avs_message = $status_avs_hint[$transactionInfo->avs_code];
                    }
                    if($transactionInfo->cvv_code){
                        $transactionInfo->cvv_message = $status_cvv_hint[$transactionInfo->cvv_code];
                    }
                    $transactionInfo->captured = false;
                    $aStatus = 'Refund';
                } else {
                    $transactionInfo->status = 'Failed';
                    $transactionInfo->message = 'Refund (Failed)';
                    if(!empty($tresponse)){
                        $transactionInfo->error_code = $tresponse->getErrors()[0]->getErrorCode();
                        $transactionInfo->error_message = $tresponse->getErrors()[0]->getErrorText();
                    }
                    $transactionInfo->message_code = $response->getTransactionResponse();
                    $transactionInfo->transaction_response_code = $response->getTransactionResponse();
                    $aStatus = 'Failed';
                }
                // Or, print errors if the API request wasn't successful
            } else {
                $transactionInfo->status = 'Failed';
                $transactionInfo->message = 'Refund (Failed)';
                $tresponse = $response->getTransactionResponse();
                if(!empty($tresponse)){
                    $transactionInfo->error_code = $tresponse->getErrors()[0]->getErrorCode();
                    $transactionInfo->error_message = $tresponse->getErrors()[0]->getErrorText();
                }
                $transactionInfo->message_code = $response->getTransactionResponse()->getMessages();
                $transactionInfo->transaction_response_code = $response->getTransactionResponse()->getResponseCode();
                $aStatus = 'Failed';
            }
        } else {
            $transactionInfo->status = 'Failed';
            $transactionInfo->message = 'No Response(Failed)';
            $transactionInfo->message_code = $response->getTransactionResponse()->getMessages();
            $transactionInfo->transaction_response_code = $response->getTransactionResponse()->getResponseCode();
            $aStatus = 'Failed';
        }

        $tInfo = json_encode($transactionInfo);

        DB::table('orders')->where('id', $orderId)->update(['authorize_info' => $tInfo,'aStatus'=> $aStatus]);

        AuthorizeLog::create(['order_id' => $order->id,'authorize_type'=>'Authorize',
            'authorize_info'=>$tInfo,'status'=>$transactionInfo->status,'responseCode'=>(isset($transactionInfo->transaction_response_code))?$transactionInfo->transaction_response_code:null,
            'authorize_message'=>(isset($transactionInfo->message_code))?$transactionInfo->message_code:null]);

        $redirectUrl = route('admin_order_details', ['order' => $orderId]);
        return response()->json(['success' => true, 'url' => $redirectUrl, 'transaction' => $transactionInfo]);
    }
}
