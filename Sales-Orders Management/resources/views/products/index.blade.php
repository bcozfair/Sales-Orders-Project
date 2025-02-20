<x-app-layout>
    <div class="container">
        <h1 class="text-center mb-4 fw-bold fs-4">Product Management</h1>
        <a href="{{ route('products.create') }}" class="btn btn-success mb-3 d-inline-flex align-items-center rounded-pill shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
            </svg>
            Add New Product
        </a>

        @if ($products->isEmpty())
            <div class="alert alert-warning text-center">No products found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped shadow-sm table-hover">
                    <thead class="bg-dark text-light">
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <!-- ใช้ data-bs-html="true" เพื่อแสดงภาพใน tooltip -->
                                    <img src="{{ asset('storage/' . $product->image) }}" width="80" height="80" class="rounded product-thumbnail"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="<img src='{{ asset('storage/' . $product->image) }}' class='img-fluid' />"
                                        data-bs-html="true">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm rounded-pill shadow-sm">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill shadow-sm"
                                            onclick="return confirm('Are you sure you want to delete this product?');">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    
</x-app-layout>
