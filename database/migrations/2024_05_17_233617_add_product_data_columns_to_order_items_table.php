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
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name')->after('subtotal');
            $table->integer('product_price')->after('product_name');
            $table->integer('product_offPrice')->after('product_price');
            $table->string('product_color')->after('product_offPrice');
            $table->string('product_colorCode')->after('product_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('product_name');
            $table->dropColumn('product_price');
            $table->dropColumn('product_offPrice');
            $table->dropColumn('product_color');
            $table->dropColumn('product_colorCode');
        });
    }
};
