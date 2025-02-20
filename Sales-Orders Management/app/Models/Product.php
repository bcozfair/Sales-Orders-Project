<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_code', 'name', 'image', 'price'];

    /**
     * สร้างความสัมพันธ์กับ OrderItem
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
