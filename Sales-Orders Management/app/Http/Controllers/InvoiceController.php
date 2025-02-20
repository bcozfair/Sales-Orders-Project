<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // แสดง Invoice บนหน้าเว็บ
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.invoice', compact('order'));
    }

    // ดาวน์โหลด Invoice เป็น PDF
    public function downloadPDF($id)
    {
        $order = Order::findOrFail($id);

        // โหลด View และสร้าง PDF
        $pdf = Pdf::loadView('orders.invoice-pdf', compact('order'))
            ->setPaper('A4', 'portrait')
            ->set_option('isHtml5ParserEnabled', true);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');


        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    // ใน InvoiceController
    public function previewPDF($id)
    {
        $order = Order::findOrFail($id);

        // ใช้ 'orders.invoice-pdf' เพื่อให้ข้อมูลแสดงใน view
        return view('orders.invoice-pdf', compact('order'));
    }
}
