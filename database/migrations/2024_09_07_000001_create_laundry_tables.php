<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // Services (the main services table)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Product Items
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Operations (Service model maps to 'operations' table)
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('product_item_id')->nullable()->constrained('product_items')->onDelete('set null');
            $table->timestamps();
        });

        // Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_item_id')->constrained('product_items')->onDelete('cascade');
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });

        // Product Types
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        // Discounts
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('invoice_number')->nullable();
            $table->string('order_number')->nullable();
            $table->date('order_date')->nullable();
            $table->string('order_time')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivery_time')->nullable();
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->onDelete('set null');
            $table->string('service_id')->nullable();
            $table->string('status')->default('pending');
            $table->integer('total_qty')->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->decimal('express_charge', 10, 2)->default(0);
            $table->timestamps();
        });

        // Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_item_id')->nullable()->constrained('product_items')->onDelete('set null');
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->foreignId('operation_id')->nullable()->constrained('operations')->onDelete('set null');
            $table->decimal('operation_price', 10, 2)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->string('status')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->timestamps();
        });

        // Lundary Order Items
        Schema::create('lundary_order_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->string('ProductName')->nullable();
            $table->integer('ProductQty')->default(0);
            $table->unsignedBigInteger('ProductCategroyId')->nullable();
            $table->timestamps();
        });

        // Payment Details
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('total_quantity')->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('payment_type')->nullable();
            $table->timestamps();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('invoice_number')->nullable();
            $table->timestamps();
        });

        // Tenants
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_deleted')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        // Subscriptions
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->date('starting_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payment_details');
        Schema::dropIfExists('lundary_order_item');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('operations');
        Schema::dropIfExists('product_items');
        Schema::dropIfExists('products');
        Schema::dropIfExists('services');
        Schema::dropIfExists('categories');
    }
};
