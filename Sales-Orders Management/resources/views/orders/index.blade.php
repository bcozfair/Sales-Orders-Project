<x-app-layout>
    <div class="container">
        <h1 class="mb-4 text-center fw-bold fs-4">Orders</h1>

        <!-- ฟอร์มค้นหา -->
        <form method="GET" action="{{ route('orders.index') }}" class="mb-3" id="filterForm">
            <div class="row d-flex justify-content-end">
                <div class="col-md-3">
                    <a href="{{ route('orders.create') }}" class="btn btn-success mb-3 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-file-earmark-plus-fill me-1" viewBox="0 0 16 16">
                            <path
                                d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M8.5 7v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 1 0" />
                        </svg>
                        Create Order
                    </a>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">Select Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing
                        </option>
                        <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered
                        </option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="amount_sort" class="form-control" onchange="this.form.submit()">
                        <option value="">Sort by Amount</option>
                        <option value="low_high" {{ request('amount_sort') == 'low_high' ? 'selected' : '' }}>Low to
                            High</option>
                        <option value="high_low" {{ request('amount_sort') == 'high_low' ? 'selected' : '' }}>High to
                            Low</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control rounded-pill border-1 shadow-sm "
                            placeholder="Search..." value="{{ request('search') }}" id="searchInput">
                    </div>
                </div>
            </div>
        </form>

        @if ($orders->isEmpty())
            <p>No orders found.</p>
        @else
            <table class="table table-bordered bg-light">
                <thead class="table-dark">
                    <tr>
                        <th>Order No.</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>
                                @if ($order->status == 'Pending')
                                    <span class="badge bg-info text-white">Pending</span>
                                @elseif($order->status == 'Shipped')
                                    <span class="badge bg-primary text-white">Shipped</span>
                                @elseif($order->status == 'Processing')
                                    <span class="badge bg-warning text-white">Processing</span>
                                @elseif($order->status == 'Delivered')
                                    <span class="badge bg-success text-white">Delivered</span>
                                @else
                                    <span class="badge bg-dark text-white">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach (json_decode($order->items ?? '[]', true) as $item)
                                        <li>
                                            {{ $item['name'] }} ({{ $item['quantity'] }} x
                                            ${{ number_format($item['price'], 2) }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <a href="{{ route('invoice.show', $order->id) }}"
                                    class="btn btn-secondary btn-sm ">Invoice</a>
                                <a href="{{ route('orders.edit', $order->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3" id="pagination">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm">
                    <!-- Prev Button -->
                    <li class="page-item {{ $orders->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $orders->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $orders->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Next Button -->
                    <li class="page-item {{ $orders->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $orders->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>


    </div>

    <script>
        let timeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            // ล้าง timeout ก่อนหน้า
            clearTimeout(timeout);

            // กำหนด timeout ใหม่หลังจากผู้ใช้หยุดพิมพ์ 500ms
            timeout = setTimeout(function() {
                document.getElementById('filterForm').submit(); // ส่งฟอร์มเมื่อหยุดพิมพ์
            }, 1000); // สามารถปรับเวลาช้า/เร็วได้ที่ตัวเลข 500ms
        });

        // ส่งฟอร์มอัตโนมัติเมื่อมีการเลือกค่าในช่องกรอง
        document.querySelector('#filterForm').addEventListener('change', function() {
            this.submit(); // ส่งฟอร์มอัตโนมัติเมื่อมีการเลือกค่า
        });
    </script>
</x-app-layout>
