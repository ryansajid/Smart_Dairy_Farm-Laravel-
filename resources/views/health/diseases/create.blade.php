@extends('layouts.app')

@section('title', 'Add New Disease')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">Add New Disease</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('health.diseases.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Disease Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('health.diseases.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Disease Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="disease_type">Disease Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('disease_type') is-invalid @enderror" 
                                    id="disease_type" name="disease_type" required>
                                <option value="">Select Type</option>
                                <option value="bacterial" {{ old('disease_type') == 'bacterial' ? 'selected' : '' }}>Bacterial</option>
                                <option value="viral" {{ old('disease_type') == 'viral' ? 'selected' : '' }}>Viral</option>
                                <option value="parasitic" {{ old('disease_type') == 'parasitic' ? 'selected' : '' }}>Parasitic</option>
                                <option value="fungal" {{ old('disease_type') == 'fungal' ? 'selected' : '' }}>Fungal</option>
                                <option value="metabolic" {{ old('disease_type') == 'metabolic' ? 'selected' : '' }}>Metabolic</option>
                                <option value="nutritional" {{ old('disease_type') == 'nutritional' ? 'selected' : '' }}>Nutritional</option>
                                <option value="other" {{ old('disease_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('disease_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="incubation_period_days">Incubation Period (days)</label>
                            <input type="number" class="form-control @error('incubation_period_days') is-invalid @enderror" 
                                   id="incubation_period_days" name="incubation_period_days" 
                                   value="{{ old('incubation_period_days') }}" min="0">
                            @error('incubation_period_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="severity">Severity <span class="text-danger">*</span></label>
                            <select class="form-control @error('severity') is-invalid @enderror" 
                                    id="severity" name="severity" required>
                                <option value="">Select Severity</option>
                                <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('severity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="zoonotic" name="zoonotic" value="1" {{ old('zoonotic') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="zoonotic">Zoonotic</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="notifiable" name="notifiable" value="1" {{ old('notifiable') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifiable">Notifiable</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transmission_methods">Transmission Methods</label>
                            <textarea class="form-control @error('transmission_methods') is-invalid @enderror" 
                                      id="transmission_methods" name="transmission_methods" rows="3">{{ old('transmission_methods') }}</textarea>
                            @error('transmission_methods')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clinical_signs">Clinical Signs</label>
                            <textarea class="form-control @error('clinical_signs') is-invalid @enderror" 
                                      id="clinical_signs" name="clinical_signs" rows="3">{{ old('clinical_signs') }}</textarea>
                            @error('clinical_signs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="treatment_protocol">Treatment Protocol</label>
                            <textarea class="form-control @error('treatment_protocol') is-invalid @enderror" 
                                      id="treatment_protocol" name="treatment_protocol" rows="3">{{ old('treatment_protocol') }}</textarea>
                            @error('treatment_protocol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prevention">Prevention Measures</label>
                            <textarea class="form-control @error('prevention') is-invalid @enderror" 
                                      id="prevention" name="prevention" rows="3">{{ old('prevention') }}</textarea>
                            @error('prevention')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Disease
                        </button>
                        <a href="{{ route('health.diseases.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
