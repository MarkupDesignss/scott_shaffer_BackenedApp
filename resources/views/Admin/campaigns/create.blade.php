@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 mb-2 fw-bold text-primary">
                <i class="fas fa-bullhorn me-2"></i>Launch New Campaign
            </h1>
            <p class="text-muted mb-0">Create an engaging marketing campaign to reach your audience</p>
        </div>
        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    {{-- <div>
                        <small class="text-muted d-block">Campaign Type</small>
                        <strong>Marketing</strong>
                    </div> --}}
                </div>
            </div>
            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>View All Campaigns
            </a>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.campaigns.store') }}" id="campaignForm">
        @csrf

        <div class="row">
            <!-- Left Column: Campaign Content -->
            <div class="col-lg-8">
                <!-- Campaign Details Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>Campaign Details
                        </h6>
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
                                               value="{{ old('name') }}"

                                               required>
                                    </div>
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
                                               value="{{ old('title') }}"

                                               required>
                                    </div>
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
                                                  placeholder="Brief description or supporting text for your campaign">{{ old('subtitle') }}</textarea>
                                    </div>
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
                                               value="{{ old('cta_text') }}"
                                               >
                                    </div>

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
                                               value="{{ old('cta_url') }}"
                                               placeholder="https://example.com/landing-page">
                                    </div>
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
                                <option value="draft" selected>
                                    <i class="fas fa-pencil-alt me-2"></i>Draft
                                </option>
                                <option value="live">
                                    <i class="fas fa-play-circle me-2"></i>Live
                                </option>
                                <option value="paused">
                                    <i class="fas fa-pause-circle me-2"></i>Paused
                                </option>
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
                                           value="{{ now()->format('Y-m-d\TH:i') }}">
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
                                           class="form-control">
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
                                       checked>
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

            </div>
        </div>

        <!-- Form Actions -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-rocket me-2"></i>Launch Campaign
                        </button>
                        {{-- <button type="button" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-save me-2"></i>Save Draft
                        </button> --}}
                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-link text-muted ms-2">
                            Cancel
                        </a>
                    </div>
                    <div class="text-end">
                        {{-- <small class="text-muted d-block">
                            <i class="fas fa-info-circle me-1"></i>Fields marked with * are required
                        </small> --}}
                    </div>
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
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>


@push('scripts')
<script>
    // Live preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.querySelector('input[name="title"]');
        const subtitleInput = document.querySelector('textarea[name="subtitle"]');
        const ctaInput = document.querySelector('input[name="cta_text"]');
        const statusSelect = document.querySelector('select[name="status"]');

        const previewTitle = document.getElementById('preview-title');
        const previewSubtitle = document.getElementById('preview-subtitle');
        const previewCta = document.getElementById('preview-cta');
        const previewStatus = document.querySelector('#preview-cta').nextElementSibling;

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
            const statusText = statusSelect.options[statusSelect.selectedIndex].text;
            previewStatus.innerHTML = `<i class="fas fa-clock me-1"></i>${statusText}`;

            // Update status badge color
            const statusColors = {
                'draft': 'bg-light text-dark',
                'live': 'bg-success text-white',
                'paused': 'bg-warning text-dark'
            };
            const selectedStatus = statusSelect.value;
            previewStatus.className = `badge ${statusColors[selectedStatus]} small`;
        }

        // Add event listeners
        [titleInput, subtitleInput, ctaInput, statusSelect].forEach(input => {
            if (input) input.addEventListener('input', updatePreview);
        });

        // Initialize preview
        updatePreview();
    });
</script>
@endpush
@endsection
