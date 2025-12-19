@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 mb-2 fw-bold text-primary">
                <i class="fas fa-edit me-2"></i>Edit Campaign
            </h1>
            <p class="text-muted mb-0">Update campaign details and settings</p>
        </div>
        <div class="d-flex align-items-center">
            <div class="me-3">
                @php
                    $statusColors = [
                        'draft' => 'warning',
                        'live' => 'success',
                        'paused' => 'secondary'
                    ];
                    $statusIcons = [
                        'draft' => 'pencil-alt',
                        'live' => 'play-circle',
                        'paused' => 'pause-circle'
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$campaign->status] }} px-3 py-2">
                    <i class="fas fa-{{ $statusIcons[$campaign->status] }} me-1"></i>
                    {{ ucfirst($campaign->status) }}
                </span>
            </div>
            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Campaign Stats -->
    {{-- <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Campaign ID</h6>
                            <h4 class="mb-0 fw-bold">#{{ $campaign->id }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Created</h6>
                            <h5 class="mb-0 fw-bold">{{ $campaign->created_at->format('M d, Y') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Last Updated</h6>
                            <h5 class="mb-0 fw-bold">{{ $campaign->updated_at->diffForHumans() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-history"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Duration</h6>
                            <h5 class="mb-0 fw-bold">
                                @if($campaign->ends_at)
                                    {{ $campaign->starts_at->diffInDays($campaign->ends_at) }} days
                                @else
                                    Ongoing
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Form -->
    <form method="POST" action="{{ route('admin.campaigns.update', $campaign->id) }}" id="campaignForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column: Campaign Content -->
            <div class="col-lg-8">
                <!-- Campaign Details Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>Campaign Details
                        </h6>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-edit me-1"></i>Editing
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Campaign Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">
                                        <span class="text-danger">*</span> Campaign Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-tag text-muted"></i>
                                        </span>
                                        <input type="text"
                                               name="name"
                                               class="form-control form-control-lg"
                                               value="{{ old('name', $campaign->name) }}"
                                               placeholder="e.g., Summer Sale 2024"
                                               required>
                                    </div>
                                    {{-- <small class="form-text text-muted">
                                        Internal name for campaign identification
                                    </small> --}}
                                </div>
                            </div>

                            <!-- Campaign Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">
                                        <span class="text-danger">*</span> Display Title
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-heading text-muted"></i>
                                        </span>
                                        <input type="text"
                                               name="title"
                                               class="form-control form-control-lg"
                                               value="{{ old('title', $campaign->title) }}"
                                               placeholder="e.g., Summer Sale - Up to 50% Off!"
                                               required>
                                    </div>
                                    {{-- <small class="form-text text-muted">
                                        Main headline visible to users
                                    </small> --}}
                                </div>
                            </div>

                            <!-- Subtitle -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">
                                        Subtitle / Description
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light align-items-start pt-3">
                                            <i class="fas fa-align-left text-muted"></i>
                                        </span>
                                        <textarea name="subtitle"
                                                  class="form-control"
                                                  rows="2"
                                                  placeholder="Brief description or supporting text for your campaign">{{ old('subtitle', $campaign->subtitle) }}</textarea>
                                    </div>
                                    {{-- <small class="form-text text-muted">
                                        Optional supporting text below the title
                                    </small> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-mouse-pointer text-success me-2"></i>Call to Action
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- CTA Text -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">CTA Button Text</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-hand-pointer text-muted"></i>
                                        </span>
                                        <input type="text"
                                               name="cta_text"
                                               class="form-control"
                                               value="{{ old('cta_text', $campaign->cta_text) }}"
                                               placeholder="e.g., Shop Now, Learn More">
                                    </div>
                                    {{-- <small class="form-text text-muted">
                                        Text displayed on the action button
                                    </small> --}}
                                </div>
                            </div>

                            <!-- CTA URL -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">CTA Destination URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-link text-muted"></i>
                                        </span>
                                        <input type="url"
                                               name="cta_url"
                                               class="form-control"
                                               value="{{ old('cta_url', $campaign->cta_url) }}"
                                               placeholder="https://example.com/landing-page">
                                    </div>
                                    @if($campaign->cta_url)
                                    <small class="form-text">
                                        <a href="{{ $campaign->cta_url }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-external-link-alt me-1"></i>Test link
                                        </a>
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="col-lg-4">
                <!-- Settings Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-cog text-warning me-2"></i>Campaign Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['draft', 'live', 'paused'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $campaign->status) == $status)>
                                    <i class="fas fa-{{ $statusIcons[$status] ?? 'circle' }} me-2"></i>
                                    {{ ucfirst($status) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Schedule -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Campaign Schedule</label>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Start Date & Time</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-play text-success"></i>
                                    </span>
                                    <input type="datetime-local"
                                           name="starts_at"
                                           class="form-control"
                                           value="{{ old('starts_at', $campaign->starts_at?->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>

                            <div class="">
                                <label class="form-label small text-muted">End Date & Time</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-stop text-danger"></i>
                                    </span>
                                    <input type="datetime-local"
                                           name="ends_at"
                                           class="form-control"
                                           value="{{ old('ends_at', $campaign->ends_at?->format('Y-m-d\TH:i')) }}">
                                </div>
                                {{-- <small class="form-text text-muted">
                                    Leave empty for indefinite campaign
                                </small> --}}
                            </div>
                        </div>

                        <!-- Consent Toggle -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="requires_consent"
                                       name="requires_consent"
                                       value="1"
                                       {{ old('requires_consent', $campaign->requires_consent ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="requires_consent">
                                    Require User Consent
                                </label>
                            </div>
                            {{-- <small class="form-text text-muted">
                                Users must opt-in to receive this campaign
                            </small> --}}
                        </div>
                    </div>
                </div>

                <!-- Preview Card -->
                {{-- <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-eye text-info me-2"></i>Live Preview
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="preview-container p-3 border rounded bg-light">
                            <h5 id="preview-title" class="fw-bold mb-2">{{ $campaign->title }}</h5>
                            <p id="preview-subtitle" class="text-muted small mb-3">{{ $campaign->subtitle ?: 'Campaign description will appear here' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <button id="preview-cta" class="btn btn-primary btn-sm {{ !$campaign->cta_text ? 'disabled' : '' }}">
                                    {{ $campaign->cta_text ?: 'CTA Button' }}
                                </button>
                                <span class="badge bg-{{ $statusColors[$campaign->status] }} small">
                                    <i class="fas fa-{{ $statusIcons[$campaign->status] }} me-1"></i>
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </div>
                        </div>
                        <small class="form-text text-muted mt-2">
                            Preview updates as you type
                        </small>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- Form Actions -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save me-2"></i>Update Campaign
                        </button>
                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="window.history.back()">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                    </div>
                    {{-- <div class="text-end">
                        <small class="text-muted d-block">
                            <i class="fas fa-history me-1"></i>Last saved {{ $campaign->updated_at->diffForHumans() }}
                        </small>
                    </div> --}}
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .preview-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .card {
        border-radius: 10px;
    }
    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
    }
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
</style>

<script>
    // Live preview functionality for edit view
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.querySelector('input[name="title"]');
        const subtitleInput = document.querySelector('textarea[name="subtitle"]');
        const ctaInput = document.querySelector('input[name="cta_text"]');
        const statusSelect = document.querySelector('select[name="status"]');

        const previewTitle = document.getElementById('preview-title');
        const previewSubtitle = document.getElementById('preview-subtitle');
        const previewCta = document.getElementById('preview-cta');
        const previewStatus = document.querySelector('#preview-cta').nextElementSibling;

        const statusColors = {
            'draft': 'warning',
            'live': 'success',
            'paused': 'secondary'
        };
        const statusIcons = {
            'draft': 'pencil-alt',
            'live': 'play-circle',
            'paused': 'pause-circle'
        };

        function updatePreview() {
            // Update title
            previewTitle.textContent = titleInput.value || 'Your Campaign Title';

            // Update subtitle
            previewSubtitle.textContent = subtitleInput.value || 'Campaign description will appear here';

            // Update CTA
            if (ctaInput.value) {
                previewCta.textContent = ctaInput.value;
                previewCta.disabled = false;
                previewCta.classList.remove('disabled');
            } else {
                previewCta.textContent = 'CTA Button';
                previewCta.disabled = true;
                previewCta.classList.add('disabled');
            }

            // Update status badge
            const selectedStatus = statusSelect.value;
            const statusText = statusSelect.options[statusSelect.selectedIndex].text;
            previewStatus.innerHTML = `<i class="fas fa-${statusIcons[selectedStatus]} me-1"></i>${selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1)}`;
            previewStatus.className = `badge bg-${statusColors[selectedStatus]} small`;
        }

        // Add event listeners
        [titleInput, subtitleInput, ctaInput, statusSelect].forEach(input => {
            if (input) input.addEventListener('input', updatePreview);
        });
    });
</script>
@endpush
