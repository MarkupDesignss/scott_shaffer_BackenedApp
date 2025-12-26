@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0" style="font-size: 1.5rem;font-weight:800">
                                <i class="fas fa-edit me-2 text-warning"></i>Edit Category
                            </h5>
                            <p class="text-muted mb-0">Update category details</p>
                        </div>
                        <div class="d-flex gap-2">
                            {{-- <a href="{{ route('admin.catalog-categories.show', $category->id) }}"
                               class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>View
                            </a> --}}
                            <a href="{{ route('admin.catalog-categories.index') }}"
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.catalog-categories.update', $category->id) }}"
                          class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold">
                                        Category Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-tag text-muted"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $category->name) }}"
                                               placeholder="Enter category name"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">This name will be displayed to customers.</div>
                                </div>

                                <div class="mb-4">
                                    <label for="interest_id" class="form-label fw-semibold">
                                        <i class="fas fa-heart me-1"></i> Interest
                                    </label>
                                    <select name="interest_id" class="form-select @error('interest_id') is-invalid @enderror" id="interest_id">
                                        <option value="">Select Interest</option>
                                        @foreach($interests as $interest)
                                            <option value="{{ $interest->id }}"
                                                {{ old('interest_id', $category->interest_id ?? '') == $interest->id ? 'selected' : '' }}>
                                                {{ $interest->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('interest_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Link this category to an interest (optional)</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-semibold">Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="status" class="form-label fw-semibold">Status</label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                    id="status"
                                                    name="status"
                                                    required>
                                                <option value="1" {{ old('status', $category->status) == '1' ? 'selected' : '' }}>
                                                    <i class="fas fa-circle text-success me-2"></i>Active
                                                </option>
                                                <option value="0" {{ old('status', $category->status) == '0' ? 'selected' : '' }}>
                                                    <i class="fas fa-circle text-secondary me-2"></i>Inactive
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Active categories are visible to customers.</div>
                                        </div>

                                        {{-- <div class="mb-4">
                                            <label class="form-label fw-semibold">Created</label>
                                            <div class="text-muted">
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $category->created_at->format('M d, Y h:i A') }}
                                            </div>
                                        </div> --}}

                                        {{-- <div class="mb-4">
                                            <label class="form-label fw-semibold">Last Updated</label>
                                            <div class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $category->updated_at->format('M d, Y h:i A') }}
                                            </div>
                                        </div> --}}

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-warning btn-lg">
                                                </i>Update Category
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" id="resetForm">
                                                <i class="fas fa-redo me-2"></i>Reset Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.color-option {
    width: 30px;
    height: 30px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s;
}

.color-option:hover {
    transform: scale(1.1);
    border-color: #dee2e6;
}

.color-option.active {
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

.preview-card {
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-secondary-soft {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.text-success {
    color: #198754 !important;
}

.text-secondary {
    color: #6c757d !important;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.needs-validation .form-control:valid,
.needs-validation .form-select:valid {
    border-color: #198754;
}

.needs-validation .form-control:invalid,
.needs-validation .form-select:invalid {
    border-color: #dc3545;
}

.form-select option {
    padding: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Live preview for name
    const nameInput = document.getElementById('name');
    const namePreview = document.getElementById('liveNamePreview');

    if (nameInput && namePreview) {
        nameInput.addEventListener('input', function() {
            namePreview.textContent = this.value || 'Category Name';
        });
    }

    // Live preview for icon
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('liveIconPreview');

    if (iconInput && iconPreview) {
        // Initialize with current value
        const currentIcon = iconInput.value || 'fas fa-folder';
        iconPreview.innerHTML = `<i class="${currentIcon} fa-2x"></i>`;

        iconInput.addEventListener('input', function() {
            if (this.value) {
                iconPreview.innerHTML = `<i class="${this.value} fa-2x"></i>`;
            } else {
                iconPreview.innerHTML = '<i class="fas fa-folder fa-2x"></i>';
            }
        });
    }

    // Color picker functionality
    const colorInput = document.getElementById('color');
    const colorOptions = document.querySelectorAll('.color-option');

    // Set active color based on current value
    const currentColor = colorInput.value;
    if (currentColor) {
        colorOptions.forEach(option => {
            if (option.getAttribute('data-color') === currentColor) {
                option.classList.add('active');
            }
        });
    }

    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            colorOptions.forEach(opt => opt.classList.remove('active'));

            // Add active class to clicked option
            this.classList.add('active');

            // Update color input value
            colorInput.value = this.getAttribute('data-color');

            // Update preview icon color
            if (iconPreview) {
                const icon = iconPreview.querySelector('i');
                if (icon) {
                    icon.style.color = colorInput.value;
                }
            }
        });
    });

    // Live preview for color
    if (colorInput && iconPreview) {
        const initialColor = colorInput.value || '#0d6efd';
        const icon = iconPreview.querySelector('i');
        if (icon) {
            icon.style.color = initialColor;
        }

        colorInput.addEventListener('input', function() {
            const icon = iconPreview.querySelector('i');
            if (icon) {
                icon.style.color = this.value || '#0d6efd';
            }
        });
    }

    // Status preview
    const statusSelect = document.getElementById('status');
    const statusPreview = document.getElementById('liveStatusPreview');

    if (statusSelect && statusPreview) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'active') {
                statusPreview.className = 'badge bg-success-soft text-success rounded-pill px-3 py-1';
                statusPreview.innerHTML = '<i class="fas fa-circle me-1" style="font-size: 8px"></i>Active';
            } else {
                statusPreview.className = 'badge bg-secondary-soft text-secondary rounded-pill px-3 py-1';
                statusPreview.innerHTML = '<i class="fas fa-circle me-1" style="font-size: 8px"></i>Inactive';
            }
        });
    }

    // Reset form button
    const resetButton = document.getElementById('resetForm');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all changes?')) {
                window.location.reload();
            }
        });
    }

    // Icon suggestions
    const iconSuggestions = [
        'fas fa-folder',
        'fas fa-box',
        'fas fa-shopping-bag',
        'fas fa-tags',
        'fas fa-gift',
        'fas fa-tshirt',
        'fas fa-laptop',
        'fas fa-mobile-alt',
        'fas fa-home',
        'fas fa-utensils',
        'fas fa-book',
        'fas fa-dumbbell',
        'fas fa-car',
        'fas fa-heart',
        'fas fa-star'
    ];

    const iconPreviewContainer = document.getElementById('iconPreview');
    if (iconPreviewContainer) {
        iconSuggestions.forEach(iconClass => {
            const iconElement = document.createElement('div');
            iconElement.className = 'icon-suggestion';
            iconElement.innerHTML = `<i class="${iconClass} fa-lg"></i>`;
            iconElement.style.cssText = 'width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; border-radius: 4px; border: 1px solid #dee2e6;';
            iconElement.title = iconClass;

            iconElement.addEventListener('click', function() {
                iconInput.value = iconClass;
                iconInput.dispatchEvent(new Event('input'));
            });

            iconElement.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
                this.style.borderColor = '#0d6efd';
            });

            iconElement.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
                this.style.borderColor = '#dee2e6';
            });

            iconPreviewContainer.appendChild(iconElement);
        });
    }
});
</script>
