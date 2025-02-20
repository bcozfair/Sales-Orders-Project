<x-app-layout>

    <div class="container-fluid">
        <div class="row mt-3">
            <!-- Main content -->
            <div class="col-md-10">
                <div class="row">
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


                    <!-- Sales Channels -->
                    <div class="col-md-4">
                        <div class="card shadow-lg border-info">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-wallet"> ยอดขายแยกตามช่องทาง</i>
                            </div>
                            <div class="card-body">
                                <p>💰 เงินสด: {{ number_format($cashSales, 2) }} บาท</p>
                                <p>💳 บัตรเครดิต: {{ number_format($creditCardSales, 2) }} บาท</p>
                                <p>🏦 โอนเงิน: {{ number_format($bankTransferSales, 2) }} บาท</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cancelled Orders -->
                    <div class="col-md-4">
                        <div class="card shadow-lg border-danger">
                            <div class="card-header bg-danger text-white">
                                <i class="fas fa-times-circle"> คำสั่งซื้อที่ถูกยกเลิก</i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center text-danger">{{ $cancelledOrders }} รายการ</h5>
                                <p class="card-text text-center text-muted">คำสั่งซื้อที่ถูกยกเลิกทั้งหมด</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Order Status Summary -->
                    <div class="col-md-4">
                        <div class="card shadow-lg border-warning">
                            <div class="card-header bg-warning text-white">
                                <i class="fas fa-box"> จำนวนคำสั่งซื้อแยกตามสถานะ</i>
                            </div>
                            <div class="card-body">

                                <ul class="list-unstyled">
                                    <li>🔹 Pending: {{ $pending }} คำสั่งซื้อ</li>
                                    <li>🔸 Processing: {{ $processing }} คำสั่งซื้อ</li>
                                    <li>🚚 Shipped: {{ $shipped }} คำสั่งซื้อ</li>
                                    <li>✅ Delivered: {{ $delivered }} คำสั่งซื้อ</li>
                                    <li>❌ Cancelled: {{ $cancelledOrders }} คำสั่งซื้อ</li>
                                </ul>
                                <a href="{{ route('orders.index') }}"
                                    class="btn btn-warning btn-block text-dark mt-2">ดูรายละเอียด</a>
                            </div>
                        </div>
                    </div>

                    <!-- Products Summary -->
                    <div class="col-md-4">
                        <div class="card shadow-lg border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <i class="fa-solid fa-basket-shopping"> สินค้าทั้งหมด</i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{ $productsCount }} รายการ</h5>
                                <p class="card-text text-center text-muted">รายการสินค้าทั้งหมดที่มีในระบบ</p>
                                <a href="{{ route('products.index') }}"
                                    class="btn btn-secondary btn-block mt-3">ดูสินค้า</a>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Summary -->
                    <div class="col-md-4">
                        <div class="card border-primary shadow-lg">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-clipboard-list"> รายการคำสั่งซื้อ</i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{ $ordersCount }}
                                    คำสั่งซื้อ</h5>
                                <p class="card-text text-center">คำสั่งซื้อที่รอดำเนินการทั้งหมด</p>
                                <a href="{{ route('orders.index') }}" class="btn btn-primary mt-2">
                                    ดูทั้งหมด
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales by Day Chart -->
                <div class="col-md-12 mt-4">
                    <div class="card shadow-lg">
                        <div class="card-header bg-dark text-white">
                            <i class="fas fa-chart-line"> ยอดขายในเดือนปัจจุบัน</i>
                        </div>
                        <div class="card-body">
                            <canvas id="salesByDayChart" width="400" height="200"></canvas>
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
                        <form method="GET" action="{{ route('orders.index') }}" class="mb-3">
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-3">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">เลือก สถานะ</option>
                                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                            รอดำเนินการ</option>
                                        <option value="Processing"
                                            {{ request('status') == 'Processing' ? 'selected' : '' }}>กำลังดำเนินการ
                                        </option>
                                        <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>
                                            จัดส่งแล้ว</option>
                                        <option value="Delivered"
                                            {{ request('status') == 'Delivered' ? 'selected' : '' }}>จัดส่งสำเร็จ
                                        </option>
                                        <option value="Cancelled"
                                            {{ request('status') == 'Cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <select name="amount_sort" class="form-control" onchange="this.form.submit()">
                                        <option value="">เรียงตามจำนวน</option>
                                        <option value="low_high"
                                            {{ request('amount_sort') == 'low_high' ? 'selected' : '' }}>น้อย ไปมาก
                                        </option>
                                        <option value="high_low"
                                            {{ request('amount_sort') == 'high_low' ? 'selected' : '' }}>มาก ไปน้อย
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control rounded-pill"
                                        placeholder="ค้นหา..." value="{{ request('search') }}">
                                </div>
                            </div>
                        </form>

                        <table class="table table-striped">
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
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>
                                            @if ($order->status == 'Pending')
                                                <span class="badge bg-info">รอดำเนินการ</span>
                                            @elseif($order->status == 'Shipped')
                                                <span class="badge bg-primary">จัดส่งแล้ว</span>
                                            @elseif($order->status == 'Processing')
                                                <span class="badge bg-warning">กำลังดำเนินการ</span>
                                            @elseif($order->status == 'Delivered')
                                                <span class="badge bg-success">จัดส่งสำเร็จ</span>
                                            @elseif($order->status == 'Cancelled')
                                                <span class="badge bg-danger">ยกเลิก</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('invoice.show', $order->id) }}"
                                                class="btn btn-primary btn-sm">ดูรายละเอียด</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-3">
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        

        <script>
            var ctxLine = document.getElementById('salesByDayChart').getContext('2d');
            var salesByDayChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: @json($dates), // ส่งวันที่ที่ได้จาก Controller
                    datasets: [{
                        label: 'ยอดขายต่อวัน',
                        data: @json($sales), // ส่งยอดขายตามวันที่
                        borderColor: '#007bff', // สีเส้นกราฟ
                        backgroundColor: 'rgba(0, 123, 255, 0.2)', // สีพื้นหลังกราฟ
                        borderWidth: 2, // ความหนาของเส้นกราฟ
                        fill: true // กราฟเต็มหลังจากเส้น
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'วันที่'
                            },
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 10, // ปรับจำนวนวันที่แสดงบนแกน X
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'ยอดขาย (บาท)'
                            }
                        }
                    }
                }
            });
        </script>


</x-app-layout>
