@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">Products</h3>
        <p class="sub-label mb-0">Manage your products</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-gradient">
        <i class="fas fa-plus me-2"></i>Add Product
    </a>
</div>

<!-- Search and Filter -->
<div class="glass-card p-4 mb-4">
    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
        <div class="col-md-5">
            <input type="text" name="search" placeholder="Search products..." class="input-dark" value="{{ request('search') }}">
        </div>
        <div class="col-md-5">
            <select name="category" class="input-dark">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-gradient w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="glass-card log-container p-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h6 class="fw-800 sub-label mb-4">All Products</h6>
    
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-box text-primary"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>
                                <span class="small fw-800 text-black">{{ $product->name }}</span>
                                <div class="sub-label fs-9">{{ $product->sku }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            @if($product->is_on_sale)
                                <div>
                                    <span class="small fw-800 text-danger">{{ number_format($product->sale_price, 2) }}</span>
                                    <div class="sub-label fs-9 text-decoration-line-through">{{ number_format($product->price, 2) }}</div>
                                </div>
                            @else
                                <span class="small fw-800 text-black">{{ number_format($product->price, 2) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $product->stock_quantity > 10 ? 'text-success' : ($product->stock_quantity > 0 ? 'text-warning' : 'text-danger') }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td>
                            @if($product->is_featured)
                                <span class="status-badge text-primary">Yes</span>
                            @else
                                <span class="status-badge">No</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="status-badge text-success">Active</span>
                            @else
                                <span class="status-badge text-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-circle btn-accept me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-circle btn-reject" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-box-open fs-1 mb-2"></i>
                                <p>No products found. Create your first product!</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <span class="sub-label fs-9">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products</span>
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection