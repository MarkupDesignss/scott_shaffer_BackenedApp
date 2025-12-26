@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 " style="font-size: 1.5rem;font-weight:800">
                                <i class="fas fa-edit me-2 text-warning"></i>Edit Catalog Item
                            </h5>
                            <p class="text-muted mb-0">Update item details</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.catalog-items.index') }}"
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.catalog-items.update', $item->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card border mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-semibold">Basic Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="name" class="form-label fw-semibold">
                                                Item Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-tag text-muted"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       id="name"
                                                       name="name"
                                                       value="{{ old('name', $item->name) }}"
                                                       placeholder="Enter item name"
                                                       required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Enter a descriptive name for your item.</div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="category_id" class="form-label fw-semibold">
                                                Category <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('category_id') is-invalid @enderror"
                                                    id="category_id"
                                                    name="category_id"
                                                    required>
                                                <option value="">Select a category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                Choose a category for this item.
                                                <a href="{{ route('admin.catalog-categories.create') }}"
                                                   class="text-decoration-none ms-2">
                                                    <i class="fas fa-plus"></i> Add new category
                                                </a>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="description" class="form-label fw-semibold">
                                                Description
                                            </label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                      id="description"
                                                      name="description"
                                                      rows="4"
                                                      placeholder="Enter item description">{{ old('description', $item->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <span id="charCount">{{ strlen($item->description ?? '') }}</span> characters
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-semibold">Media</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Image</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Current Image</label>
                                                        @if($item->image_url)
                                                            @php
                                                                $imgSrc = Str::startsWith($item->image_url, ['http://', 'https://'])
                                                                          ? $item->image_url
                                                                          : asset('storage/' . $item->image_url);
                                                            @endphp
                                                            <div class="mb-2">
                                                                <img src="{{ $imgSrc }}"
                                                                     alt="{{ $item->name }}"
                                                                     class="img-thumbnail"
                                                                     style="max-height: 100px;">
                                                            </div>
                                                        @else
                                                            <div class="text-muted mb-2">
                                                                <i class="fas fa-image me-1"></i> No image set
                                                            </div>
                                                        @endif

                                                        <label class="form-label">New Image URL</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="fas fa-link text-muted"></i>
                                                            </span>
                                                            <input type="url"
                                                                   class="form-control @error('image_url') is-invalid @enderror"
                                                                   name="image_url"
                                                                   value="{{ old('image_url', $item->image_url) }}"
                                                                   placeholder="https://example.com/image.jpg">
                                                        </div>
                                                        @error('image_url')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Or Upload New Image</label>
                                                    <div class="input-group">
                                                        <input type="file"
                                                               class="form-control @error('image_upload') is-invalid @enderror"
                                                               name="image_upload"
                                                               accept="image/*">
                                                    </div>
                                                    @error('image_upload')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Leave empty to keep current image
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-text">Provide either an image URL or upload an image file.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card border mb-4">
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
                                                <option value="1" {{ old('status', $item->status) == '1' ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('status', $item->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Active items are visible to customers.</div>
                                        </div>

                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" class="btn btn-warning btn-lg">
                                                Update Item
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
.bg-light-primary { background-color: rgba(13, 110, 253, 0.1) !important; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
.bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1) !important; }
.text-success { color: #198754 !important; }
.form-label { font-weight: 500; margin-bottom: 0.5rem; }
.input-group-text { background-color: #f8f9fa; border-color: #dee2e6; }
.image-preview { background-color: #f8f9fa; }
.needs-validation .form-control:valid, .needs-validation .form-select:valid { border-color: #198754; }
.needs-validation .form-control:invalid, .needs-validation .form-select:invalid { border-color: #dc3545; }
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

    // Character counter
    const descriptionInput = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    if (descriptionInput && charCount) {
        descriptionInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    // Image preview functionality
    const imageUrlInput = document.querySelector('input[name="image_url"]');
    const imageUploadInput = document.querySelector('input[name="image_upload"]');
    const liveImagePreview = document.getElementById('liveImagePreview');

    function updatePreview(src) {
        liveImagePreview.innerHTML = src
            ? `<img src="${src}" class="img-fluid rounded" style="max-height: 150px;">`
            : '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="fas fa-image fa-3x text-muted"></i></div>';
    }

    if (imageUrlInput) {
        imageUrlInput.addEventListener('input', function() {
            const src = this.value.startsWith('http') ? this.value : `{{ asset('storage') }}/${this.value}`;
            updatePreview(this.value ? src : null);
        });
    }

    if (imageUploadInput) {
        imageUploadInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { updatePreview(e.target.result); };
                reader.readAsDataURL(file);
            }
        });
    }

    // Reset form
    const resetButton = document.getElementById('resetForm');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all changes?')) {
                window.location.reload();
            }
        });
    }
});
</script>