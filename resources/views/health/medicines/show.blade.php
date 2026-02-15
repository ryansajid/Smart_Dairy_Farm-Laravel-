@extends('layouts.admin')

@section('title', 'Medicine Details')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">{{ $medicine->name }}</h3>
        <p class="sub-label mb-0">Medicine Details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('health.medicines.edit', $medicine) }}" class="btn btn-gradient">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('health.medicines.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<!-- Medicine Details -->
<div class="glass-card p-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Basic Information -->
        <div class="col-12">
            <h6 class="fw-800 sub-label mb-3">Basic Information</h6>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Medicine Name</label>
                <p>{{ $medicine->name }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Generic Name</label>
                <p>{{ $medicine->generic_name ?? '-' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Medicine Form</label>
                <p><span class="status-badge">{{ ucfirst($medicine->medicine_form) }}</span></p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Strength</label>
                <p>{{ $medicine->strength ?? '-' }}</p>
            </div>
        </div>

        <!-- Manufacturer & Supplier -->
        <div class="col-12 mt-4">
            <h6 class="fw-800 sub-label mb-3">Manufacturer & Supplier</h6>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Manufacturer</label>
                <p>{{ $medicine->manufacturer ?? '-' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Supplier</label>
                <p>{{ $medicine->supplier ?? '-' }}</p>
            </div>
        </div>

        <!-- Storage & Tracking -->
        <div class="col-12 mt-4">
            <h6 class="fw-800 sub-label mb-3">Storage & Tracking</h6>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Storage Requirements</label>
                <p>{{ $medicine->storage_requirements ?? '-' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Stock Location</label>
                <p>{{ $medicine->stock_location ?? '-' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Expiry Tracking</label>
                <p>
                    @if($medicine->expiry_tracking)
                        <span class="status-badge text-success">Enabled</span>
                    @else
                        <span class="status-badge text-danger">Disabled</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Inventory -->
        <div class="col-12 mt-4">
            <h6 class="fw-800 sub-label mb-3">Inventory</h6>
        </div>

        <div class="col-md-3">
            <div class="detail-item">
                <label>Current Stock</label>
                <p>
                    @if($medicine->current_stock <= $medicine->reorder_level)
                        <span class="status-badge text-warning">{{ $medicine->current_stock }}</span>
                    @else
                        <span class="status-badge text-success">{{ $medicine->current_stock }}</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="detail-item">
                <label>Reorder Level</label>
                <p>{{ $medicine->reorder_level }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="detail-item">
                <label>Reorder Quantity</label>
                <p>{{ $medicine->reorder_qty }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="detail-item">
                <label>Unit Cost</label>
                <p>{{ number_format($medicine->unit_cost, 2) }}</p>
            </div>
        </div>

        <!-- Status -->
        <div class="col-12 mt-4">
            <h6 class="fw-800 sub-label mb-3">Status</h6>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Active Status</label>
                <p>
                    @if($medicine->active)
                        <span class="status-badge text-success">Active</span>
                    @else
                        <span class="status-badge text-danger">Inactive</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="col-12 mt-4">
            <h6 class="fw-800 sub-label mb-3">Timestamps</h6>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Created At</label>
                <p>{{ $medicine->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <label>Updated At</label>
                <p>{{ $medicine->updated_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

<style>
.detail-item {
    background: rgba(59, 130, 246, 0.05);
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
}

.detail-item label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
    display: block;
}

.detail-item p {
    margin: 0;
    font-weight: 600;
    color: #1f2937;
}
</style>
@endsection
