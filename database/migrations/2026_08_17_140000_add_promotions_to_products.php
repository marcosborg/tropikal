<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('compare_at_price', 10, 2)->nullable()->after('price');
            $table->boolean('is_promotion')->default(false)->after('is_featured');
            $table->timestamp('promotion_starts_at')->nullable()->after('is_promotion');
            $table->timestamp('promotion_ends_at')->nullable()->after('promotion_starts_at');
        });
        Schema::table('order_request_items', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 2)->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('order_request_items', fn (Blueprint $table) => $table->dropColumn('unit_price'));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn([
            'compare_at_price', 'is_promotion', 'promotion_starts_at', 'promotion_ends_at',
        ]));
    }
};
