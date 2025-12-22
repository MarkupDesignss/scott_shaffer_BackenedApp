@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-semibold text-dark">Policy Details</h4>
            <p class="text-muted mb-0 small">View complete policy information and details</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">{{ $policy->name }}</h5>
                <span class="badge bg-white text-primary fw-medium px-3 py-2 rounded-pill">
                    Version: {{ $policy->version ?? 'N/A' }}
                </span>
            </div>
        </div>

        <div class="card-body p-4">

            <!-- Status & Basic Info Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small text-uppercase fw-semibold mb-2">Policy Status</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="status-indicator {{ $policy->is_active ? 'bg-success' : 'bg-danger' }}"
                                 style="width: 12px; height: 12px; border-radius: 50%;"></div>
                            <span class="badge {{ $policy->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }} px-3 py-2 fw-medium">
                                {{ $policy->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($policy->is_active)
                            <span class="text-success small">
                                <i class="fas fa-check-circle me-1"></i>Currently Active
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-card p-3 border rounded">
                        <label class="text-muted small text-uppercase fw-semibold mb-2">Display Order</label>
                        <div class="d-flex align-items-center gap-2">
                            <span class="display-4 fw-bold text-primary">{{ $policy->order }}</span>
                            <span class="text-muted small">Position in listing</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="mb-4">
                <label class="text-muted small text-uppercase fw-semibold mb-2">Description</label>
                <div class="policy-description p-4 border rounded bg-light-subtle">
                    <div class="prose">
                        {!! $policy->description !!}
                    </div>
                </div>
            </div>

            <!-- Metadata Section -->
            <div class="border-top pt-4 mt-4">
                <h6 class="text-muted text-uppercase small fw-semibold mb-3">Policy Metadata</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="metadata-item p-3 border rounded h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-calendar-plus text-primary"></i>
                                <span class="fw-medium">Created</span>
                            </div>
                            <div class="text-dark">
                                {{ $policy->created_at->format('d M Y') }}
                            </div>
                            <div class="text-muted small">
                                {{ $policy->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="metadata-item p-3 border rounded h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-calendar-edit text-info"></i>
                                <span class="fw-medium">Last Updated</span>
                            </div>
                            <div class="text-dark">
                                {{ $policy->updated_at->format('d M Y') }}
                            </div>
                            <div class="text-muted small">
                                {{ $policy->updated_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>

        </div>

        <!-- Card Footer -->
        <div class="card-footer bg-light-subtle border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    <i class="fas fa-eye me-1"></i> View Mode
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.policies.edit', $policy) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.policies.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list me-1"></i> All Policies
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.policy-description {
    min-height: 150px;
    line-height: 1.6;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.prose p {
    margin-bottom: 1rem;
}

.prose ul, .prose ol {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.info-card {
    transition: all 0.2s ease;
    background: white;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.metadata-item {
    background: #f8f9fa;
    transition: all 0.2s ease;
}

.metadata-item:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.status-indicator {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
}
</style>
@endsection
