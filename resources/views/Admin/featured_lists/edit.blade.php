@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Featured List</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.featured-lists.index') }}">Featured Lists</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.featured-lists.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Main Form Card -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Edit List Details</h6>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-hashtag me-1"></i>ID: {{ $featuredList->id }}
                    </span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.featured-lists.update', $featuredList) }}" id="editForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">List Title *</label>
                                <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title', $featuredList->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Category *</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $featuredList->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- List Size -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">List Size *</label>
                                <input type="number" name="list_size" class="form-control text-center" min="1" max="100" value="{{ old('list_size', $featuredList->list_size) }}" placeholder="Enter list size" required>
                                <div class="form-text">Enter how many items this list will contain.</div>
                                @error('list_size')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload / URL -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Image</label>
                                <div class="row">
                                    <!-- Current Image & URL -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Current Image</label>
                                            @if($featuredList->image)
                                                @php
                                                    $imgSrc = Str::startsWith($featuredList->image, ['http://', 'https://']) ? $featuredList->image : asset('storage/'.$featuredList->image);
                                                @endphp
                                                <div class="mb-2">
                                                    <img src="{{ $imgSrc }}" alt="{{ $featuredList->title }}" class="img-thumbnail" style="max-height: 100px;">
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
                                                <input type="url" name="image_url" id="image_url" class="form-control @error('image_url') is-invalid @enderror" value="{{ old('image_url', $featuredList->image) }}" placeholder="https://example.com/image.jpg">
                                            </div>
                                            @error('image_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Upload New Image -->
                                    <div class="col-md-6">
                                        <label class="form-label">Or Upload New Image</label>
                                        <div class="input-group">
                                            <input type="file" name="image_upload" id="image_upload" class="form-control @error('image_upload') is-invalid @enderror" accept="image/*">
                                        </div>
                                        @error('image_upload')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i> Leave empty to keep the current image
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div class="mt-3">
                                    <div class="image-preview rounded border p-3 text-center" id="imagePreview" style="{{ $featuredList->image ? '' : 'display:none;' }}">
                                        <img id="previewImage" class="img-fluid rounded" style="max-height: 200px;" src="{{ $featuredList->image ? $imgSrc : '' }}">
                                    </div>
                                </div>

                                <div class="form-text">Provide either an image URL or upload an image file.</div>
                            </div>

                            <!-- Status & Order -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Status *</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status', $featuredList->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="live" {{ old('status', $featuredList->status) === 'live' ? 'selected' : '' }}>Live</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Display Order</label>
                                <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $featuredList->display_order) }}" min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('admin.featured-lists.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageUrlInput = document.getElementById('image_url');
    const imageUploadInput = document.getElementById('image_upload');
    const imagePreviewDiv = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');

    imageUrlInput.addEventListener('input', function() {
        if(this.value.trim() !== '') {
            imageUploadInput.value = '';
            imageUploadInput.disabled = true;
            previewImage.src = this.value;
            imagePreviewDiv.style.display = 'block';
        } else {
            imageUploadInput.disabled = false;
            previewImage.src = '{{ $featuredList->image ? $imgSrc : "" }}';
            imagePreviewDiv.style.display = '{{ $featuredList->image ? "block" : "none" }}';
        }
    });

    imageUploadInput.addEventListener('change', function() {
        if(this.files.length > 0) {
            imageUrlInput.value = '';
            imageUrlInput.disabled = true;
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreviewDiv.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            imageUrlInput.disabled = false;
            previewImage.src = '{{ $featuredList->image ? $imgSrc : "" }}';
            imagePreviewDiv.style.display = '{{ $featuredList->image ? "block" : "none" }}';
        }
    });
});
</script>

@endsection