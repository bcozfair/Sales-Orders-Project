<x-app-layout>
    <div class="container">
        <h1 class="mb-4 text-center fw-bold fs-4">Edit Order</h1>

        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
            @csrf
            @method('PUT')

            <!-- Customer Name -->
            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customer_name" class="form-control" value="{{ $order->customer_name }}"
                    required>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="customer_phone" class="form-control" value="{{ $order->customer_phone }}">
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="customer_address" class="form-control">{{ $order->customer_address }}</textarea>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing
                    </option>
                    <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="Cash" {{ $order->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Credit Card" {{ $order->payment_method == 'Credit Card' ? 'selected' : '' }}>Credit
                        Card</option>
                    <option value="Bank Transfer" {{ $order->payment_method == 'Bank Transfer' ? 'selected' : '' }}>
                        Bank Transfer</option>
                </select>
            </div>

            <!-- Products -->
            <h4 class="mt-4">Products</h4>
            <div id="product-list">
                @foreach (json_decode($order->items ?? '[]', true) as $index => $item)
                    <div class="row mb-3 product-row align-items-center">
                        <div class="col-2">
                            <img src="{{ asset('storage/' . $item['image']) }}" class="product-image img-thumbnail"
                                width="80" height="80" style="object-fit: cover;">
                        </div>
                        <div class="col-3">
                            <select name="product_id[]" class="form-select product-select"
                                onchange="updateProductDetails(this)">
                                <option value="" disabled>-- Select Product --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                        data-image="{{ asset('storage/' . $product->image) }}"
                                        {{ $item['product_id'] == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <input type="number" name="quantity[]" class="form-control quantity" placeholder="Quantity"
                                min="1" value="{{ $item['quantity'] }}" required oninput="updateTotal()">
                        </div>
                        <div class="col-2">
                            <span
                                class="product-price">${{ number_format($item['quantity'] * $item['price'], 2) }}</span>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger remove-product" onclick="removeProduct(this)">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-success" onclick="addProduct()">+ Add Product</button>
                <h5 class="mt-2">Total: <span id="total-amount">${{ number_format($order->total_amount, 2) }}</span>
                </h5>
                <input type="hidden" name="total_amount" id="total-input" value="{{ $order->total_amount }}">

            </div>

            <!-- Action Buttons -->

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </div>
        </form>
    </div>

    <script>
        function addProduct() {
            let row = document.querySelector('.product-row').cloneNode(true);
            row.querySelector('.product-image').src = "";
            row.querySelector('.product-select').selectedIndex = 0;
            row.querySelector('.quantity').value = 1;
            row.querySelector('.product-price').innerText = "$0.00";
            document.getElementById('product-list').appendChild(row);
        }

        function removeProduct(button) {
            let productList = document.getElementById('product-list');
            if (productList.childElementCount > 1) {
                button.closest('.product-row').remove();
                updateTotal();
            }
        }

        function updateProductDetails(select) {
            let selectedOption = select.options[select.selectedIndex];
            let price = selectedOption.dataset.price || 0;
            let image = selectedOption.dataset.image || "";

            let row = select.closest('.row');
            row.querySelector('.product-image').src = image;
            row.querySelector('.product-price').innerText = "$" + (price * row.querySelector('.quantity').value).toFixed(2);
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                let price = row.querySelector('.product-select').options[row.querySelector('.product-select')
                    .selectedIndex].dataset.price || 0;
                let quantity = row.querySelector('.quantity').value || 1;
                let totalPrice = price * quantity;
                row.querySelector('.product-price').innerText = "$" + totalPrice.toFixed(2);
                total += totalPrice;
            });
            document.getElementById('total-amount').innerText = "$" + total.toFixed(2);
            document.getElementById('total-input').value = total;
        }
    </script>
</x-app-layout>
