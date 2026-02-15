@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">Edit Category</h3>
        <p class="sub-label mb-0">Update category information</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left me-2"></i>Back to Categories
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

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Category Name -->
            <div class="col-12 col-md-6">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i> Category Name
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="e.g., Dairy Products"
                    class="input-dark"
                    value="{{ old('name', $category->name) }}"
                    required
                >
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
                    value="{{ old('sort_order', $category->sort_order) }}"
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
                    placeholder="Enter category description..."
                    class="input-dark"
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <!-- Current Image -->
            @if($category->image)
            <div class="col-12">
                <label class="form-label">
                    <i class="fas fa-image"></i> Current Image
                </label>
                <div class="mb-3">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; border: 2px solid rgba(0,0,0,0.1);">
                </div>
            </div>
            @endif

            <!-- Image Upload -->
            <div class="col-12">
                <label for="image" class="form-label">
                    <i class="fas fa-upload"></i> Upload New Image
                </label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="input-dark"
                >
                <small class="sub-label fs-9 mt-1 d-block">Leave empty to keep current image. Recommended size: 300x300px. Max file size: 2MB</small>
            </div>

            <!-- Active Status -->
            <div class="col-12">
                <label class="checkbox-label">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="checkbox-custom">
                    <span class="small fw-800 text-black">Active</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-gradient w-100">
                    <i class="fas fa-save me-2"></i>Update Category
                </button>
            </div>
        </div>
    </form>
</div>
@endsection