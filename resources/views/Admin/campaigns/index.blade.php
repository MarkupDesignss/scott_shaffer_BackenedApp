@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Campaign Management</h4>
            <p class="text-muted mb-0">Manage your marketing campaigns</p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Campaign
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    {{-- <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="live">Live</option>
                        <option value="paused">Paused</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <input type="date" class="form-control" name="start_date">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" placeholder="Search campaigns..." name="search">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- Campaigns Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#ID</th>
                            <th>Campaign Details</th>
                            <th>Status</th>
                            <th>Schedule</th>
                            <th>Consent</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                        <tr>
                            <td class="ps-4 fw-semibold">#{{ $campaign->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">{{ $campaign->name }}</h6>
                                        <p class="text-muted mb-0 small">{{ $campaign->title }}</p>
                                        <p class="text-muted mb-0 small">{{ $campaign->subtitle }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'live' => 'success',
                                        'paused' => 'warning'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$campaign->status] ?? 'secondary' }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-play text-success me-2 small"></i>
                                        <span>{{ $campaign->starts_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-stop text-danger me-2 small"></i>
                                        <span>{{ $campaign->ends_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($campaign->requires_consent)
                                <span class="badge bg-info">
                                    <i class="fas fa-user-check me-1"></i>Consent Required
                                </span>
                                @else
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-user me-1"></i>Auto-send
                                </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                       class="btn btn-outline-primary btn-sm"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.campaigns.destroy', $campaign) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this campaign? This action cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @if($campaign->cta_url)
                                    <a href="{{ $campaign->cta_url }}"
                                       target="_blank"
                                       class="btn btn-outline-success btn-sm"
                                       title="Preview">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No campaigns found</h5>
                                    <p class="text-muted">Get started by creating your first campaign</p>
                                    <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create Campaign
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($campaigns->hasPages())
    <div class="mt-4">
        {{ $campaigns->links() }}
    </div>
    @endif
</div>

<style>
    .table td {
        vertical-align: middle;
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
</style>
@endsection
