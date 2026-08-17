<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->string('phone', 40)->nullable();
            $table->string('tax_number', 32)->nullable();
            $table->string('company')->nullable();
            $table->string('customer_type')->default('private');
            $table->string('stripe_customer_id')->nullable()->unique();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_purchasable')->default(false);
            $table->unsignedInteger('stock_quantity')->nullable();
            $table->boolean('track_stock')->default(false);
            $table->unsignedInteger('tax_rate')->default(23);
            $table->unsignedInteger('weight_grams')->nullable();
        });
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedInteger('stock_quantity')->nullable();
            $table->boolean('track_stock')->default(false);
        });
        Schema::create('addresses', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Principal'); $table->string('name'); $table->string('company')->nullable();
            $table->string('tax_number', 32)->nullable(); $table->string('phone', 40); $table->string('line1'); $table->string('line2')->nullable();
            $table->string('postal_code', 20); $table->string('city'); $table->string('region')->nullable(); $table->char('country_code', 2)->default('PT');
            $table->boolean('is_default_shipping')->default(false); $table->boolean('is_default_billing')->default(false); $table->timestamps();
        });
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id(); $table->string('code')->unique(); $table->string('name'); $table->string('type'); $table->decimal('value', 10, 2);
            $table->decimal('minimum_amount', 10, 2)->nullable(); $table->decimal('maximum_discount', 10, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable(); $table->unsignedInteger('uses')->default(0); $table->unsignedInteger('per_customer_limit')->default(1);
            $table->timestamp('starts_at')->nullable(); $table->timestamp('ends_at')->nullable(); $table->boolean('is_active')->default(true); $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); $table->uuid('public_id')->unique(); $table->string('number')->unique(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('discount_code_id')->nullable()->constrained()->nullOnDelete(); $table->string('status')->default('pending_payment');
            $table->string('payment_status')->default('unpaid'); $table->string('fulfilment_status')->default('unfulfilled'); $table->string('currency', 3)->default('EUR');
            $table->decimal('subtotal', 12, 2); $table->decimal('discount_total', 12, 2)->default(0); $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0); $table->decimal('total', 12, 2); $table->string('email'); $table->string('phone', 40);
            $table->string('customer_name'); $table->string('tax_number', 32)->nullable(); $table->json('shipping_address'); $table->json('billing_address'); $table->text('customer_notes')->nullable();
            $table->string('stripe_checkout_session_id')->nullable()->unique(); $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->timestamp('paid_at')->nullable(); $table->timestamp('cancelled_at')->nullable(); $table->timestamp('fulfilled_at')->nullable(); $table->timestamp('confirmation_sent_at')->nullable(); $table->timestamps();
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('order_id')->constrained()->cascadeOnDelete(); $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete(); $table->string('sku')->nullable(); $table->string('name'); $table->string('variant_name')->nullable();
            $table->unsignedInteger('quantity'); $table->decimal('unit_price', 12, 2); $table->unsignedInteger('tax_rate')->default(23); $table->decimal('line_total', 12, 2); $table->json('snapshot')->nullable(); $table->timestamps();
        });
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id(); $table->string('reference')->unique(); $table->foreignId('order_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('requested'); $table->string('reason'); $table->text('details')->nullable(); $table->decimal('requested_amount', 12, 2)->nullable();
            $table->decimal('refunded_amount', 12, 2)->default(0); $table->string('stripe_refund_id')->nullable(); $table->text('internal_notes')->nullable(); $table->timestamp('resolved_at')->nullable(); $table->timestamps();
        });
        Schema::create('return_request_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('return_request_id')->constrained()->cascadeOnDelete(); $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity'); $table->string('condition')->nullable(); $table->timestamps();
        });
        Schema::create('processed_webhooks', function (Blueprint $table) {
            $table->id(); $table->string('provider'); $table->string('event_id'); $table->string('event_type'); $table->timestamp('processed_at'); $table->timestamps();
            $table->unique(['provider', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processed_webhooks'); Schema::dropIfExists('return_request_items'); Schema::dropIfExists('return_requests'); Schema::dropIfExists('order_items'); Schema::dropIfExists('orders'); Schema::dropIfExists('discount_codes'); Schema::dropIfExists('addresses');
        Schema::table('product_variants', fn (Blueprint $table) => $table->dropColumn(['stock_quantity','track_stock']));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn(['is_purchasable','stock_quantity','track_stock','tax_rate','weight_grams']));
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['is_admin','phone','tax_number','company','customer_type','stripe_customer_id']));
    }
};
