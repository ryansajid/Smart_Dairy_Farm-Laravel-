@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">Edit Product</h3>
        <p class="sub-label mb-0">Update product information</p>
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

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                    value="{{ old('name', $product->name) }}"
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
                    value="{{ old('sku', $product->sku) }}"
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
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
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
                    value="{{ old('sort_order', $product->sort_order) }}"
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
                    value="{{ old('price', $product->price) }}"
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
                    value="{{ old('sale_price', $product->sale_price) }}"
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
                    value="{{ old('stock_quantity', $product->stock_quantity) }}"
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
                >{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Current Main Image -->
            @if($product->image)
            <div class="col-12">
                <label class="form-label">
                    <i class="fas fa-image"></i> Current Main Image
                </label>
                <div class="mb-3">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; border: 2px solid rgba(0,0,0,0.1);">
                </div>
            </div>
            @endif

            <!-- Main Image Upload -->
            <div class="col-12">
                <label for="image" class="form-label">
                    <i class="fas fa-upload"></i> Upload New Main Image
                </label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="input-dark"
                >
                <small class="sub-label fs-9 mt-1 d-block">Leave empty to keep current image. Recommended size: 400x400px. Max file size: 2MB</small>
            </div>

            <!-- Current Additional Images -->
            @if($product->images && count($product->images) > 0)
            <div class="col-12">
                <label class="form-label">
                    <i class="fas fa-images"></i> Current Additional Images
                </label>
                <div class="d-flex gap-3 flex-wrap mb-3">
                    @foreach($product->images as $image)
                        <img src="{{ asset($image) }}" alt="Additional image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid rgba(0,0,0,0.1);">
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Multiple Images Upload -->
            <div class="col-12">
                <label for="images" class="form-label">
                    <i class="fas fa-upload"></i> Upload New Additional Images
                </label>
                <input
                    type="file"
                    id="images"
                    name="images[]"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="input-dark"
                    multiple
                >
                <small class="sub-label fs-9 mt-1 d-block">Leave empty to keep current images. New images will replace all existing ones. Max file size: 2MB each</small>
            </div>

            <!-- Active Status -->
            <div class="col-12 col-md-6">
                <label class="checkbox-label">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="checkbox-custom">
                    <span class="small fw-800 text-black">Active</span>
                </label>
            </div>

            <!-- Featured Status -->
            <div class="col-12 col-md-6">
                <label class="checkbox-label">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="checkbox-custom">
                    <span class="small fw-800 text-black">Featured Product</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-gradient w-100">
                    <i class="fas fa-save me-2"></i>Update Product
                </button>
            </div>
        </div>
    </form>
</div>
@endsection