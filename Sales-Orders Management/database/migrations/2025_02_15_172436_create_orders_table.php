<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // หมายเลขคำสั่งซื้อ
            $table->string('customer_name'); // ชื่อลูกค้า
            $table->string('customer_phone')->nullable(); // เบอร์โทรศัพท์
            $table->text('customer_address')->nullable(); // ที่อยู่
            $table->enum('status', ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'])
                ->default('Pending'); // สถานะคำสั่งซื้อ
            $table->enum('payment_method', ['Cash', 'Credit Card', 'Bank Transfer'])->default('Cash'); // วิธีชำระเงิน
            $table->decimal('total_amount', 10, 2); // ราคารวม
            $table->json('items')->nullable();
            // รายการสินค้า (เก็บเป็น JSON)
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
