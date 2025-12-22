@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.policies.index') }}">Policies</a></li>
                    <li class="breadcrumb-item active">
                        {{ isset($policy) ? 'Edit Policy' : 'Create Policy' }}
                    </li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-gray-800 mb-0">
                {{ isset($policy) ? 'Edit Policy' : 'Create New Policy' }}
            </h1>
        </div>
        <a href="{{ route('admin.policies.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Policies
        </a>
    </div>

    <!-- Main Form -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas {{ isset($policy) ? 'fa-edit' : 'fa-plus-circle' }} text-primary me-2"></i>
                        Policy Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ isset($policy) ? route('admin.policies.update', $policy) : route('admin.policies.store') }}"
                          method="POST"
                          id="policyForm">
                        @csrf
                        @if(isset($policy))
                            @method('PUT')
                        @endif

                        <!-- Policy Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Policy Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name', $policy->name ?? '') }}"
                                   placeholder="e.g., Terms of Service, Privacy Policy"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug (Auto-generated) -->
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">URL Slug</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    /policy/
                                </span>
                                <input type="text"
                                       name="slug"
                                       id="slug"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       value="{{ old('slug', $policy->slug ?? '') }}"
                                       placeholder="terms-of-service">
                            </div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Policy Content <span class="text-danger">*</span>
                            </label>
                            <textarea name="description"
                                      id="description"
                                      class="form-control ckeditor @error('description') is-invalid @enderror"
                                      rows="15">{{ old('description', $policy->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Settings Cards -->
                        <div class="row mb-4">
                            <!-- Version & Order -->
                            <div class="col-md-6">
                                <div class="card h-100 border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="version" class="form-label">Version</label>
                                                <input type="text"
                                                       name="version"
                                                       id="version"
                                                       class="form-control"
                                                       value="{{ old('version', $policy->version ?? '1.0') }}"
                                                       placeholder="1.0">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="order" class="form-label">Display Order</label>
                                                <input type="number"
                                                       name="order"
                                                       id="order"
                                                       class="form-control"
                                                       value="{{ old('order', $policy->order ?? 0) }}"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status & Visibility -->
                            <div class="col-md-6">
                                <div class="card h-100 border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-eye me-2"></i>Visibility</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="is_active"
                                                   id="is_active"
                                                   value="1"
                                                   {{ old('is_active', $policy->is_active ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_active">
                                                Active Policy
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div>
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="window.history.back()">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit"
                                        name="action"
                                        value="publish"
                                        class="btn btn-primary">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ isset($policy) ? 'Update Policy' : 'Publish Policy' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

=<style>
    /* .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    } */
    /* .card-title {
        color: white;
    } */
    .form-control-lg {
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
    }
.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        nameInput.addEventListener('blur', function() {
            if (!slugInput.value) {
                const slug = this.value
                .toLowerCase()
                .replace(/[^\w\s]/gi, '')
                .replace(/\s+/g, '-');
                slugInput.value = slug;
            }
        });

        // Initialize CKEditor
        ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'blockQuote', '|',
                    'undo', 'redo'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                ]
            }
        })
        .catch(error => {
            console.error(error);
        });

        // Form submission confirmation
        document.getElementById('policyForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const content = document.getElementById('description').value.trim();

            if (!name || !content) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
        });
    });
</script>

@endsection
