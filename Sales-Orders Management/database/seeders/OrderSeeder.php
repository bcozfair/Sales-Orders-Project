<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            // ดึงข้อมูลสินค้าจากตาราง products
            $product = Product::inRandomOrder()->first(); // สุ่มสินค้าจากตาราง products

            // สุ่มจำนวนสินค้า
            $quantity = $faker->numberBetween(1, 5);

            // คำนวณราคา total_amount โดยการคูณราคาสินค้ากับจำนวนสินค้า
            $totalAmount = (float) $product->price * (float) $quantity; // แปลงเป็น float

            // ใช้ number_format() เพื่อจัดรูปแบบ
            $formattedTotalAmount = number_format($totalAmount, 2); // คำนวณราคารวม

            // สร้างข้อมูล items ในรูปแบบที่ต้องการ
            $items = [
                [
                    'product_id' => $product->id, // ไอดีของสินค้า
                    'name' => $product->name, // ชื่อสินค้า
                    'price' => $product->price, // ราคาสินค้า
                    'quantity' => (string) $quantity, // จำนวนสินค้าที่เป็น string
                    'total' => $formattedTotalAmount, // ราคารวมที่ผ่านการ format
                    'image' => $product->image, // ที่อยู่ของภาพสินค้า
                ]
            ];

            // แปลงข้อมูล items เป็น JSON
            $jsonItems = json_encode($items);

            // escape ตัวอักษรพิเศษใน JSON
            $escapedItems = addslashes($jsonItems);

            // ทำการครอบข้อมูลด้วยเครื่องหมาย " "
            $escapedItems = "\"" . $escapedItems . "\"";

            DB::table('orders')->insert([
                'order_number' => $faker->unique()->numerify('ORD-#####'),
                'customer_name' => $faker->name,
                'customer_phone' => $faker->phoneNumber,
                'customer_address' => $faker->address,
                'status' => $faker->randomElement(['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled']),
                'payment_method' => $faker->randomElement(['Cash', 'Credit Card', 'Bank Transfer']),
                'total_amount' => $formattedTotalAmount, // ใส่ราคาที่ format แล้วลงใน total_amount
                'items' => $escapedItems, // ใส่ข้อมูลที่ escape แล้วลงในคอลัมน์ items
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now()->subDays(rand(1, 365)),
            ]);
        }
    }
}
