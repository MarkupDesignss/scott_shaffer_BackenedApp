@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>There were some problems with your input.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 style="font-weight:800;font-size:1.5rem">
                            <i class="fas fa-edit text-warning me-2"></i>Edit Catalog Item
                        </h5>
                        <p class="text-muted mb-0">Update item details</p>
                    </div>
                    <a href="{{ route('admin.catalog-items.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.catalog-items.update', $item->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- LEFT --}}
                        <div class="col-lg-8">

                            {{-- BASIC INFO --}}
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <h6 class="fw-semibold mb-0">Basic Information</h6>
                                </div>

                                <div class="card-body">

                                    {{-- NAME --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            Item Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $item->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- CATEGORY --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            Category <span class="text-danger">*</span>
                                        </label>
                                        <select name="category_id" id="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror" required>
                                            <option value="">Select category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- SUB CATEGORY --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            Sub Category <span class="text-danger">*</span>
                                        </label>
                                        <select name="sub_category_id" id="sub_category_id"
                                            class="form-select @error('sub_category_id') is-invalid @enderror" >
                                            <option value="">Loading...</option>
                                        </select>
                                        @error('sub_category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- DESCRIPTION --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $item->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            {{-- MEDIA --}}
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <h6 class="fw-semibold mb-0">Media</h6>
                                </div>

                                <div class="card-body">

                                    {{-- CURRENT IMAGE --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Current Image</label><br>
                                        @if ($item->image_url)
                                            <img src="{{ Str::startsWith($item->image_url, ['http', 'https']) ? $item->image_url : asset('storage/' . $item->image_url) }}"
                                                class="img-thumbnail mb-2" style="max-height:120px">
                                        @else
                                            <div class="text-muted">No image set</div>
                                        @endif
                                    </div>

                                    {{-- IMAGE URL --}}
                                    <!--<div class="mb-3">-->
                                    <!--    <label class="form-label">New Image URL</label>-->
                                    <!--    <input type="text" name="image_url" id="image_url" class="form-control"-->
                                    <!--        value="{{ old('image_url', $item->image_url) }}">-->
                                    <!--</div>-->

                                    {{-- IMAGE UPLOAD --}}
                                    <div class="mb-3">
                                        <label class="form-label">Or Upload New Image</label>
                                        <input type="file" name="image_upload" id="image_upload" class="form-control"
                                            accept="image/*">
                                    </div>

                                    {{-- LIVE PREVIEW --}}
                                    <div class="mt-3" id="imagePreview" style="display:none;">
                                        <img id="previewImage" class="img-fluid rounded" style="max-height:150px">
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="col-lg-4">
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <h6 class="fw-semibold mb-0">Settings</h6>
                                </div>

                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="1"
                                                {{ old('status', $item->status) == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0"
                                                {{ old('status', $item->status) == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-warning btn-lg">
                                            <i class="fas fa-save me-2"></i>Update Item
                                        </button>
                                        <a href="{{ route('admin.catalog-items.index') }}"
                                            class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection





<style>
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
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

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .image-preview {
        background-color: #f8f9fa;
    }

    .needs-validation .form-control:valid,
    .needs-validation .form-select:valid {
        border-color: #198754;
    }

    .needs-validation .form-control:invalid,
    .needs-validation .form-select:invalid {
        border-color: #dc3545;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const categorySelect = document.getElementById('category_id');
        const subCategorySelect = document.getElementById('sub_category_id');

        const selectedSubCategory = "{{ $item->sub_category_id }}";

        function loadSubCategories(categoryId, selectedId = null) {
            subCategorySelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/scott-shafer/admin/get-subcategories/${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    subCategorySelect.innerHTML = '<option value="">Select sub category</option>';

                    data.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = sub.name;

                        if (selectedId && selectedId == sub.id) {
                            option.selected = true;
                        }

                        subCategorySelect.appendChild(option);
                    });
                });
        }

        // Initial load
        if (categorySelect.value) {
            loadSubCategories(categorySelect.value, selectedSubCategory);
        }

        // Change event
        categorySelect.addEventListener('change', function() {
            loadSubCategories(this.value);
        });

        // IMAGE PREVIEW
        const imageUrlInput = document.getElementById('image_url');
        const imageUploadInput = document.getElementById('image_upload');
        const previewBox = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');

        imageUrlInput.addEventListener('input', function() {
            if (this.value) {
                previewImage.src = this.value;
                previewBox.style.display = 'block';
                imageUploadInput.value = '';
            } else {
                previewBox.style.display = 'none';
            }
        });

        imageUploadInput.addEventListener('change', function() {
            if (this.files.length) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImage.src = e.target.result;
                    previewBox.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
                imageUrlInput.value = '';
            }
        });

    });
</script>
