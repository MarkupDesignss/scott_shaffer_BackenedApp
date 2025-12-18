@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"  style="font-size: 1.5rem;font-weight:800">Create New Interest</h1>
            <p class="text-muted mb-0">Add a new interest category for users</p>
        </div>
        <a href="{{ route('admin.interest.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6" style="display: contents;">
            <!-- Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Live Preview -->
                    <div class="text-center mb-5">
                        <h6 class="text-muted mb-3">Live Preview</h6>
                        <div class="interest-preview">
                            <div class="preview-icon-wrapper d-inline-flex align-items-center justify-content-center rounded-circle bg-light"
                                 style="width: 90px; height: 90px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <i id="iconPreview" class="bi bi-plus-circle" style="font-size: 2.5rem; color: #6c757d;"></i>
                            </div>
                            <h4 class="mt-4 mb-0" id="namePreview">New Interest</h4>
                            <small class="text-muted">Preview will update as you type</small>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div>
                                    <strong class="d-block mb-1">Please fix the following errors:</strong>
                                    <ul class="mb-0 mt-2 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Creation Form -->
                    <form action="{{ route('admin.interest.store') }}" method="POST" id="interestForm" onsubmit="return validateForm()">
                        @csrf

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-card-heading me-1"></i> Interest Name
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-tag"></i>
                                </span>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') }}"
                                       class="form-control form-control-lg border-start-0"
                                       placeholder="Enter a descriptive name"
                                       required
                                       autofocus
                                       maxlength="50">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="form-text text-muted">Choose a clear, concise name that users will recognize</small>
                                <small class="text-muted"><span id="charCount">0</span>/50</small>
                            </div>
                        </div>

                        <!-- Icon Selection -->
                        <div class="mb-4">
                            <label for="icon" class="form-label fw-semibold">
                                <i class="bi bi-emoji-smile me-1"></i> Icon
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-palette"></i>
                                </span>
                                <input type="text"
                                       name="icon"
                                       id="icon"
                                       value="{{ old('icon') }}"
                                       class="form-control border-start-0"
                                       placeholder="bi bi-heart (optional)">
                                <button type="button" class="btn btn-outline-secondary border-start-0" onclick="clearIcon()" title="Clear icon">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Enter Bootstrap Icons class or leave empty for default.
                                <a href="https://icons.getbootstrap.com/" target="_blank" class="ms-1">
                                    Browse icons <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Icon Suggestions -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted fw-semibold">Popular Icon Suggestions:</small>
                                <button type="button" class="btn btn-sm btn-link p-0" onclick="toggleIconGrid()">
                                    <i class="bi bi-grid me-1"></i> Show all
                                </button>
                            </div>
                            <div class="icon-suggestions">
                                <div class="row g-2" id="iconGrid">
                                    @php
                                        $iconCategories = [
                                            'General' => ['bi bi-heart', 'bi bi-star', 'bi bi-bookmark', 'bi bi-flag'],
                                            'Activities' => ['bi bi-controller', 'bi bi-music-note', 'bi bi-camera', 'bi bi-book'],
                                            'Sports' => ['bi bi-bicycle', 'bi bi-joystick', 'bi bi-trophy', 'bi bi-dice-5'],
                                            'Arts' => ['bi bi-palette', 'bi bi-music-note-beamed', 'bi bi-camera-reels', 'bi bi-brush'],
                                            'Tech' => ['bi bi-code-slash', 'bi bi-laptop', 'bi bi-phone', 'bi bi-cpu'],
                                            'Food' => ['bi bi-cup-straw', 'bi bi-egg-fried', 'bi bi-emoji-sunglasses', 'bi bi-basket']
                                        ];
                                    @endphp

                                    @foreach($iconCategories as $category => $icons)
                                        <div class="col-12 mb-2">
                                            <small class="text-muted d-block mb-2">{{ $category }}</small>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($icons as $icon)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-light border d-flex flex-column align-items-center justify-content-center p-2"
                                                            onclick="selectIcon('{{ $icon }}')"
                                                            style="width: 70px; height: 70px;"
                                                            title="{{ str_replace('bi bi-', '', $icon) }}">
                                                       <i class="{{ $icon }} fs-5 mb-1" style="color:#212529;"></i>

                                                        <small class="text-muted">{{ str_replace('bi bi-', '', $icon) }}</small>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Default Status -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-toggle-on me-1"></i> Initial Status
                            </label>
                            <div class="form-check form-switch ps-0 m-lg-2">
                                <div class="d-flex align-items-center" style="margin-left: 30px">
                                    <input class="form-check-input me-3"
                                           type="checkbox"
                                           role="switch"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           checked>
                                    <div>
                                        <label class="form-check-label fw-medium" for="is_active">
                                            Active
                                        </label>
                                        <small class="text-muted d-block">Active interests will be immediately available to users</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end border-top pt-4">
                            <a href="{{ route('admin.interest.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-plus-circle me-1"></i> Create Interest
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Card -->
            {{-- <div class="card border-0 shadow-sm mt-4 bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-lightbulb me-2"></i> Tips for Creating Great Interests
                    </h6>
                    <ul class="mb-0 text-muted">
                        <li class="mb-2">Use clear, descriptive names that users will understand</li>
                        <li class="mb-2">Keep names concise (under 30 characters recommended)</li>
                        <li class="mb-2">Choose relevant icons that match the interest category</li>
                        <li class="mb-2">Consider how the interest will be used in user profiles</li>
                        <li>Test new interests with a small user group first</li>
                    </ul>
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
        border-radius: 8px;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .interest-preview {
        padding: 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }

    .preview-icon-wrapper {
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        50% { box-shadow: 0 4px 20px rgba(13, 110, 253, 0.2); }
        100% { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
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

    .btn-outline-light:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .btn-outline-light:hover i,
    .btn-outline-light:hover small {
        color: white !important;
    }

    .icon-suggestions .btn {
        transition: all 0.2s ease;
    }

    .icon-suggestions .btn:hover {
        transform: translateY(-2px);
    }

    .alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .tips-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .char-count {
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .interest-preview {
            padding: 1.5rem;
        }

        .icon-suggestions .btn {
            width: 60px;
            height: 60px;
        }
    }
</style>

<script>
    // Initialize live preview
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const iconInput = document.getElementById('icon');
        const namePreview = document.getElementById('namePreview');
        const iconPreview = document.getElementById('iconPreview');
        const charCount = document.getElementById('charCount');

        // Update name preview and character count
        nameInput.addEventListener('input', function() {
            const value = this.value.trim();
            namePreview.textContent = value || 'New Interest';
            charCount.textContent = value.length;

            // Color indicator for length
            if (value.length > 45) {
                charCount.style.color = '#dc3545';
            } else if (value.length > 30) {
                charCount.style.color = '#ffc107';
            } else {
                charCount.style.color = '#6c757d';
            }
        });

        // Update icon preview
        iconInput.addEventListener('input', function() {
            const iconClass = this.value.trim();
            if (iconClass) {
                iconPreview.className = iconClass;
                iconPreview.style.fontSize = '2.5rem';
            } else {
                iconPreview.className = 'bi bi-plus-circle';
            }
        });

        // Animate icon selection
        iconPreview.addEventListener('click', function() {
            this.style.transform = 'scale(1.2) rotate(180deg)';
            setTimeout(() => {
                this.style.transform = 'scale(1) rotate(0deg)';
            }, 300);
        });

        // Initialize character count
        charCount.textContent = nameInput.value.length;

        // Add animation to form elements
        const formCard = document.querySelector('.card');
        formCard.style.opacity = '0';
        formCard.style.transform = 'translateY(20px)';

        setTimeout(() => {
            formCard.style.opacity = '1';
            formCard.style.transform = 'translateY(0)';
            formCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        }, 100);
    });

    // Select icon from suggestions
    function selectIcon(iconClass) {
        const iconInput = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');

        iconInput.value = iconClass;
        iconPreview.className = iconClass;
        iconPreview.style.fontSize = '2.5rem';

        // Animate the icon change
        iconPreview.style.transform = 'scale(1.3)';
        setTimeout(() => {
            iconPreview.style.transform = 'scale(1)';
        }, 200);

        // Visual feedback on selected button
        const buttons = document.querySelectorAll('.icon-suggestions .btn');
        buttons.forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-light');
        });

        event.target.closest('.btn').classList.remove('btn-outline-light');
        event.target.closest('.btn').classList.add('btn-primary');
    }

    // Clear icon field
    function clearIcon() {
        const iconInput = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');

        iconInput.value = '';
        iconPreview.className = 'bi bi-plus-circle';

        // Reset icon buttons
        const buttons = document.querySelectorAll('.icon-suggestions .btn');
        buttons.forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-light');
        });

        // Animate the icon change
        iconPreview.style.transform = 'scale(0.8)';
        setTimeout(() => {
            iconPreview.style.transform = 'scale(1)';
        }, 200);
    }

    // Toggle icon grid visibility
    function toggleIconGrid() {
        const iconGrid = document.getElementById('iconGrid');
        const toggleBtn = event.target.closest('button');

        if (iconGrid.style.display === 'none' || iconGrid.style.display === '') {
            iconGrid.style.display = 'block';
            toggleBtn.innerHTML = '<i class="bi bi-grid me-1"></i> Show less';
        } else {
            iconGrid.style.display = 'none';
            toggleBtn.innerHTML = '<i class="bi bi-grid me-1"></i> Show all';
        }
    }

    // Form validation
    function validateForm() {
        const nameInput = document.getElementById('name');
        const nameValue = nameInput.value.trim();

        if (!nameValue) {
            nameInput.focus();
            nameInput.classList.add('is-invalid');

            // Add error message
            if (!document.getElementById('nameError')) {
                const errorDiv = document.createElement('div');
                errorDiv.id = 'nameError';
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.textContent = 'Please enter an interest name';
                nameInput.parentNode.appendChild(errorDiv);
            }

            return false;
        }

        if (nameValue.length > 50) {
            nameInput.focus();
            nameInput.classList.add('is-invalid');

            if (!document.getElementById('nameError')) {
                const errorDiv = document.createElement('div');
                errorDiv.id = 'nameError';
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.textContent = 'Interest name must be 50 characters or less';
                nameInput.parentNode.appendChild(errorDiv);
            }

            return false;
        }

        return true;
    }

    // Remove error on input
    document.getElementById('name').addEventListener('input', function() {
        this.classList.remove('is-invalid');
        const errorDiv = document.getElementById('nameError');
        if (errorDiv) {
            errorDiv.remove();
        }
    });
</script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

@endsection
