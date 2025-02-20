<x-app-layout>
    <div class="container">
        <h1 class="text-center mb-1 fw-bold fs-4">Edit Product</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="shadow-sm p-4 rounded bg-light">
            @csrf
            @method('PUT')
            
            <div class="form-group mb-3">
                <label for="name" class="fw-medium">Product Name</label>
                <input type="text" name="name" class="form-control border-0 shadow-sm p-3 rounded" value="{{ $product->name }}" required placeholder="Enter product name">
            </div>
            
            <div class="form-group mb-3">
                <label for="price" class="fw-medium">Price</label>
                <input type="number" name="price" class="form-control border-0 shadow-sm p-3 rounded" value="{{ $product->price }}" step="0.01" required placeholder="Enter product price">
            </div>
            
            <div class="form-group mb-3">
                <label for="image" class="fw-medium">Product Image</label>
                <input type="file" name="image" class="form-control border-0 shadow-sm p-3 rounded" accept="image/*">
                <small class="text-muted">Leave blank if not changing</small>
                
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover;">
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-secondary rounded-pill px-4 py-2">Back</a>
                <button type="submit" class="btn btn-warning rounded-pill px-4 py-2">Update Product</button>
            </div>
        </form>
    </div>
</x-app-layout>
