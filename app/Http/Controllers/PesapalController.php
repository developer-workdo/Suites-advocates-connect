<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PesapalController extends Controller
{
    protected $currency;
    protected $currencySymbol;
    protected $consumerKey;
    protected $consumerSecret;
    protected $url;
    protected $token;
    protected $ipn_id;

    public function __construct(Request $request)
    {
        $admin_payment_setting  = Utility::payment_settings();
        $this->currency         = $admin_payment_setting['currency'] ?? '';
        $this->currencySymbol   = $admin_payment_setting['currency_symbol'] ?? '';
        $this->consumerKey      = $admin_payment_setting['pesapal_key'] ?? '';
        $this->consumerSecret   = $admin_payment_setting['pesapal_secret'] ?? '';
        $this->url              = $admin_payment_setting['pesapal_api_url'] ?? '';
        $this->token            = session('pesapal_token');
        $this->ipn_id           = '';
    }

    public function submitPaymentOrder($orderDetails)
    {
        if (empty($this->token)) {
            return redirect()->back()->with('error', __('Authentication token is missing.'));
        }

        $client = new Client();
        $url = $this->url . 'Transactions/SubmitOrderRequest';

        $headers = [
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ];

        $body = [
            "id"              => $orderDetails['id'],
            "currency"        => $orderDetails['currency'],
            "amount"          => $orderDetails['amount'],
            "description"     => $orderDetails['description'],
            "callback_url"    => $orderDetails['callback_url'],
            "notification_id" => $this->ipn_id,
            "billing_address" => [
                "email_address" => $orderDetails['email_address'],
                "phone_number"  => $orderDetails['phone_number'],
                "country_code"  => $orderDetails['country_code'],
                "first_name"    => $orderDetails['first_name'],
                "middle_name"   => $orderDetails['middle_name'],
                "last_name"     => $orderDetails['last_name'],
                "line_1"        => $orderDetails['line_1'],
                "line_2"        => $orderDetails['line_2'],
                "city"          => $orderDetails['city'],
                "state"         => $orderDetails['state'],
                "postal_code"   => $orderDetails['postal_code'],
                "zip_code"      => $orderDetails['zip_code']
            ]
        ];

        $response = $client->post($url, [
            'headers' => $headers,
            'json'    => $body,
        ]);

        $resBody = json_decode($response->getBody()->getContents(), true);
        if ($resBody['status'] == '200') {
            return $resBody;
        } else {
            return redirect()->back()->with('error', $resBody['error']['message']);
        }
    }

    public function planPayWithPesaPal(Request $request)
    {
        $response = $this->authenticatePesaPal();

        $authUser = Auth::user();
        $planID   = Crypt::decrypt($request->plan_id);
        $plan     = Plan::find($planID);
        if (!$plan) {
            return redirect()->back()->with('error', __('Plan not found.'));
        }
        $price = (int) $plan->price;
        if (!empty($request->coupon)) {
            $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
            if (!empty($coupons)) {
                $usedCoupun     = $coupons->used_coupon();
                $discountValue  = ($plan->price / 100) * $coupons->discount;
                $price          = $plan->price - $discountValue;

                if ($usedCoupun >= $coupons->limit) {
                    return redirect()->back()->with('error', __('This coupon code has expired.'));
                }
            } else {
                return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
            }
        }

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return $response;
        }
        if (is_array($response) && $response['status'] == '200') {
            $data = [
                "id"              => $orderID,
                "currency"        => !empty($this->currency) ? $this->currency : 'UGX',
                "currency_symbol" => !empty($this->currencySymbol) ? $this->currencySymbol : 'UGX',
                "amount"          => $price,
                "description"     => "Plan - " . $plan->name,
                "callback_url"    => route('pesapal.payment.callback', [
                    'userId'      => $authUser->id,
                    'planId'      => $plan->id,
                ]),
                "notification_id" => '',
                "email_address"   => $authUser->email,
                "phone_number"    => $authUser->phone_number ?? "",
                "country_code"    => "UG",
                "first_name"      => $authUser->name,
                "middle_name"     => "",
                "last_name"       => "",
                "line_1"          => "",
                "line_2"          => "",
                "city"            => "",
                "state"           => "",
                "postal_code"     => "",
                "zip_code"        => "",
                "metadata"        => ["order_id" => $orderID],
            ];
            $orderResponse = $this->submitPaymentOrder($data);
            if ($orderResponse instanceof \Illuminate\Http\RedirectResponse) {
                return $orderResponse;
            }
            if (is_array($orderResponse) && $orderResponse['status'] == '200') {
                Order::create(
                    [
                        'order_id'                  => $orderID,
                        'name'                      => $request->name,
                        'card_number'               => '',
                        'card_exp_month'            => '',
                        'card_exp_year'             => '',
                        'plan_name'                 => $plan->name,
                        'plan_id'                   => $plan->id,
                        'price'                     => $price,
                        'price_currency'            => isset($data['currency']) ? $data['currency'] : '',
                        'price_currency_symbol'     => isset($data['currency_symbol']) ? $data['currency_symbol'] : '',
                        'txn_id'                    => '',
                        'pesapal_order_tracking_id' => isset($orderResponse['order_tracking_id']) ? $orderResponse['order_tracking_id'] : '',
                        'payment_status'            => 'Pending',
                        'payment_type'              => __('PesaPal'),
                        'receipt'                   => null,
                        'user_id'                   => $authUser->id,
                    ]
                );
                if (!empty($request->coupon)) {
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $authUser->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order  = $orderID;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
                return redirect()->away($orderResponse['redirect_url']);
            } else {
                return redirect()->back()->with('error', $orderResponse['message'] ?? 'Something went wrong');
            }
        } else {
            return redirect()->back()->with('error', $orderResponse['message'] ?? 'Something went wrong');
        }
    }

    public function paymentCallback(Request $request)
    {
        $data           = $request->all();
        $orderResponse  = $this->getTransactionStatus($data);
        $authUser       = User::find($data['userId']);
        $plan           = Plan::find($data['planId']);
        if ($orderResponse instanceof \Illuminate\Http\RedirectResponse) {
            return $orderResponse;
        }
        if (is_array($orderResponse) && $orderResponse['status'] == '200') {
            $order =  Order::where('pesapal_order_tracking_id', $orderResponse['order_tracking_id'])->first();
            if ($order) {
                $order->price                      = $orderResponse['amount'] ?? '';
                $order->price_currency             = $orderResponse['currency'] ?? '';
                $order->pesapal_confirmation_code  = $orderResponse['confirmation_code'] ?? null;
                $order->pesapal_order_tracking_id  = $orderResponse['order_tracking_id'] ?? '';
                $order->card_number                = $orderResponse['payment_account'] ?? '';
                $order->pesapal_merchant_reference = $orderResponse['merchant_reference'] ?? null;
                $order->pesapal_account_number     = $orderResponse['account_number'] ?? null;

                if ($orderResponse['payment_status_description'] == 'Completed') {
                    $order->payment_status = 'Succeeded';
                } else {
                    $order->payment_status = $orderResponse['payment_status_description'];
                }

                $order->save();
                $assignPlan = $authUser->assignPlan($plan->id);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            }
        } else {
            return redirect()->route('home')->with('error', $orderResponse['description']);
        }
    }

    public function showPesaPalRefundForm($id)
    {
        $order = Order::find($id);
        $admin_payment_setting = Utility::payment_settings();
        return view('order.pesapal-refund', compact('order', 'admin_payment_setting'));
    }

    public function pesapalRefundRequest(Request $request, $id)
    {
        $response = $this->authenticatePesaPal();

        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return $response;
        }
        if (is_array($response) && $response['status'] == '200') {
            $order = Order::find($id);
            if (!$order || $order->payment_status != 'Succeeded') {
                return redirect()->route('home')->with('error', 'Invalid transaction or already refunded.');
            }
            $user = User::find($order->user_id);
            if (empty($user)) {
                return redirect()->route('home')->with('error', 'The user assigned to this plan was not found.');
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'refund_amount' => 'required|numeric|lte:' . $order->price,
                    'refund_reason' => 'required|string|max:255',
                ],
                [
                    'refund_amount.required' => 'The refund amount is required.',
                    'refund_amount.numeric'  => 'The refund amount must be a valid number.',
                    'refund_amount.lte'      => 'The refund amount must not exceed the order price.',
                    'refund_reason.required' => 'The reason for the refund is required.',
                    'refund_reason.string'   => 'The reason for the refund must be a valid string.',
                    'refund_reason.max'      => 'The refund reason must not exceed 255 characters.',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $refundAmount = $request->input('refund_amount', $order->price);
            if ($refundAmount <= 0 || $refundAmount > $order->price) {
                return redirect()->route('home')->with('error', 'The refund amount must not exceed the order price;.');
            }
            try {
                if ($order && $order->payment_status == 'Succeeded') {
                    $refundRquest = $this->refundRequest($order, $user, $refundAmount);
                    if ($refundRquest instanceof \Illuminate\Http\RedirectResponse) {
                        return $refundRquest;
                    }
                    if (is_array($refundRquest) && $refundRquest['status'] == '200') {
                        $order->pesapal_refund_status = 'completed';
                        $order->save();
                        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                        Order::create(
                            [
                                'order_id'                  => $orderID,
                                'name'                      => Auth::user()->name,
                                'card_number'               => '',
                                'card_exp_month'            => '',
                                'card_exp_year'             => '',
                                'plan_name'                 => $order->plan_name,
                                'plan_id'                   => $order->plan_id,
                                'price'                     => $refundAmount,
                                'price_currency'            => $order->price_currency ?? 'USD',
                                'price_currency_symbol'     => $order->price_currency_symbol ?? '$',
                                'txn_id'                    => '',
                                'pesapal_order_tracking_id' => isset($orderResponse['order_tracking_id']) ? $orderResponse['order_tracking_id'] : '',
                                'payment_status'            => 'Succeeded',
                                'payment_type'              => __('PesaPal Refund'),
                                'receipt'                   => null,
                                'pesapal_refund_status'     => 'completed',
                                'user_id'                   => Auth::user()->id,
                            ]
                        );
                        return redirect()->back()->with('success', $refundRquest['message']);
                    } else {
                        return redirect()->back()->with('error', $refundRquest['message']);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Pesapal Refund Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Refund request failed.');
            }
        } else {
            return redirect()->back()->with('error', 'PesaPal Authentication failed');
        }
    }

    private function authenticatePesaPal()
    {
        $client = new Client();
        $url = $this->url . 'Auth/RequestToken';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $body = [
            'consumer_key' => $this->consumerKey,
            'consumer_secret' => $this->consumerSecret
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            $resBody = json_decode($response->getBody()->getContents(), true);

            if ($resBody['status'] == '200') {
                $this->token = $resBody['token'];
                session(['pesapal_token' => $this->token]);

                if (empty($this->ipn_id)) {
                    $registerIPN = $this->registerIPN();

                    if ($registerIPN instanceof \Illuminate\Http\RedirectResponse) {
                        return $registerIPN;
                    }
                    if (is_array($registerIPN) && $registerIPN['status'] == '200') {
                        $this->ipn_id = $registerIPN['ipn_id'];
                        return $resBody;
                    }
                }
            } else {
                return redirect()->back()->with('error', $resBody['error']['message']);
            }
        } catch (\Exception $e) {
            Log::error('Pesapal Authentication Error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('Authentication failed'));
        }
        return ['status' => '500', 'message' => 'Authentication or IPN registration failed'];
    }

    public function registerIPN()
    {
        $client = new Client();
        $url = $this->url . 'URLSetup/RegisterIPN';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];

        $body = [
            'url' => env('APP_URL') . '/pesapal/payment-callback',
            'ipn_notification_type' => 'GET'
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            $resBody = json_decode($response->getBody()->getContents(), true);

            if ($resBody['status'] == '200') {
                return $resBody;
            } else {
                return redirect()->back()->with('error', $resBody['error']['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Pesapal IPN Registration Error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('Authentication failed'));
        }
        return ['status' => '500', 'message' => 'IPN registration failed'];
    }

    public function getTransactionStatus($data)
    {
        $this->token            = session('pesapal_token');
        if (empty($this->token)) {
            return redirect()->back()->with('error', __('Authentication token is missing.'));
        }

        $client = new Client();
        $url = $this->url . 'Transactions/GetTransactionStatus';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ];

        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'query' => [
                    'orderTrackingId' => $data['OrderTrackingId']
                ]
            ]);

            $resBody = json_decode($response->getBody()->getContents(), true);

            if ($resBody['status'] == '200') {
                return $resBody;
            } else {
                return redirect()->back()->with('error', $resBody['error']['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Pesapal Transaction Status Error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function refundRequest($data, $user, $refundAmount)
    {
        if (empty($this->token)) {
            return redirect()->back()->with('error', __('Authentication token is missing.'));
        }
        $client = new Client();
        $url = $this->url . 'Transactions/RefundRequest';

        $headers = [
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ];

        $body = [
            "confirmation_code" => $data->pesapal_confirmation_code,
            "amount"            => $refundAmount,
            "username"          => $user->name ?? '',
            "remarks"           => "Service not offered."
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);
            $resBody = json_decode($response->getBody()->getContents(), true);

            if ($resBody['status'] == '200') {
                return $resBody;
            } else {
                return redirect()->back()->with('error', $resBody['error']['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Pesapal Refund Request Error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('Refund request failed'));
        }
    }
}
