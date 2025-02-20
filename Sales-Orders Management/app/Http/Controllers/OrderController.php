<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // ค้นหาคำจากทุกคอลัมน์
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($query) use ($request) {
                $query->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            });
        }

        // กรองตามสถานะ
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // กรองตามจำนวนเงิน (น้อยไปมาก, มากไปน้อย)
        if ($request->has('amount_sort') && $request->amount_sort != '') {
            if ($request->amount_sort == 'low_high') {
                $query->orderBy('total_amount', 'asc');
            } elseif ($request->amount_sort == 'high_low') {
                $query->orderBy('total_amount', 'desc');
            }
        }

        // แบ่งหน้า
        $orders = $query->paginate(8);

        // ส่งข้อมูลไปยัง View
        return view('orders.index', compact('orders'));
    }



    public function create()
    {
        $products = Product::all(); // ดึงสินค้าทั้งหมดจากตาราง products
        return view('orders.create', compact('products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'nullable',
            'customer_address' => 'nullable',
            'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
            'payment_method' => 'required|in:Cash,Credit Card,Bank Transfer',
            'total_amount' => 'required|numeric',
            'product_id' => 'required|array',
            'quantity' => 'required|array'
        ]);

        // สร้างรายการสินค้า (items) ในรูปแบบ JSON
        $items = [];
        foreach ($request->product_id as $index => $productId) {
            $product = Product::find($productId);
            if ($product) {
                // เพิ่มข้อมูล "image" ลงในรายการสินค้า
                $items[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $request->quantity[$index],
                    'total' => $product->price * $request->quantity[$index],
                    'image' => $product->image // เพิ่มข้อมูล image ของสินค้า
                ];
            }
        }

        // สร้างคำสั่งซื้อ
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'total_amount' => $request->total_amount,
            'items' => json_encode($items) // เก็บ JSON ลงในฐานข้อมูล
        ]);

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }


    public function edit($id)
    {
        $order = Order::findOrFail($id); // ใช้ findOrFail เพื่อให้เกิด error ถ้าไม่เจอ order
        $products = Product::all(); // ดึงสินค้าทั้งหมดมาแสดงใน dropdown

        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
            'payment_method' => 'required|in:Cash,Credit Card,Bank Transfer',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity.*' => 'integer|min:1',
        ]);

        $order = Order::findOrFail($id);

        // จัดรูปแบบข้อมูลสินค้าให้เป็น JSON
        $items = [];
        foreach ($request->product_id as $index => $productId) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'product_id' => $productId,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image, // เก็บ path ของรูปสินค้า
                    'quantity' => $request->quantity[$index],
                ];
            }
        }

        // อัปเดตคำสั่งซื้อ
        $order->update([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'items' => json_encode($items), // แปลงข้อมูลสินค้าเป็น JSON ก่อนบันทึก
            'total_amount' => array_reduce($items, function ($total, $item) {
                return $total + ($item['price'] * $item['quantity']);
            }, 0),
        ]);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        return redirect()->route('orders.index');
    }
}
