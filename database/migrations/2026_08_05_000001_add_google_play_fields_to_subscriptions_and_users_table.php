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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('settings');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('google_purchase_token')->nullable()->after('stripe_subscription_id');
            $table->string('google_subscription_id')->nullable()->after('google_purchase_token');
            $table->string('google_order_id')->nullable()->after('google_subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['google_purchase_token', 'google_subscription_id', 'google_order_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};
