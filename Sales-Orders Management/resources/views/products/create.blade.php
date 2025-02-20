<x-app-layout>
    <div class="container">
        <h1 class="text-center mb-5 fw-bold fs-4">Add New Product</h1>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="shadow-sm p-4 rounded bg-light">
            @csrf
            <div class="form-group mb-4">
                <label for="name" class="fw-medium">Product Name</label>
                <input type="text" name="name" class="form-control border-0 shadow-sm p-3 rounded" placeholder="Enter product name" required>
            </div>

            <div class="form-group mb-4">
                <label for="price" class="fw-medium">Price</label>
                <input type="number" name="price" class="form-control border-0 shadow-sm p-3 rounded" step="0.01" placeholder="Enter product price" required>
            </div>

            <div class="form-group mb-4">
                <label for="image" class="fw-medium">Product Image</label>
                <input type="file" name="image" class="form-control border-0 shadow-sm p-3 rounded" id="imageInput" accept="image/*" required>
                
                <div class="mt-4">
                    <img id="imagePreview" src="" alt="Image Preview" class="img-fluid d-none rounded shadow-sm" style="max-height: 200px; object-fit: cover;">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-secondary rounded-pill px-4 py-2">Back</a>
                <button type="submit" class="btn btn-success rounded-pill px-4 py-2">Add Product</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('imagePreview');
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none'); // แสดงภาพ
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
