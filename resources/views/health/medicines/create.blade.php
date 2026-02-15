@extends('layouts.admin')

@section('title', 'Add New Medicine')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">Add New Medicine</h3>
        <p class="sub-label mb-0">Register a new veterinary medicine</p>
    </div>
    <a href="{{ route('health.medicines.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left me-2"></i>Back to Medicines
    </a>
</div>

<!-- Create Form -->
<div class="glass-card p-4">
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

    <form action="{{ route('health.medicines.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Basic Information -->
            <div class="col-12">
                <h6 class="fw-800 sub-label mb-3">Basic Information</h6>
            </div>

            <div class="col-md-6">
                <label for="name" class="form-label">Medicine Name *</label>
                <input type="text" name="name" id="name" class="input-dark @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="generic_name" class="form-label">Generic Name</label>
                <input type="text" name="generic_name" id="generic_name" class="input-dark @error('generic_name') is-invalid @enderror" value="{{ old('generic_name') }}">
                @error('generic_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="medicine_form" class="form-label">Medicine Form *</label>
                <select name="medicine_form" id="medicine_form" class="input-dark @error('medicine_form') is-invalid @enderror" required>
                    <option value="">Select Form</option>
                    <option value="tablet" {{ old('medicine_form') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="capsule" {{ old('medicine_form') == 'capsule' ? 'selected' : '' }}>Capsule</option>
                    <option value="injection" {{ old('medicine_form') == 'injection' ? 'selected' : '' }}>Injection</option>
                    <option value="liquid" {{ old('medicine_form') == 'liquid' ? 'selected' : '' }}>Liquid</option>
                    <option value="powder" {{ old('medicine_form') == 'powder' ? 'selected' : '' }}>Powder</option>
                    <option value="ointment" {{ old('medicine_form') == 'ointment' ? 'selected' : '' }}>Ointment</option>
                    <option value="spray" {{ old('medicine_form') == 'spray' ? 'selected' : '' }}>Spray</option>
                </select>
                @error('medicine_form')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="strength" class="form-label">Strength</label>
                <input type="text" name="strength" id="strength" class="input-dark @error('strength') is-invalid @enderror" value="{{ old('strength') }}" placeholder="e.g., 500mg">
                @error('strength')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Manufacturer & Supplier -->
            <div class="col-12 mt-4">
                <h6 class="fw-800 sub-label mb-3">Manufacturer & Supplier</h6>
            </div>

            <div class="col-md-6">
                <label for="manufacturer" class="form-label">Manufacturer</label>
                <input type="text" name="manufacturer" id="manufacturer" class="input-dark @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer') }}">
                @error('manufacturer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="supplier" class="form-label">Supplier</label>
                <input type="text" name="supplier" id="supplier" class="input-dark @error('supplier') is-invalid @enderror" value="{{ old('supplier') }}">
                @error('supplier')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Storage & Tracking -->
            <div class="col-12 mt-4">
                <h6 class="fw-800 sub-label mb-3">Storage & Tracking</h6>
            </div>

            <div class="col-md-6">
                <label for="storage_requirements" class="form-label">Storage Requirements</label>
                <textarea name="storage_requirements" id="storage_requirements" class="input-dark @error('storage_requirements') is-invalid @enderror" rows="3">{{ old('storage_requirements') }}</textarea>
                @error('storage_requirements')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="stock_location" class="form-label">Stock Location</label>
                <input type="text" name="stock_location" id="stock_location" class="input-dark @error('stock_location') is-invalid @enderror" value="{{ old('stock_location') }}" placeholder="e.g., Shelf A-3">
                @error('stock_location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <div class="form-check mt-4">
                    <input type="checkbox" name="expiry_tracking" id="expiry_tracking" class="form-check-input" value="1" {{ old('expiry_tracking', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="expiry_tracking">Enable Expiry Tracking</label>
                </div>
            </div>

            <!-- Inventory -->
            <div class="col-12 mt-4">
                <h6 class="fw-800 sub-label mb-3">Inventory</h6>
            </div>

            <div class="col-md-3">
                <label for="current_stock" class="form-label">Current Stock</label>
                <input type="number" name="current_stock" id="current_stock" class="input-dark @error('current_stock') is-invalid @enderror" value="{{ old('current_stock', 0) }}" min="0">
                @error('current_stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="reorder_level" class="form-label">Reorder Level</label>
                <input type="number" name="reorder_level" id="reorder_level" class="input-dark @error('reorder_level') is-invalid @enderror" value="{{ old('reorder_level', 10) }}" min="0">
                @error('reorder_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="reorder_qty" class="form-label">Reorder Quantity</label>
                <input type="number" name="reorder_qty" id="reorder_qty" class="input-dark @error('reorder_qty') is-invalid @enderror" value="{{ old('reorder_qty', 50) }}" min="0">
                @error('reorder_qty')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="unit_cost" class="form-label">Unit Cost</label>
                <input type="number" name="unit_cost" id="unit_cost" class="input-dark @error('unit_cost') is-invalid @enderror" value="{{ old('unit_cost') }}" min="0" step="0.01">
                @error('unit_cost')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status -->
            <div class="col-12 mt-4">
                <h6 class="fw-800 sub-label mb-3">Status</h6>
            </div>

            <div class="col-md-3">
                <div class="form-check mt-4">
                    <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">Active</label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-12 mt-4">
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-2"></i>Save Medicine
                    </button>
                    <a href="{{ route('health.medicines.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
