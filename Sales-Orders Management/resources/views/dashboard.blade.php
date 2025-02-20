<x-app-layout>

    <div class="container-fluid">
        <div class="row mt-3">
            <!-- Main content -->
            <div class="col-md-10">
                <div class="row">
                    <!-- Orders Summary -->
                    <div class="col-md-4">
                        <div class="card border-primary shadow-lg">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-clipboard-list"> รายการคำสั่งซื้อ</i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $ordersCount }} คำสั่งซื้อ</h5>
                                <p class="card-text">คำสั่งซื้อที่รอดำเนินการทั้งหมด</p>
                                <a href="{{ route('orders.index') }}" class="btn btn-primary mt-2">ดูทั้งหมด</a>
                            </div>
                        </div>
                    </div>

                    <!-- Products Summary -->
                    <div class="col-md-4">
                        <div class="card border-danger shadow-lg">
                            <div class="card-header bg-danger text-white">
                                <i class="fa-solid fa-basket-shopping"> สินค้าทั้งหมด</i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $productsCount }} รายการ</h5>
                                <p class="card-text">รายการสินค้าทั้งหมดที่มีในระบบ</p>
                                <a href="{{ route('products.index') }}" class="btn btn-danger mt-2">ดูสินค้า</a>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Summary -->
                    <div class="col-md-4">
                        <div class="card border-success shadow-lg">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-wallet"> ยอดขายรวมทั้งหมด</i>
                            </div>
                            <div class="card-body bg-light">
                                <h5 class="card-title">
                                    {{ number_format($totalSales, 2) }} บาท
                                    <small class="{{ $salesChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="fas fa-arrow-up"></i> ({{ number_format($salesChange, 2) }}%)
                                    </small>
                                </h5>
                                <p class="card-text">เปรียบเทียบกับเดือนที่แล้ว</p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Stats Chart -->
                <div class="row mt-4">
                    <!-- Bar Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <i class="fa-solid fa-square-poll-vertical"> สถิติจำนวนสถานะคำสั่งซื้อ</i>
                            </div>
                            <div class="card-body">
                                <canvas id="ordersChart" width="400" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <i class="fa-solid fa-chart-pie"> ยอดขายตามประเภทการชำระเงิน</i>
                            </div>
                            <div class="card-body">
                                <canvas id="paymentMethodsChart" width="400" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="card mt-4 shadow-lg">
                    <div class="card-header bg-light">
                        <i class="fas fa-list-alt"></i> รายการคำสั่งซื้อ
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('orders.index') }}" class="mb-3" id="filterForm">
                            <div class="row d-flex justify-content-end">

                                <div class="col-md-3">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">เลือก สถานะ</option>
                                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                            รอดำเนินการ</option>
                                        <option value="Processing"
                                            {{ request('status') == 'Processing' ? 'selected' : '' }}>
                                            กำลังดำเนินการ
                                        </option>
                                        <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>
                                            จัดส่งแล้ว</option>
                                        <option value="Delivered"
                                            {{ request('status') == 'Delivered' ? 'selected' : '' }}>จัดส่งสำเร็จ
                                        </option>
                                        <option value="Cancelled"
                                            {{ request('status') == 'Cancelled' ? 'selected' : '' }}>ยกเลิก
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="amount_sort" class="form-control" onchange="this.form.submit()">
                                        <option value="">เรียงตามจำนวน</option>
                                        <option value="low_high"
                                            {{ request('amount_sort') == 'low_high' ? 'selected' : '' }}>น้อย ไป
                                            มาก</option>
                                        <option value="high_low"
                                            {{ request('amount_sort') == 'high_low' ? 'selected' : '' }}>มาก ไป
                                            น้อย</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" name="search"
                                            class="form-control rounded-pill border-1 shadow-sm " placeholder="ค้นหา..."
                                            value="{{ request('search') }}" id="searchInput">
                                    </div>
                                </div>
                            </div>
                        </form>

                        <table class="table table-striped" id="orderTable">
                            <thead>
                                <tr>
                                    <th>หมายเลขคำสั่งซื้อ</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>สถานะ</th>
                                    <th>ราคารวม</th>
                                    <th>การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr class="{{ $loop->even ? 'bg-light' : 'bg-white' }}">
                                        <td><i class="fas fa-box"></i> {{ $order->order_number }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>
                                            @if ($order->status == 'Pending')
                                                <span class="badge bg-info"><i class="fas fa-clock"></i>
                                                    รอดำเนินการ</span>
                                            @elseif($order->status == 'Shipped')
                                                <span class="badge bg-primary"><i class="fas fa-truck"></i>
                                                    จัดส่งแล้ว</span>
                                            @elseif($order->status == 'Processing')
                                                <span class="badge bg-warning"><i class="fas fa-cogs"></i>
                                                    กำลังดำเนินการ</span>
                                            @elseif($order->status == 'Delivered')
                                                <span class="badge bg-success"><i class="fas fa-check-circle"></i>
                                                    จัดส่งสำเร็จ</span>
                                            @elseif($order->status == 'Cancelled')
                                                <span class="badge bg-danger"><i class="fas fa-times"></i>
                                                    ยกเลิก</span>
                                            @endif
                                        </td>

                                        <td>{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('invoice.show', $order->id) }}"
                                                class="btn btn-primary btn-sm"><i class="fa-regular fa-eye"></i>
                                                ดูรายละเอียด</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-3" id="pagination">
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm">
                                    <!-- Prev Button -->
                                    <li class="page-item {{ $orders->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $orders->previousPageUrl() }}"
                                            aria-label="Previous">
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
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        var ctxBar = document.getElementById('ordersChart').getContext('2d');
        var ordersChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Shipped', 'Processing', 'Delivered'],
                datasets: [{
                    label: 'คำสั่งซื้อทั้งหมด',
                    data: [{{ $pending }}, {{ $shipped }}, {{ $processing }},
                        {{ $delivered }}
                    ],
                    backgroundColor: ['#6c757d', '#007bff', '#ffc107', '#28a745'],
                    borderColor: ['#6c757d', '#007bff', '#ffc107', '#28a745'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });

        var ctxPie = document.getElementById('paymentMethodsChart').getContext('2d');
        var paymentMethodsChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Cash', 'Credit Card', 'Bank Transfer'],
                datasets: [{
                    label: 'ยอดขายตามประเภทการชำระเงิน',
                    data: [{{ $cashSales }}, {{ $creditCardSales }}, {{ $bankTransferSales }}],
                    backgroundColor: ['#28a745', '#007bff', '#ffc107'],
                    borderColor: ['#28a745', '#007bff', '#ffc107'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>

</x-app-layout>
