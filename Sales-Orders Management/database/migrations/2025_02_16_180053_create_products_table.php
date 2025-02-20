<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique(); // รหัสสินค้า
            $table->string('name'); // ชื่อสินค้า
            $table->string('image')->nullable(); // รูปสินค้า (URL)
            $table->decimal('price', 10, 2); // ราคาสินค้า
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
