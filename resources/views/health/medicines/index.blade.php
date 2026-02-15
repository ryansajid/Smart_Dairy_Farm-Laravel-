@extends('layouts.admin')

@section('title', 'Medicines Management')

@section('content')
<div class="header-section">
    <div>
        <h3 class="fw-800 mb-1 text-black letter-spacing-1">Medicines</h3>
        <p class="sub-label mb-0">Manage veterinary medicines inventory</p>
    </div>
    <a href="{{ route('health.medicines.create') }}" class="btn btn-gradient">
        <i class="fas fa-plus me-2"></i>Add Medicine
    </a>
</div>

<!-- Search and Filter -->
<div class="glass-card p-4 mb-4">
    <form action="{{ route('health.medicines.index') }}" method="GET" class="row g-3">
        <div class="col-md-8">
            <input type="text" name="search" placeholder="Search medicines..." class="input-dark" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-gradient w-100">
                <i class="fas fa-search me-2"></i>Search
            </button>
        </div>
    </form>
</div>

<!-- Medicines Table -->
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

    <h6 class="fw-800 sub-label mb-4">All Medicines</h6>
    
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Generic Name</th>
                    <th>Form</th>
                    <th>Stock</th>
                    <th>Unit Cost</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $medicine)
                    <tr>
                        <td>
                            <div>
                                <span class="small fw-800 text-black">{{ $medicine->name }}</span>
                                <div class="sub-label fs-9">{{ $medicine->manufacturer }}</div>
                            </div>
                        </td>
                        <td>{{ $medicine->generic_name ?? '-' }}</td>
                        <td>
                            <span class="status-badge">{{ ucfirst($medicine->medicine_form) }}</span>
                        </td>
                        <td>
                            @if($medicine->current_stock <= $medicine->reorder_level)
                                <span class="status-badge text-warning">{{ $medicine->current_stock }}</span>
                            @else
                                <span class="status-badge text-success">{{ $medicine->current_stock }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($medicine->unit_cost, 2) }}</td>
                        <td>
                            @if($medicine->active)
                                <span class="status-badge text-success">Active</span>
                            @else
                                <span class="status-badge text-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('health.medicines.show', $medicine) }}" class="btn-circle btn-accept me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('health.medicines.edit', $medicine) }}" class="btn-circle btn-accept me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('health.medicines.destroy', $medicine) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this medicine?');">
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
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-pills fs-1 mb-2"></i>
                                <p>No medicines found. Add your first medicine!</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($medicines->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <span class="sub-label fs-9">Showing {{ $medicines->firstItem() }} to {{ $medicines->lastItem() }} of {{ $medicines->total() }} medicines</span>
            {{ $medicines->links() }}
        </div>
    @endif
</div>
@endsection
