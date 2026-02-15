@extends('layouts.admin')

@section('title', 'Categories Management')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">Categories</h3>
        <p class="sub-label mb-0">Manage product categories</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-gradient">
        <i class="fas fa-plus me-2"></i>Add Category
    </a>
</div>

<!-- Search and Filter -->
<div class="glass-card p-4 mb-4">
    <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3">
        <div class="col-md-8">
            <input type="text" name="search" placeholder="Search categories..." class="input-dark" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-gradient w-100">
                <i class="fas fa-search me-2"></i>Search
            </button>
        </div>
    </form>
</div>

<!-- Categories Table -->
<div class="glass-card log-container p-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h6 class="fw-800 sub-label mb-4">All Categories</h6>
    
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Products Count</th>
                    <th>Status</th>
                    <th>Sort Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image text-primary"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>
                                <span class="small fw-800 text-black">{{ $category->name }}</span>
                                <div class="sub-label fs-9">{{ $category->slug }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge">{{ $category->products->count() }}</span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="status-badge text-success">Active</span>
                            @else
                                <span class="status-badge text-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-circle btn-accept me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-folder-open fs-1 mb-2"></i>
                                <p>No categories found. Create your first category!</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <span class="sub-label fs-9">Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} categories</span>
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection