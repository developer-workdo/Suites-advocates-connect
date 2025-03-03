<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('price_currency_symbol', 5)->nullable()->after('price_currency');
            $table->string('card_number', 30)->nullable()->change();
            $table->string('pesapal_order_tracking_id')->default(null)->after('txn_id')->nullable();
            $table->string('pesapal_merchant_reference')->default(null)->after('pesapal_order_tracking_id')->nullable();
            $table->string('pesapal_confirmation_code')->default(null)->after('pesapal_merchant_reference')->nullable();
            $table->string('pesapal_account_number')->default(null)->after('pesapal_confirmation_code')->nullable();
            $table->string('pesapal_refund_status')->default('Pending')->after('pesapal_account_number')->nullable();
            $table->string('refund_reason', 255)->default(null)->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('price_currency_symbol');
            $table->string('card_number', 10)->nullable()->change();
            $table->dropColumn('pesapal_order_tracking_id');
            $table->dropColumn('pesapal_merchant_reference');
            $table->dropColumn('pesapal_confirmation_code');
            $table->dropColumn('pesapal_account_number');
            $table->dropColumn('pesapal_refund_status');
            $table->dropColumn('refund_reason');
        });
    }
};
