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
                    <li class="breadcrumb-item"><a href="{{ route('admin.featured-lists.show', $featuredList) }}">{{ Str::limit($featuredList->title, 20) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.featured-lists.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            @if($featuredList->status === 'live')
                <span class="btn btn-success disabled">
                    <i class="fas fa-broadcast-tower me-2"></i>Live
                </span>
            @endif
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
                    <form method="POST" action="{{ route('admin.featured-lists.update', $featuredList) }}" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">List Title *</label>
                                <input type="text"
                                       name="title"
                                       class="form-control form-control-lg @error('title') is-invalid @enderror"
                                       value="{{ old('title', $featuredList->title) }}"
                                       required>
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
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $featuredList->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">List Size *</label>

                                <input type="number"
                                    name="list_size"
                                    class="form-control text-center"
                                    min="1"
                                    max="100"
                                    value="{{ old('list_size', $featuredList->list_size) }}"
                                    placeholder="Enter list size (e.g. 3, 5, 10)"
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
                                <label class="form-label fw-semibold">Status *</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio"
                                           class="btn-check"
                                           name="status"
                                           id="statusDraft"
                                           value="draft"
                                           {{ old('status', $featuredList->status) === 'draft' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary" for="statusDraft">
                                        <i class="fas fa-pencil-alt me-2"></i>Draft
                                    </label>

                                    <input type="radio"
                                           class="btn-check"
                                           name="status"
                                           id="statusLive"
                                           value="live"
                                           {{ old('status', $featuredList->status) === 'live' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success" for="statusLive">
                                        <i class="fas fa-broadcast-tower me-2"></i>Live
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Display Order</label>
                                <div class="input-group">
                                    {{-- <button class="btn btn-outline-secondary" type="button" onclick="changeOrder(-1)">
                                        <i class="fas fa-minus"></i>
                                    </button> --}}
                                    <input type="number"
                                           name="display_order"
                                           class="form-control text-center @error('display_order') is-invalid @enderror"
                                           value="{{ old('display_order', $featuredList->display_order) }}"
                                           min="0"
                                           id="orderInput">
                                    {{-- <button class="btn btn-outline-secondary" type="button" onclick="changeOrder(1)">
                                        <i class="fas fa-plus"></i>
                                    </button> --}}
                                </div>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div>
                                <a href="{{ route('admin.featured-lists.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                {{-- <button type="button" class="btn btn-success" onclick="saveAndView()">
                                    <i class="fas fa-eye me-2"></i>Save & View
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone Card -->
            {{-- <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold">Delete This List</h6>
                            <p class="text-muted mb-0">Once deleted, this list cannot be recovered.</p>
                        </div>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i>Delete List
                        </button>
                    </div>
                </div>
            </div> --}}
        </div>

        <!-- Sidebar -->
        {{-- <div class="col-lg-4">
            <!-- List Stats Card -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>List Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Created</span>
                            <span class="fw-semibold">{{ $featuredList->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Last Updated</span>
                            <span class="fw-semibold">{{ $featuredList->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Items in List</span>
                            <span class="badge bg-primary">{{ $featuredList->items_count ?? 0 }} / {{ $featuredList->list_size }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary text-start">
                            <i class="fas fa-plus-circle me-2"></i>Add Items to List
                        </a>
                        <a href="#" class="btn btn-outline-success text-start">
                            <i class="fas fa-eye me-2"></i>Preview List
                        </a>
                        <a href="#" class="btn btn-outline-info text-start">
                            <i class="fas fa-copy me-2"></i>Duplicate List
                        </a>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this featured list?</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone. All items in this list will also be removed.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.featured-lists.destroy', $featuredList) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete List</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function changeOrder(value) {
        const input = document.getElementById('orderInput');
        const newValue = parseInt(input.value) + value;
        if (newValue >= 0) {
            input.value = newValue;
        }
    }

    function saveAndView() {
        document.getElementById('editForm').submit();
        // The redirect would be handled in the controller
    }

    // Auto-save draft status
    const statusRadios = document.querySelectorAll('input[name="status"]');
    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'draft') {
                // Optional: Show draft saved notification
            }
        });
    });
</script>

@endsection
