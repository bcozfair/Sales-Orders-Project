<x-app-layout>
    <div class="container">
        <h1 class="text-center fw-bold fs-4">Invoice</h1>
        <div class="card p-4 shadow-sm">
            <h3>Order #{{ $order->order_number }}</h3>

            <!-- เพิ่มข้อมูลวันที่สั่งซื้อและวิธีการชำระเงิน -->
            <p><strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <!-- แสดงวันที่สั่งซื้อ -->
            <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p> <!-- แสดงวิธีการชำระเงิน -->

            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
            <p><strong>Address:</strong> {{ $order->customer_address }}</p>
            <p><strong>Status:</strong>
                @if ($order->status == 'Pending')
                    <span class="badge bg-secondary text-white">Pending</span>
                @elseif($order->status == 'Shipped')
                    <span class="badge bg-primary text-white">Shipped</span>
                @elseif($order->status == 'Processing')
                    <span class="badge bg-warning text-white">Processing</span>
                @elseif($order->status == 'Delivered')
                    <span class="badge bg-success text-white">Delivered</span>
                @else
                    <span class="badge bg-dark text-white">{{ $order->status }}</span>
                @endif
            </p>

            <table class="table table-bordered mt-3">
                <thead class="table-dark">
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
                            <td>
                                <img src="{{ asset('storage/' . $item['image']) }}" width="60" height="60"
                                    class="rounded">
                            </td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format($item['price'], 2) }}</td>
                            <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="text-end mt-3">Total: ${{ number_format($order->total_amount, 2) }}</h4>
            <div class="mt-4">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back</a>
                <button class="btn btn-primary" onclick="openPDFPreview({{ $order->id }})">Preview</button>


                <!-- Bootstrap Modal -->
                <div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Invoice Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <iframe id="pdfPreviewFrame" style="width: 100%; height: 500px;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('invoice.pdf', $order->id) }}" class="btn btn-danger">Download</a>
            </div>
        </div>
    </div>

    <script>
        function openPDFPreview(orderId) {
            let pdfUrl = `/orders/${orderId}/preview-pdf`; // ใช้ค่า orderId ที่เป็นจริงจากระบบ
            document.getElementById('pdfPreviewFrame').src = pdfUrl;
            var modal = new bootstrap.Modal(document.getElementById('pdfModal'));
            modal.show();
        }
    </script>

</x-app-layout>
