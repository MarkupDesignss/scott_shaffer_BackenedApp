@extends('layouts.admin')

@section('content')
<div class="container-fluid">
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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

    <!-- Form -->
    <form method="POST" action="{{ route('admin.campaigns.update', $campaign->id) }}" id="campaignForm"  enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">
                                        <span class="text-danger">*</span> Campaign Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-tag text-muted"></i>
                                        </span>
                                        <input type="text"
                                               name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $campaign->name) }}"
                                               placeholder="e.g., Summer Sale 2024"
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Campaign Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">
                                        <span class="text-danger">*</span> Display Title
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-heading text-muted"></i>
                                        </span>
                                        <input type="text"
                                               name="title"
                                               class="form-control @error('title') is-invalid @enderror"
                                               value="{{ old('title', $campaign->title) }}"
                                               placeholder="e.g., Summer Sale - Up to 50% Off!"
                                               required>
                                    </div>
                                    @error('title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Subtitle -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">
                                        Subtitle / Description
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light align-items-start">
                                            <i class="fas fa-align-left text-muted"></i>
                                        </span>
                                        <textarea name="subtitle"
                                                  class="form-control @error('subtitle') is-invalid @enderror"
                                                  rows="3"
                                                  placeholder="Brief description or supporting text for your campaign">{{ old('subtitle', $campaign->subtitle) }}</textarea>
                                    </div>
                                    @error('subtitle')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Campaign Image</label>
                                    {{-- @dd($campaign->image_url); --}}
                                    @if($campaign->image_url)
                                        <div class="mb-2">
                                             <img src="{{ asset($campaign->image_url) }}"
                                                class="img-thumbnail"
                                                style="max-height: 120px;">
                                        </div>
                                    @endif

                                    <input type="file"
                                        name="image_url"
                                        class="form-control"
                                        accept="image/*">
                                </div>
                            </div>

                            <!-- Segments -->
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="form-label fw-semibold">
                                        <span class="text-danger">*</span> Target Segments
                                    </label>

                                    <div class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($segments as $segment)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="segments[]"
                                                    value="{{ $segment->id }}"
                                                    id="segment_{{ $segment->id }}"
                                                    {{ in_array($segment->id, old('segments', $campaign->segments->pluck('id')->toArray())) ? 'checked' : '' }}>

                                                <label class="form-check-label" for="segment_{{ $segment->id }}">
                                                    {{ $segment->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('segments')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Call to Action Card -->
                {{-- <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-mouse-pointer text-success me-2"></i>Call to Action
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- CTA Text -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">CTA Button Text</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-hand-pointer text-muted"></i>
                                        </span>
                                        <input type="text"
                                               name="cta_text"
                                               class="form-control @error('cta_text') is-invalid @enderror"
                                               value="{{ old('cta_text', $campaign->cta_text) }}"
                                               placeholder="e.g., Shop Now, Learn More">
                                    </div>
                                    @error('cta_text')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- CTA URL -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">CTA Destination URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-link text-muted"></i>
                                        </span>
                                        <input type="url"
                                               name="cta_url"
                                               class="form-control @error('cta_url') is-invalid @enderror"
                                               value="{{ old('cta_url', $campaign->cta_url) }}"
                                               placeholder="https://example.com/landing-page">
                                    </div>
                                    @error('cta_url')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @if($campaign->cta_url)
                                    <small class="form-text text-muted mt-1">
                                        <a href="{{ $campaign->cta_url }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-external-link-alt me-1"></i>Test link
                                        </a>
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
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
                            <label class="form-label fw-semibold mb-2">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                @foreach(['draft', 'live', 'paused'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $campaign->status) == $status)>
                                    {{ ucfirst($status) }}
                                </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">Campaign Schedule</label>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Start Date & Time</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-play text-success"></i>
                                    </span>
                                    <input type="datetime-local"
                                           name="starts_at"
                                           class="form-control @error('starts_at') is-invalid @enderror"
                                           value="{{ old('starts_at', $campaign->starts_at?->format('Y-m-d\TH:i')) }}">
                                </div>
                                @error('starts_at')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label class="form-label small text-muted">End Date & Time (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-stop text-danger"></i>
                                    </span>
                                    <input type="datetime-local"
                                           name="ends_at"
                                           class="form-control @error('ends_at') is-invalid @enderror"
                                           value="{{ old('ends_at', $campaign->ends_at?->format('Y-m-d\TH:i')) }}">
                                </div>
                                @error('ends_at')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Consent Toggle -->
                        <div class="mb-0">
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
                            <i class="fas fa-save me-2"></i>Update Campaign
                        </button>
                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="window.history.back()">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .card {
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    .card-header {
        background-color: #f8f9fa;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
    }
    .form-label {
        font-weight: 600;
        color: #495057;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    .form-control {
        border-left: none;
    }
</style>
@endsection
