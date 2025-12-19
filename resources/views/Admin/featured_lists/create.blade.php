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
                    <form method="POST" action="{{ route('admin.featured-lists.store') }}" id="listForm">
                        @csrf

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">List Title *</label>
                                <input type="text"
                                       name="title"
                                       class="form-control form-control-lg @error('title') is-invalid @enderror"
                                       placeholder="e.g., Top 10 Web Development Tools"
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
                                    placeholder="Enter list size (e.g. 3, 5, 10)"
                                    value="{{ old('list_size', 3) }}"
                                    required>

                                <div class="form-text">
                                    Enter how many items this list will contain.
                                </div>

                                @error('list_size')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Status & Order -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status"
                                        class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                        <i class="fas fa-pencil-alt me-2"></i>Draft
                                    </option>
                                    <option value="live" {{ old('status') == 'live' ? 'selected' : '' }}>
                                        <i class="fas fa-broadcast-tower me-2"></i>Live
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Display Order</label>
                                <div class="input-group">
                                    {{-- <button class="btn btn-outline-secondary" type="button" id="decreaseOrder">
                                        <i class="fas fa-minus"></i>
                                    </button> --}}
                                    <input type="number"
                                           name="display_order"
                                           id="displayOrder"
                                           class="form-control text-center @error('display_order') is-invalid @enderror"
                                           value="{{ old('display_order', 0) }}"
                                           min="0">
                                    {{-- <button class="btn btn-outline-secondary" type="button" id="increaseOrder">
                                        <i class="fas fa-plus"></i>
                                    </button> --}}
                                </div>
                                <div class="form-text">Lower numbers appear first.</div>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                {{-- <button type="submit" name="action" value="save_and_add" class="btn btn-success">
                                    <i class="fas fa-plus-circle me-2"></i>Save & Add Items
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        {{-- <div class="col-lg-4">
            <!-- Preview Card -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Live Preview</h6>
                </div>
                <div class="card-body">
                    <div class="list-preview">
                        <div class="text-center py-4">
                            <i class="fas fa-list fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Preview will appear here</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-lightbulb me-2"></i>Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Clear Titles:</strong> Make titles descriptive
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Proper Categories:</strong> Helps with organization
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>List Size:</strong> Choose based on content depth
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Display Order:</strong> Plan your list hierarchy
                        </li>
                    </ul>
                </div>
            </div>
        </div> --}}
    </div>

</div>

<script>
    // Order input controls
    document.getElementById('increaseOrder').addEventListener('click', function() {
        const input = document.getElementById('displayOrder');
        input.value = parseInt(input.value) + 1;
    });

    document.getElementById('decreaseOrder').addEventListener('click', function() {
        const input = document.getElementById('displayOrder');
        if (input.value > 0) {
            input.value = parseInt(input.value) - 1;
        }
    });

    // Live preview update
    document.querySelectorAll('input[name="title"], select[name="list_size"]').forEach(element => {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    function updatePreview() {
        const title = document.querySelector('input[name="title"]').value || 'Your List Title';
        const size = document.querySelector('input[name="list_size"]:checked')?.value || '3';

        const preview = document.querySelector('.list-preview');
        preview.innerHTML = `
            <div class="border rounded p-3 bg-light">
                <h5 class="mb-3">${title}</h5>
                <div class="list-group">
                    ${Array.from({length: size}, (_, i) => `
                        <div class="list-group-item d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle me-3" style="width: 24px; height: 24px; line-height: 24px">${i + 1}</span>
                            <span class="text-muted">Item ${i + 1}</span>
                        </div>
                    `).join('')}
                </div>
                <div class="mt-3 text-end">
                    <small class="text-muted">Top ${size} list</small>
                </div>
            </div>
        `;
    }

    // Initial preview
    updatePreview();
</script>

@endsection
