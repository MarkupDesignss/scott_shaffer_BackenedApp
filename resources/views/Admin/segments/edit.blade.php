@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.segments.index') }}">Segments</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Segment</li>
                        </ol>
                    </nav>
                    <div class="d-flex align-items-center">
                        <div class="segment-icon-lg bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <div>
                            <h1 class="h3 fw-bold text-dark mb-1">Edit Segment</h1>
                            <p class="text-muted mb-0">Modify filters and update audience targeting</p>
                        </div>
                    </div>
                </div>
                <span class="badge bg-light text-dark border">
                    <i class="fas fa-user me-1"></i>
                    {{ number_format($segment->estimated_users ?? 0) }} users
                </span>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.segments.update', $segment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-12">
                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Segment Name -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Segment Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name', $segment->name) }}"
                                   placeholder="Enter segment name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Describe this segment...">{{ old('description', $segment->description ?? '') }}</textarea>
                        </div>

                        <!-- Segment Stats -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <div class="text-muted small mb-1">Created</div>
                                    <div class="fw-semibold">{{ $segment->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <div class="text-muted small mb-1">Last Updated</div>
                                    <div class="fw-semibold">{{ $segment->updated_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <div class="text-muted small mb-1">Active Filters</div>
                                    <div class="fw-semibold">
                                        {{ count(array_filter($segment->filters ?? [])) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Audience Filters</h5>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetFilters()">
                                <i class="fas fa-redo me-1"></i>Reset All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Age Band -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    <i class="fas fa-birthday-cake me-2 text-primary"></i>
                                    Age Band
                                </label>
                                <select name="filters[age_band][]"
                                        class="form-select"
                                        multiple
                                        size="4">
                                    @foreach(['18-24' => '👶 18–24 Years', '25-34' => '👨‍💼 25–34 Years',
                                             '35-44' => '👨‍💻 35–44 Years', '45+' => '👴 45+ Years'] as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ in_array($value, old('filters.age_band', $segment->filters['age_band'] ?? [])) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Budget -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    <i class="fas fa-money-bill-wave me-2 text-primary"></i>
                                    Dining Budget
                                </label>
                                <select name="filters[budget]" class="form-select">
                                    <option value="">Any Budget</option>
                                    @foreach(['low' => '💰 Low (Under ₹500)', 'medium' => '💰💰 Medium (₹500–₹1500)',
                                             'high' => '💰💰💰 High (Above ₹1500)'] as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('filters.budget', $segment->filters['budget'] ?? '') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- City -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    <i class="fas fa-city me-2 text-primary"></i>
                                    City
                                </label>
                                <input type="text"
                                       name="filters[city]"
                                       class="form-control"
                                       value="{{ old('filters.city', $segment->filters['city'] ?? '') }}"
                                       placeholder="Enter city names">
                                <small class="text-muted">e.g., Mumbai, Delhi, Bangalore</small>
                            </div>

                            <!-- Activity -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    <i class="fas fa-chart-line me-2 text-primary"></i>
                                    User Activity
                                </label>
                                <select name="filters[activity]" class="form-select">
                                    <option value="">Any Activity</option>
                                    @foreach(['active' => '🔥 Active (Last 30 days)',
                                             'inactive' => '💤 Inactive (Over 30 days)'] as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('filters.activity', $segment->filters['activity'] ?? '') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- Delete Form -->
<form id="deleteForm" action="{{ route('admin.segments.destroy', $segment) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function resetFilters() {
    if (confirm('Reset all filters to default values?')) {
        // Reset age band
        document.querySelectorAll('select[name="filters[age_band][]"] option').forEach(option => {
            option.selected = false;
        });

        // Reset other selects
        document.querySelector('select[name="filters[budget]"]').selectedIndex = 0;
        document.querySelector('select[name="filters[activity]"]').selectedIndex = 0;

        // Reset city input
        document.querySelector('input[name="filters[city]"]').value = '';
    }
}

function confirmDelete() {
    if (confirm('Are you sure you want to delete this segment?\n\nThis will remove it from all campaigns and cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush

@push('styles')
<style>
.segment-icon-lg {
    transition: transform 0.3s ease;
}
.segment-icon-lg:hover {
    transform: rotate(10deg);
}
.list-group-item {
    border: none;
    padding: 1rem;
    transition: all 0.2s;
}
.list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(4px);
}
.list-group-item i.fa-chevron-right {
    opacity: 0.6;
    transition: opacity 0.2s;
}
.list-group-item:hover i.fa-chevron-right {
    opacity: 1;
}
.border {
    border-color: #e9ecef !important;
}
.sticky-top {
    z-index: 1;
}
</style>
@endpush
