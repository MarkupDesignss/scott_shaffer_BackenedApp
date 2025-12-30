@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create Featured List</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.featured-lists.index') }}">Featured Lists</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.featured-lists.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Lists
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Main Form Card -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">List Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.featured-lists.store') }}" id="listForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">List Title *</label>
                                <input type="text"
                                       name="title"
                                       class="form-control form-control-lg @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}"
                                       required
                                       autofocus>
                                <div class="form-text">Give your list a clear, descriptive title.</div>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Category *</label>
                                <select name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                <input type="number"
                                    name="list_size"
                                    class="form-control text-center"
                                    min="1"
                                    max="100"
                                    value="{{ old('list_size', 3) }}"
                                    required>
                                <div class="form-text">Enter how many items this list will contain.</div>
                                @error('list_size')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="live" {{ old('status') == 'live' ? 'selected' : '' }}>Live</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Display Order -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Display Order</label>
                                <input type="number"
                                       name="display_order"
                                       id="displayOrder"
                                       class="form-control text-center @error('display_order') is-invalid @enderror"
                                       value="{{ old('display_order', 0) }}"
                                       min="0">
                                <div class="form-text">Lower numbers appear first.</div>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="col-md-12 mb-4">
                                <div class="card border mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-semibold">Media</h6>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">Upload Image *</label>
                                        <input type="file"
                                            class="form-control @error('image') is-invalid @enderror"
                                            name="image"
                                            accept="image/*"
                                            required>

                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-3">
                                            <div class="image-preview rounded border p-3 text-center d-none" id="imagePreview">
                                                <img id="previewImage" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div>
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('admin.featured-lists.index') }}'">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" name="action" value="save" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save List
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.image-preview { background-color: #f8f9fa; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('input[name="image"]');
    const previewBox = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImage');

    input.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewBox.classList.remove('d-none');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>

@endsection
