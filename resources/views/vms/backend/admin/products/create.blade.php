@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">Create Product</h3>
        <p class="sub-label mb-0">Add a new product</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left me-2"></i>Back to Products
    </a>
</div>

<div class="glass-card p-4">
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Product Name -->
            <div class="col-12 col-md-6">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i> Product Name
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="e.g., Fresh Milk 1L"
                    class="input-dark"
                    required
                >
            </div>

            <!-- SKU -->
            <div class="col-12 col-md-6">
                <label for="sku" class="form-label">
                    <i class="fas fa-barcode"></i> SKU (Stock Keeping Unit)
                </label>
                <input
                    type="text"
                    id="sku"
                    name="sku"
                    placeholder="e.g., MILK-001"
                    class="input-dark"
                >
            </div>

            <!-- Category -->
            <div class="col-12 col-md-6">
                <label for="category_id" class="form-label">
                    <i class="fas fa-folder"></i> Category
                </label>
                <select id="category_id" name="category_id" class="input-dark" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort Order -->
            <div class="col-12 col-md-6">
                <label for="sort_order" class="form-label">
                    <i class="fas fa-sort-numeric-up"></i> Sort Order
                </label>
                <input
                    type="number"
                    id="sort_order"
                    name="sort_order"
                    placeholder="0"
                    min="0"
                    class="input-dark"
                >
            </div>

            <!-- Price -->
            <div class="col-12 col-md-4">
                <label for="price" class="form-label">
                    <i class="fas fa-dollar-sign"></i> Price
                </label>
                <input
                    type="number"
                    id="price"
                    name="price"
                    placeholder="0.00"
                    step="0.01"
                    min="0"
                    class="input-dark"
                    required
                >
            </div>

            <!-- Sale Price -->
            <div class="col-12 col-md-4">
                <label for="sale_price" class="form-label">
                    <i class="fas fa-tags"></i> Sale Price (Optional)
                </label>
                <input
                    type="number"
                    id="sale_price"
                    name="sale_price"
                    placeholder="0.00"
                    step="0.01"
                    min="0"
                    class="input-dark"
                >
            </div>

            <!-- Stock Quantity -->
            <div class="col-12 col-md-4">
                <label for="stock_quantity" class="form-label">
                    <i class="fas fa-boxes"></i> Stock Quantity
                </label>
                <input
                    type="number"
                    id="stock_quantity"
                    name="stock_quantity"
                    placeholder="0"
                    min="0"
                    class="input-dark"
                >
            </div>

            <!-- Description -->
            <div class="col-12">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i> Description
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Enter product description..."
                    class="input-dark"
                ></textarea>
            </div>

            <!-- Main Image -->
            <div class="col-12">
                <label for="image" class="form-label">
                    <i class="fas fa-image"></i> Main Product Image
                </label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="input-dark"
                >
                <small class="sub-label fs-9 mt-1 d-block">Recommended size: 400x400px. Max file size: 2MB</small>
            </div>

            <!-- Multiple Images -->
            <div class="col-12">
                <label for="images" class="form-label">
                    <i class="fas fa-images"></i> Additional Product Images (Optional)
                </label>
                <input
                    type="file"
                    id="images"
                    name="images[]"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="input-dark"
                    multiple
                >
                <small class="sub-label fs-9 mt-1 d-block">You can select multiple images. Max file size: 2MB each</small>
            </div>

            <!-- Active Status -->
            <div class="col-12 col-md-6">
                <label class="checkbox-label">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="checkbox-custom">
                    <span class="small fw-800 text-black">Active</span>
                </label>
            </div>

            <!-- Featured Status -->
            <div class="col-12 col-md-6">
                <label class="checkbox-label">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" class="checkbox-custom">
                    <span class="small fw-800 text-black">Featured Product</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-gradient w-100">
                    <i class="fas fa-save me-2"></i>Save Product
                </button>
            </div>
        </div>
    </form>
</div>
@endsection