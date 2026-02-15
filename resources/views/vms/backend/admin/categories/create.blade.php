@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">Create Category</h3>
        <p class="sub-label mb-0">Add a new product category</p>
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

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                ></textarea>
            </div>

            <!-- Image Upload -->
            <div class="col-12">
                <label for="image" class="form-label">
                    <i class="fas fa-image"></i> Category Image
                </label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="input-dark"
                >
                <small class="sub-label fs-9 mt-1 d-block">Recommended size: 300x300px. Max file size: 2MB</small>
            </div>

            <!-- Active Status -->
            <div class="col-12">
                <label class="checkbox-label">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="checkbox-custom">
                    <span class="small fw-800 text-black">Active</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-gradient w-100">
                    <i class="fas fa-save me-2"></i>Save Category
                </button>
            </div>
        </div>
    </form>
</div>
@endsection