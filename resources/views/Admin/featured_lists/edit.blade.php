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
                                <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title', $featuredList->title) }}">
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
                                <input type="number" name="list_size" class="form-control text-start" min="1" max="100" value="{{ old('list_size', $featuredList->list_size) }}" placeholder="Enter list size" required>
                                <div class="form-text">Enter how many items this list will contain.</div>
                                @error('list_size')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">Image</label>

                                {{-- Existing Image --}}
                                @if($featuredList->image)
                                    <div class="mb-3">
                                        <img
                                            src="{{ asset('storage/' .$featuredList->image) }}"
                                            class="img-thumbnail"
                                            style="max-height: 200px;"
                                            alt="Featured Image">
                                    </div>
                                @endif

                                {{-- Upload New Image --}}
                                <input type="file"
                                    name="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    accept="image/*">

                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Preview --}}
                                <div class="mt-3 d-none" id="imagePreview">
                                    <img id="previewImage" class="img-fluid rounded" style="max-height:200px;">
                                </div>

                                <div class="form-text">
                                    Leave empty to keep existing image.
                                </div>
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
