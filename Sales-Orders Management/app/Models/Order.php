<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'status',
        'payment_method',
        'total_amount',
        'items',
    ];

    protected $casts = [
        'items' => 'array', // ให้ Laravel แปลง JSON เป็น array อัตโนมัติ
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(Str::random(8)); // สร้างเลขออเดอร์อัตโนมัติ
        });
    }

    /**
     * ความสัมพันธ์กับ OrderItem (ดึงรายการสินค้าใน Order นี้)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
