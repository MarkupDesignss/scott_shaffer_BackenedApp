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
                            <li class="breadcrumb-item active" aria-current="page">Create Segment</li>
                        </ol>
                    </nav>
                    <h1 class="h3 fw-bold text-dark mb-1">Create New Segment</h1>
                    <p class="text-muted mb-0">Define filters to create a targeted audience segment</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.segments.store') }}" method="POST">
        @csrf

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
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Young Professionals in Mumbai"
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description (Optional)</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Brief description about this segment...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Audience Filters</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary">4 filters available</span>
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
                                    <option value="18-24" {{ in_array('18-24', old('filters.age_band', [])) ? 'selected' : '' }}>
                                        <span class="me-2">👶</span> 18–24 Years
                                    </option>
                                    <option value="25-34" {{ in_array('25-34', old('filters.age_band', [])) ? 'selected' : '' }}>
                                        <span class="me-2">👨‍💼</span> 25–34 Years
                                    </option>
                                    <option value="35-44" {{ in_array('35-44', old('filters.age_band', [])) ? 'selected' : '' }}>
                                        <span class="me-2">👨‍💻</span> 35–44 Years
                                    </option>
                                    <option value="45+" {{ in_array('45+', old('filters.age_band', [])) ? 'selected' : '' }}>
                                        <span class="me-2">👴</span> 45+ Years
                                    </option>
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
                                    <option value="low" {{ old('filters.budget') == 'low' ? 'selected' : '' }}>
                                        <span class="me-2">💰</span> Low (Under ₹500)
                                    </option>
                                    <option value="medium" {{ old('filters.budget') == 'medium' ? 'selected' : '' }}>
                                        <span class="me-2">💰💰</span> Medium (₹500–₹1500)
                                    </option>
                                    <option value="high" {{ old('filters.budget') == 'high' ? 'selected' : '' }}>
                                        <span class="me-2">💰💰💰</span> High (Above ₹1500)
                                    </option>
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
                                       value="{{ old('filters.city') }}"
                                       placeholder="Enter city names (comma separated)">
                            </div>

                            <!-- Activity -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    <i class="fas fa-chart-line me-2 text-primary"></i>
                                    User Activity
                                </label>
                                <select name="filters[activity]" class="form-select">
                                    <option value="">Any Activity</option>
                                    <option value="active" {{ old('filters.activity') == 'active' ? 'selected' : '' }}>
                                        <span class="me-2">🔥</span> Active (Last 30 days)
                                    </option>
                                    <option value="inactive" {{ old('filters.activity') == 'inactive' ? 'selected' : '' }}>
                                        <span class="me-2">💤</span> Inactive (Over 30 days)
                                    </option>
                                </select>
                            </div>
                        </div>


                </div>
            </div>

            <!-- Sidebar - Preview & Actions -->
        </div>
    </form>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const previewName = document.getElementById('previewName');
    const previewFilters = document.getElementById('previewFilters');

    // Update preview on name change
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'New Segment';
    });

    // Update filter preview
    function updateFilterPreview() {
        const filters = [];

        // Age band
        const ageBands = Array.from(document.querySelectorAll('select[name="filters[age_band][]"] option:checked'))
            .map(opt => opt.textContent.split(' ').pop());
        if (ageBands.length > 0) {
            filters.push(`Age: ${ageBands.join(', ')}`);
        }

        // City
        const city = document.querySelector('input[name="filters[city]"]').value;
        if (city) {
            filters.push(`City: ${city}`);
        }

        // Budget
        const budget = document.querySelector('select[name="filters[budget]"]').value;
        if (budget) {
            filters.push(`Budget: ${budget.charAt(0).toUpperCase() + budget.slice(1)}`);
        }

        // Activity
        const activity = document.querySelector('select[name="filters[activity]"]').value;
        if (activity) {
            filters.push(`Activity: ${activity.charAt(0).toUpperCase() + activity.slice(1)}`);
        }

        previewFilters.textContent = filters.length > 0 ? filters.join(' • ') : 'No filters applied';
    }

    // Listen to all filter changes
    document.querySelectorAll('select, input').forEach(element => {
        element.addEventListener('change', updateFilterPreview);
        element.addEventListener('input', updateFilterPreview);
    });
});
</script>

<style>
.segment-preview-icon {
    transition: all 0.3s ease;
}
.form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1.1rem;
}
select[multiple] {
    height: auto;
    min-height: 120px;
}
select[multiple] option {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    margin-bottom: 2px;
}
select[multiple] option:hover {
    background-color: #f8f9fa;
}
.alert-light {
    background-color: #f8fafc;
    border-color: #e9ecef;
}
.sticky-top {
    z-index: 1;
}
</style>
