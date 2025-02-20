<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        // ฟิลเตอร์สถานะคำสั่งซื้อจากตัวกรอง
        $status = $request->get('status');

        // คำนวณยอดขายตามประเภทการชำระเงิน
        $cashSales = Order::where('payment_method', 'Cash')->sum('total_amount');
        $creditCardSales = Order::where('payment_method', 'Credit Card')->sum('total_amount');
        $bankTransferSales = Order::where('payment_method', 'Bank Transfer')->sum('total_amount');

        // คำนวณจำนวนคำสั่งซื้อในแต่ละสถานะ
        $pending = Order::where('status', 'Pending')->count();
        $shipped = Order::where('status', 'Shipped')->count();
        $processing = Order::where('status', 'Processing')->count();
        $delivered = Order::where('status', 'Delivered')->count();
        $cancelledOrders = Order::where('status', 'Cancelled')->count(); // เพิ่มที่นี่

        // ดึงข้อมูลคำสั่งซื้อทั้งหมดพร้อมกับการกรองสถานะ
        $orders = Order::when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })
            ->paginate(5);  // แสดงผลหน้าละ 5 รายการ

        // จำนวนคำสั่งซื้อทั้งหมด
        $ordersCount = Order::count();
        $productsCount = \App\Models\Product::count(); // สมมุติว่าใช้โมเดล Product

        // ยอดขายรวมจากช่องทางต่าง ๆ
        $totalSales = $cashSales + $creditCardSales + $bankTransferSales;

        // เปรียบเทียบยอดขายกับเดือนที่แล้ว
        $previousMonthSales = Order::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->sum('total_amount');

        $salesChange = $previousMonthSales > 0
            ? (($totalSales - $previousMonthSales) / $previousMonthSales) * 100
            : 0;

        // ดึงข้อมูลยอดขายในเดือนปัจจุบัน
        $salesByDay = Order::whereMonth('created_at', Carbon::now()->month) // เลือกเฉพาะเดือนปัจจุบัน
            ->whereYear('created_at', Carbon::now()->year) // เลือกปีปัจจุบัน
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // แปลงข้อมูลให้เหมาะสมกับการแสดงผล
        $dates = $salesByDay->pluck('date');
        $sales = $salesByDay->pluck('total');

        // ส่งค่าทั้งหมดไปยัง View
        return view('report', compact(
            'totalSales',
            'cashSales',
            'creditCardSales',
            'bankTransferSales',
            'salesChange',
            'ordersCount',
            'productsCount',
            'pending',
            'shipped',
            'processing',
            'delivered',
            'cancelledOrders',
            'salesByDay',
            'dates',
            'sales',
            'orders'
        ));
    }
}
