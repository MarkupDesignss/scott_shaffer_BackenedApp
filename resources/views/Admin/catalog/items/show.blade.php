@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-2">Catalog Item Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.catalog-items.index') }}">Catalog Items</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $item->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            {{-- <a href="{{ route('admin.catalog-items.edit', $item->id) }}"
            class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
            </a> --}}
            <a href="{{ route('admin.catalog-items.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-box me-2"></i>Item Information
                        </h5>
                        <span class="badge rounded-pill bg-{{ $item->status === 'active' ? 'success' : 'secondary' }}-soft
                                  text-{{ $item->status === 'active' ? 'success' : 'secondary' }} px-3 py-2">
                            <i class="fas fa-circle me-1" style="font-size: 8px"></i>
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-tag me-1"></i>Item Name
                                </h6>
                                <h5 class="fw-semibold mb-0">{{ $item->name }}</h5>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-folder me-1"></i>Category
                                </h6>
                                @if($item->category)
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light-primary text-primary rounded-pill px-3 py-2 me-2">
                                        <i class="fas fa-folder me-1"></i>
                                    </span>
                                    <span class="fw-semibold">{{ $item->category->name }}</span>
                                </div>
                                @else
                                <span class="text-muted fst-italic">Uncategorized</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-align-left me-1"></i>Description
                        </h6>
                        <div class="description-box p-3 rounded bg-light">
                            @if($item->description)
                            <p class="mb-0">{{ $item->description }}</p>
                            @else
                            <p class="text-muted fst-italic mb-0">No description provided</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Image Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-image me-2"></i>Image Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        @if($item->image_url)
                        @php
                        $imgSrc = Str::startsWith($item->image_url, ['http://', 'https://'])
                        ? $item->image_url
                        : asset('storage/' . $item->image_url);
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $item->name }}" class="img-fluid rounded shadow-sm"
                            style="max-height: 300px; object-fit: contain;">
                        @else
                        <div class="empty-image d-flex flex-column align-items-center justify-content-center p-5">
                            <div class="empty-image-icon rounded-circle bg-light p-4 mb-3">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                            <h6 class="text-muted mb-0">No Image Available</h6>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Meta Information Card -->
            <div class="card shadow-sm border-0">
                {{-- <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-info-circle me-2"></i>Meta Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="detail-item mb-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-plus-circle me-1"></i>Created
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="meta-icon bg-light-primary rounded p-2 me-3">
                                <i class="fas fa-calendar-plus text-primary"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">{{ $item->created_at->format('M d, Y') }}</p>
                <small class="text-muted">{{ $item->created_at->format('h:i A') }}</small>
            </div>
        </div>
    </div>

    <div class="detail-item">
        <h6 class="text-muted mb-2">
            <i class="fas fa-sync-alt me-1"></i>Last Updated
        </h6>
        <div class="d-flex align-items-center">
            <div class="meta-icon bg-light-warning rounded p-2 me-3">
                <i class="fas fa-calendar-alt text-warning"></i>
            </div>
            <div>
                <p class="mb-0 fw-semibold">{{ $item->updated_at->format('M d, Y') }}</p>
                <small class="text-muted">{{ $item->updated_at->format('h:i A') }}</small>
            </div>
        </div>
    </div>
</div> --}}
{{-- <div class="card-footer bg-white border-top py-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.catalog-items.edit', $item->id) }}"
class="btn btn-warning">
<i class="fas fa-edit me-2"></i>Edit Item
</a>
<form action="{{ route('admin.catalog-items.destroy', $item->id) }}" method="POST"
    onsubmit="return confirm('Are you sure you want to delete this item?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger w-100">
        <i class="fas fa-trash-alt me-2"></i>Delete Item
    </button>
</form>
</div>
</div> --}}
</div>
</div>
</div>
</div>
@endsection

@push('styles')
<style>
.detail-item {
    padding: 0.5rem 0;
}

.description-box {
    min-height: 100px;
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
}

.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-secondary-soft {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.meta-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-image {
    height: 200px;
}

.empty-image-icon {
    width: 80px;
    height: 80px;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 0;
}

.breadcrumb-item a {
    text-decoration: none;
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}

.breadcrumb-item.active {
    color: #495057;
    font-weight: 500;
}
</style>
@endpush