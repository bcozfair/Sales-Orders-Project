<x-app-layout>
    <div class="container">
        <h1 class="mb-4 text-center fw-bold fs-4 text-muted">Create Order</h1>
        <form action="{{ route('orders.store') }}" method="POST" class="shadow-sm p-4 bg-light rounded">
            @csrf

            <!-- Customer Name -->
            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customer_name" class="form-control" required placeholder="Enter customer name">
            </div>

            <!-- Customer Phone -->
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="customer_phone" class="form-control" placeholder="Enter phone number">
            </div>

            <!-- Customer Address -->
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="customer_address" class="form-control" rows="3" placeholder="Enter address"></textarea>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Pending">Pending</option>
                    <option value="Processing">Processing</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <!-- Products -->
            <h4 class="mt-4">Products</h4>
            <div id="product-list">
                <div class="row mb-2 product-row align-items-center">
                    <div class="col-2">
                        <img src="" class="product-image img-thumbnail d-none" width="80" height="80"
                            style="object-fit: cover;">
                    </div>
                    <div class="col-3">
                        <select name="product_id[]" class="form-select product-select"
                            onchange="updateProductDetails(this)">
                            <option value="" disabled selected>-- Select Product --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                    data-image="{{ asset('storage/' . $product->image) }}">
                                    {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <input type="number" name="quantity[]" class="form-control quantity" placeholder="Quantity"
                            min="1" value="1" required oninput="updateTotal()">
                    </div>
                    <div class="col-2">
                        <span class="product-price">$0.00</span>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger remove-product" onclick="removeProduct(this)"><i
                                class="fa-regular fa-trash-can"></i></button>
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-success" onclick="addProduct()"><i
                        class="fa-solid fa-circle-plus me-1"></i> Add Product</button>
                <h5>Total: <span id="total-amount">$0.00</span></h5>
                <input type="hidden" name="total_amount" id="total-input">
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary px-4 py-2">Back</a>
                <button type="submit" class="btn btn-primary px-4 py-2">Create Order</button>
            </div>
        </form>
    </div>

    <script>
        function addProduct() {
            let row = document.querySelector('.product-row').cloneNode(true);
            row.querySelector('.product-image').src = "";
            row.querySelector('.product-image').classList.add('d-none');
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
            let price = parseFloat(selectedOption.dataset.price) || 0;
            let image = selectedOption.dataset.image || "";

            let row = select.closest('.row');
            let imageElement = row.querySelector('.product-image');

            if (image) {
                imageElement.src = image;
                imageElement.classList.remove('d-none');
            } else {
                imageElement.src = "";
                imageElement.classList.add('d-none');
            }

            let quantity = row.querySelector('.quantity').value || 1;
            row.querySelector('.product-price').innerText = "$" + (price * quantity).toFixed(2);
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                let price = parseFloat(row.querySelector('.product-select').options[row.querySelector(
                    '.product-select').selectedIndex].dataset.price) || 0;
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
