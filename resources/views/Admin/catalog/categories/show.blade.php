@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-2">Category Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.catalog-categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.catalog-categories.edit', $category->id) }}"
               class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.catalog-categories.index') }}"
               class="btn btn-outline-secondary">
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
                            <i class="fas fa-folder me-2"></i>Category Information
                        </h5>
                        <span class="badge rounded-pill bg-{{ $category->status === 'active' ? 'success' : 'secondary' }}-soft
                                  text-{{ $category->status === 'active' ? 'success' : 'secondary' }} px-3 py-2">
                            <i class="fas fa-circle me-1" style="font-size: 8px"></i>
                            {{ ucfirst($category->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-tag me-1"></i>Name
                                </h6>
                                <h4 class="fw-semibold mb-0">
                                    <i class="fas fa-folder text-primary me-2"></i>{{ $category->name }}
                                </h4>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-link me-1"></i>Slug
                                </h6>
                                <div class="slug-box p-2 rounded bg-light">
                                    <code class="text-primary">{{ $category->slug }}</code>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Icon & Color Display -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-icons me-1"></i>Icon
                                </h6>
                                <div class="d-flex align-items-center">
                                    @if($category->icon)
                                        <div class="icon-display bg-light-primary rounded p-3 me-3">
                                            <i class="{{ $category->icon }} fa-lg text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold">{{ $category->icon }}</p>
                                            <small class="text-muted">Font Awesome Class</small>
                                        </div>
                                    @else
                                        <div class="icon-display bg-light-secondary rounded p-3 me-3">
                                            <i class="fas fa-icons fa-lg text-muted"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted fst-italic">No icon set</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="detail-item">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-palette me-1"></i>Color
                                </h6>
                                <div class="d-flex align-items-center">
                                    @if($category->color)
                                        <div class="color-display me-3"
                                             style="width: 40px; height: 40px; background: {{ $category->color }};
                                                    border-radius: 8px; border: 2px solid #dee2e6;">
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold">{{ $category->color }}</p>
                                            <small class="text-muted">Hex Color Code</small>
                                        </div>
                                    @else
                                        <div class="color-display bg-light-secondary rounded me-3"
                                             style="width: 40px; height: 40px; display: flex;
                                                    align-items: center; justify-content: center;">
                                            <i class="fas fa-palette text-muted"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-muted fst-italic">No color set</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Items (Optional) -->

            @if($category->items && $category->items->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-boxes me-2"></i>Items in this Category
                            <span class="badge bg-primary ms-2">{{ $category->items->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($category->items->take(6) as $item)
                                <div class="col-md-4 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <h6 class="fw-semibold mb-2">{{ $item->name }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-{{ $item->status == 'active' ? 'success' : 'secondary' }}-soft
                                                      text-{{ $item->status == 'active' ? 'success' : 'secondary' }} rounded-pill px-2 py-1">
                                                    <small>{{ ucfirst($item->status) }}</small>
                                                </span>
                                                <a href="{{ route('admin.catalog-items.show', $item->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($category->items->count() > 6)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.catalog-items.index') }}?category={{ $category->id }}"
                                   class="btn btn-outline-primary btn-sm">
                                    View All {{ $category->items->count() }} Items
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Preview Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-eye me-2"></i>Category Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="preview-card text-center p-4 rounded">
                        <div class="preview-icon mb-3">
                            @if($category->icon)
                                <i class="{{ $category->icon }} fa-3x"
                                   style="color: {{ $category->color ?? '#0d6efd' }}"></i>
                            @else
                                <i class="fas fa-folder fa-3x text-primary"></i>
                            @endif
                        </div>
                        <h4 class="fw-semibold mb-2">{{ $category->name }}</h4>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge rounded-pill bg-{{ $category->status === 'active' ? 'success' : 'secondary' }}-soft
                                      text-{{ $category->status === 'active' ? 'success' : 'secondary' }} px-3 py-2">
                                <i class="fas fa-circle me-1" style="font-size: 8px"></i>
                                {{ ucfirst($category->status) }}
                            </span>
                            @if($category->items)
                                <span class="badge rounded-pill bg-light-primary text-primary px-3 py-2">
                                    <i class="fas fa-box me-1"></i>{{ $category->items->count() }} items
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Information Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
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
                                <p class="mb-0 fw-semibold">{{ $category->created_at->format('M d, Y') }}</p>
                                <small class="text-muted">{{ $category->created_at->format('h:i A') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="detail-item mb-3">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-sync-alt me-1"></i>Last Updated
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="meta-icon bg-light-warning rounded p-2 me-3">
                                <i class="fas fa-calendar-alt text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">{{ $category->updated_at->format('M d, Y') }}</p>
                                <small class="text-muted">{{ $category->updated_at->format('h:i A') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-hashtag me-1"></i>Unique ID
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="meta-icon bg-light-info rounded p-2 me-3">
                                <i class="fas fa-fingerprint text-info"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">{{ $category->id }}</p>
                                <small class="text-muted">Category Identifier</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.catalog-categories.edit', $category->id) }}"
                           class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Category
                        </a>
                        <form action="{{ route('admin.catalog-categories.destroy', $category->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this category? This will affect all items in this category.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash-alt me-2"></i>Delete Category
                            </button>
                        </form>
                    </div>
                </div>
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

.slug-box {
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
    word-break: break-all;
}

.preview-card {
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
}

.icon-display {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.color-display {
    transition: transform 0.2s;
}

.color-display:hover {
    transform: scale(1.1);
}

.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-light-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-light-secondary {
    background-color: rgba(108, 117, 125, 0.1) !important;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color display hover effect
    const colorDisplays = document.querySelectorAll('.color-display');
    colorDisplays.forEach(display => {
        display.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        });
        display.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
});
</script>
@endpush
