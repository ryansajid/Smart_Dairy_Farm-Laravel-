@extends('layouts.app')

@section('title', 'Disease Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">Disease Management</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('health.diseases.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Disease
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Diseases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_diseases'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-virus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Zoonotic Diseases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['zoonotic_count'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-biohazard fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Notifiable Diseases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['notifiable_count'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Disease Cases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeCases ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Diseases</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('health.diseases.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="type" class="mr-2">Disease Type:</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="bacterial" {{ request('type') == 'bacterial' ? 'selected' : '' }}>Bacterial</option>
                        <option value="viral" {{ request('type') == 'viral' ? 'selected' : '' }}>Viral</option>
                        <option value="parasitic" {{ request('type') == 'parasitic' ? 'selected' : '' }}>Parasitic</option>
                        <option value="fungal" {{ request('type') == 'fungal' ? 'selected' : '' }}>Fungal</option>
                        <option value="metabolic" {{ request('type') == 'metabolic' ? 'selected' : '' }}>Metabolic</option>
                        <option value="nutritional" {{ request('type') == 'nutritional' ? 'selected' : '' }}>Nutritional</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="form-group mr-3">
                    <label for="severity" class="mr-2">Severity:</label>
                    <select name="severity" id="severity" class="form-control">
                        <option value="">All Severities</option>
                        <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div class="form-group mr-3">
                    <label class="mr-2">
                        <input type="checkbox" name="zoonotic" value="true" {{ request('zoonotic') ? 'checked' : '' }}> Zoonotic Only
                    </label>
                </div>
                <div class="form-group mr-3">
                    <label class="mr-2">
                        <input type="checkbox" name="notifiable" value="true" {{ request('notifiable') ? 'checked' : '' }}> Notifiable Only
                    </label>
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('health.diseases.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Diseases Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Diseases List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="diseasesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Incubation (days)</th>
                            <th>Severity</th>
                            <th>Zoonotic</th>
                            <th>Notifiable</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diseases as $disease)
                        <tr>
                            <td>{{ $disease->name }}</td>
                            <td>
                                <span class="badge badge-{{ $disease->disease_type == 'viral' ? 'info' : ($disease->disease_type == 'bacterial' ? 'success' : ($disease->disease_type == 'parasitic' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($disease->disease_type) }}
                                </span>
                            </td>
                            <td>{{ $disease->incubation_period_days ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $disease->severity == 'critical' ? 'danger' : ($disease->severity == 'high' ? 'warning' : ($disease->severity == 'medium' ? 'info' : 'success')) }}">
                                    {{ ucfirst($disease->severity) }}
                                </span>
                            </td>
                            <td>
                                @if($disease->zoonotic)
                                    <span class="badge badge-danger">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                @if($disease->notifiable)
                                    <span class="badge badge-warning">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('health.diseases.show', $disease->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('health.diseases.edit', $disease->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('health.diseases.destroy', $disease->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this disease?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No diseases found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($diseases->hasPages())
            <div class="mt-4">
                {{ $diseases->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#diseasesTable').DataTable({
        paging: false,
        searching: true,
        ordering: true,
        info: false
    });
});
</script>
@endsection
