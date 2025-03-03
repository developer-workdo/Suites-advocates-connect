<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'email',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'plan_name',
        'plan_id',
        'price',
        'price_currency',
        'price_currency_symbol',
        'txn_id',
        'pesapal_order_tracking_id',
        'pesapal_merchant_reference',
        'pesapal_confirmation_code',
        'pesapal_account_number',
        'pesapal_refund_status',
        'payment_type',
        'payment_status',
        'receipt',
        'user_id',
        'refund_reason',
    ];

    public static function total_orders()
    {
        return Order::count();
    }
    public static function total_orders_price()
    {
        return Order::sum('price');
    }

    public function use_coupon()
    {
        return $this->hasOne('App\Models\UserCoupon', 'order', 'order_id');
    }
}
