<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_requests', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->json('shipping_address')->nullable()->after('region');
            $table->decimal('subtotal', 12, 2)->nullable()->after('shipping_address');
            $table->string('currency', 3)->default('EUR')->after('subtotal');
        });
    }

    public function down(): void
    {
        Schema::table('order_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['shipping_address', 'subtotal', 'currency']);
        });
    }
};
