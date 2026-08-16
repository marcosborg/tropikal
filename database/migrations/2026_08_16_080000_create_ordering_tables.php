<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->boolean('is_feature_approved')->default(false)->after('alt_text');
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->decimal('price', 10, 2)->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('customer_type');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->string('tax_number', 32)->nullable();
            $table->string('region');
            $table->string('fulfilment_type');
            $table->text('notes')->nullable();
            $table->string('status')->default('new');
            $table->text('internal_notes')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->unsignedInteger('quantity');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_request_items');
        Schema::dropIfExists('order_requests');
        Schema::dropIfExists('product_variants');
        Schema::table('product_images', fn (Blueprint $table) => $table->dropColumn('is_feature_approved'));
    }
};
