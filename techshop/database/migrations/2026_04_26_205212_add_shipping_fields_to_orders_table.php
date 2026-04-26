<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make user_id nullable so guests can place orders
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('email')->nullable()->after('user_id');
            $table->string('shipping_first_name')->nullable()->after('email');
            $table->string('shipping_last_name')->nullable()->after('shipping_first_name');
            $table->string('shipping_address_line_1')->nullable()->after('shipping_last_name');
            $table->string('shipping_address_line_2')->nullable()->after('shipping_address_line_1');
            $table->string('shipping_postcode', 20)->nullable()->after('shipping_address_line_2');
            $table->string('shipping_city')->nullable()->after('shipping_postcode');
            $table->string('shipping_country')->nullable()->after('shipping_city');
            $table->string('shipping_phone', 30)->nullable()->after('shipping_country');
            $table->timestamp('checked_out_at')->nullable()->after('shipping_phone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'email', 'shipping_first_name', 'shipping_last_name',
                'shipping_address_line_1', 'shipping_address_line_2',
                'shipping_postcode', 'shipping_city', 'shipping_country',
                'shipping_phone', 'checked_out_at',
            ]);

            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
