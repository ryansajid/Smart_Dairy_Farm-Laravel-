@extends('layouts.app')

@section('title', 'Disease Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">{{ $disease->name }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('health.diseases.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('health.diseases.edit', $disease->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Disease Type</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ ucfirst($disease->disease_type) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-{{ $disease->severity == 'critical' ? 'danger' : ($disease->severity == 'high' ? 'warning' : ($disease->severity == 'medium' ? 'info' : 'success')) }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Severity</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ ucfirst($disease->severity) }}
                            </div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Incubation Period</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $disease->incubation_period_days ?? 'N/A' }} days
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-{{ $disease->zoonotic ? 'danger' : 'secondary' }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Zoonotic</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $disease->zoonotic ? 'Yes' : 'No' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Disease Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Transmission Methods</label>
                        <p class="text-gray-800">{{ $disease->transmission_methods ?? 'Not specified' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Clinical Signs</label>
                        <p class="text-gray-800">{{ $disease->clinical_signs ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Treatment Protocol</label>
                        <p class="text-gray-800">{{ $disease->treatment_protocol ?? 'Not specified' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Prevention Measures</label>
                        <p class="text-gray-800">{{ $disease->prevention ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Notifiable Disease</label>
                        <p>
                            @if($disease->notifiable)
                                <span class="badge badge-warning">Yes - Requires Official Reporting</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Created At</label>
                        <p class="text-gray-800">{{ $disease->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
        </div>
        <div class="card-body">
            <div class="d-flex gap-2">
                <a href="{{ route('health.diseases.edit', $disease->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Disease
                </a>
                <form action="{{ route('health.diseases.destroy', $disease->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this disease?')">
                        <i class="fas fa-trash"></i> Delete Disease
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
