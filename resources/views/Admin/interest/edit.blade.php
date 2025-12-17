@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="font-size: 1.5rem;font-weight:800">Edit Interest</h1>
            <p class="text-muted mb-0">Update interest details and icon</p>
        </div>
        <a href="{{ route('admin.interest.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6" style="display: contents;">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Interest Preview -->
                    <div class="text-center mb-4">
                        <div class="interest-preview mb-3">
                            <div class="preview-icon-wrapper d-inline-flex align-items-center justify-content-center rounded-circle bg-light"
                                 style="width: 80px; height: 80px; margin: 0 auto;">
                                <i id="iconPreview" class="{{ $interest->icon ?? 'bi bi-tag' }}" style="font-size: 2rem; color: #6c757d;"></i>
                            </div>
                            <h5 class="mt-3 mb-0" id="namePreview">{{ $interest->name }}</h5>
                            <small class="text-muted">Preview</small>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Please fix the following errors:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Edit Form -->
                    <form action="{{ route('admin.interest.update', $interest) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-tag me-1"></i> Interest Name
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-fonts"></i>
                                </span>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', $interest->name) }}"
                                       class="form-control form-control-lg"
                                       placeholder="Enter interest name"
                                       required
                                       autofocus>
                            </div>
                            <div class="form-text">Enter a descriptive name for this interest (e.g., "Photography", "Cooking")</div>
                        </div>

                        <!-- Icon Field with Preview -->
                        <div class="mb-4">
                            <label for="icon" class="form-label fw-semibold">
                                <i class="bi bi-emoji-smile me-1"></i> Icon Class
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-emoji-smile"></i>
                                </span>
                                <input type="text"
                                       name="icon"
                                       id="icon"
                                       value="{{ old('icon', $interest->icon) }}"
                                       class="form-control"
                                       placeholder="bi bi-heart">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearIcon()">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                Enter Bootstrap Icons class (e.g., "bi bi-heart").
                                <a href="https://icons.getbootstrap.com/" target="_blank" class="ms-1">
                                    Browse icons <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>

                            <!-- Icon Suggestions -->
                            <div class="mt-3">
                                <small class="text-muted mb-2 d-block">Quick Pick:</small>
                                <div class="d-flex flex-wrap gap-2">
                                    @php
                                        $iconSuggestions = [
                                            'bi bi-heart', 'bi bi-star', 'bi bi-music-note',
                                            'bi bi-camera', 'bi bi-controller', 'bi bi-book',
                                            'bi bi-joystick', 'bi bi-bicycle', 'bi bi-cup-straw',
                                            'bi bi-palette', 'bi bi-code-slash', 'bi bi-people'
                                        ];
                                    @endphp
                                    @foreach($iconSuggestions as $suggestion)
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                                                onclick="selectIcon('{{ $suggestion }}')">
                                            <i class="{{ $suggestion }} me-1"></i>
                                            {{ str_replace('bi bi-', '', $suggestion) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Status Field -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-toggle-on me-1"></i> Status
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ $interest->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                    <small class="text-muted d-block">Inactive interests won't appear for users</small>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end border-top pt-4">
                            <a href="{{ route('admin.interest.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Update Interest
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Card (Danger Zone) -->
            {{-- <div class="card border-0 shadow-sm border-danger mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title text-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Danger Zone
                    </h5>
                    <p class="text-muted mb-3">
                        Once you delete an interest, there is no going back. Please be certain.
                    </p>
                    <form action="{{ route('admin.interest.destroy', $interest) }}"
                          method="POST"
                          onsubmit="return confirmDelete()"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Delete This Interest
                        </button>
                    </form>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1.1rem;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .interest-preview {
        padding: 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        border: 2px dashed #dee2e6;
    }

    .preview-icon-wrapper {
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .preview-icon-wrapper:hover {
        transform: scale(1.1);
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    }

    .form-label {
        color: #495057;
        font-weight: 600;
    }

    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }

    .form-switch .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    } */

    /* Alert styling */
    .alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    /* Danger zone card */
    .card.border-danger {
        border: 2px solid #dc3545 !important;
    }

    /* Icon suggestion buttons */
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .interest-preview {
            padding: 1.5rem;
        }
    }
</style>

<script>
    // Live preview of interest name and icon
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const iconInput = document.getElementById('icon');
        const namePreview = document.getElementById('namePreview');
        const iconPreview = document.getElementById('iconPreview');

        // Update name preview
        nameInput.addEventListener('input', function() {
            namePreview.textContent = this.value || 'Interest Name';
        });

        // Update icon preview
        iconInput.addEventListener('input', function() {
            const iconClass = this.value.trim();
            if (iconClass) {
                iconPreview.className = iconClass;
                iconPreview.style.fontSize = '2rem';
            } else {
                iconPreview.className = 'bi bi-tag';
            }
        });

        // Add animation to icon preview
        iconPreview.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.3s ease';
        });

        iconPreview.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });

        // Animate form appearance
        const formElements = document.querySelectorAll('.card, .alert');
        formElements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });

    // Select icon from suggestions
    function selectIcon(iconClass) {
        const iconInput = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');

        iconInput.value = iconClass;
        iconPreview.className = iconClass;
        iconPreview.style.fontSize = '2rem';

        // Animate the icon change
        iconPreview.style.transform = 'scale(1.2)';
        setTimeout(() => {
            iconPreview.style.transform = 'scale(1)';
        }, 200);
    }

    // Clear icon field
    function clearIcon() {
        const iconInput = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');

        iconInput.value = '';
        iconPreview.className = 'bi bi-tag';

        // Animate the icon change
        iconPreview.style.transform = 'scale(0.8)';
        setTimeout(() => {
            iconPreview.style.transform = 'scale(1)';
        }, 200);
    }

    // Confirm delete action
    function confirmDelete() {
        const interestName = document.getElementById('name').value || 'this interest';
        return confirm(`Are you sure you want to delete "${interestName}"? This action cannot be undone.`);
    }

    // Form validation
    function validateForm() {
        const nameInput = document.getElementById('name');
        const nameValue = nameInput.value.trim();

        if (!nameValue) {
            nameInput.focus();
            nameInput.classList.add('is-invalid');
            return false;
        }

        return true;
    }
</script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

@endsection
