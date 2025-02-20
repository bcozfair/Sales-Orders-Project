<!DOCTYPE html>
<html lang="th"> <!-- ใช้ภาษาไทย -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PDF</title>
    <style>
        @font-face {
            font-family: 'NotoSansThai';
            src: url("{{ storage_path('fonts/NotoSansThai-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'NotoSansThai', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .img-product {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>



</head>

<body>
    <div class="invoice-box">
        <h2>Invoice</h2>
        <p><strong>Order #{{ $order->order_number }}</strong></p>

        <!-- เพิ่มข้อมูลเพิ่มเติม -->
        <p><strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
        <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
        <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
        <p><strong>Address:</strong> {{ $order->customer_address }}</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>

        <!-- แปลงข้อมูล items ที่ถูกเก็บในฐานข้อมูลเป็น JSON -->
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach (json_decode($order->items ?? '[]', true) as $item)
                    <tr>
                        <!-- แสดงภาพสินค้าที่ถูกเก็บใน storage -->
                        <td>
                            @php
                                $imagePath = storage_path('app/public/' . $item['image']);
                                if (file_exists($imagePath)) {
                                    $imageData = base64_encode(file_get_contents($imagePath));
                                    $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                                } else {
                                    $imageSrc = '';
                                }
                            @endphp
                            @if ($imageSrc)
                                <img src="{{ $imageSrc }}" width="60" height="60" alt="Product Image" />
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td> <!-- ✅ แสดงจำนวนสินค้า -->
                        <td>${{ number_format($item['price'], 2) }}</td>
                        <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <h3 class="text-right">Total: ${{ number_format($order->total_amount, 2) }}</h3>
    </div>
</body>

</html>
