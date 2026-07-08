@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-2">Catalog Item Details</h2>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.catalog-items.index') }}">Catalog Items</a>
                </li>
                <li class="breadcrumb-item active">{{ $item->name }}</li>
            </ol>
        </div>

        <a href="{{ route('admin.catalog-items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="row">

        {{-- MAIN --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white border-bottom d-flex justify-content-between">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-box me-2"></i>Item Information
                    </h5>

                    <span class="badge rounded-pill
                        bg-{{ $item->status ? 'success' : 'secondary' }}-soft
                        text-{{ $item->status ? 'success' : 'secondary' }} px-3 py-2">
                        {{ $item->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- Item Name --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted">Item Name</h6>
                            <h5>{{ $item->name }}</h5>
                        </div>

                        {{-- Category --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted">Category</h6>
                            @if($item->subCategory && $item->subCategory->category)
                                <span class="badge bg-light-primary text-primary px-3 py-2">
                                    {{ $item->subCategory->category->name }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">Not assigned</span>
                            @endif
                        </div>

                        {{-- Sub Category --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted">Sub Category</h6>
                            @if($item->subCategory)
                                <span class="text-dark">
                                    {{ $item->subCategory->name }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">Not assigned</span>
                            @endif
                        </div>

                    </div>

                    {{-- Description --}}
                    <div class="mt-3">
                        <h6 class="text-muted">Description</h6>
                        <div class="p-3 bg-light rounded">
                            {{ $item->description ?? 'No description provided' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-image me-2"></i>Image
                    </h5>
                </div>

                <div class="card-body text-center">
                    @if($item->image_url)
                        @php
                            $imgSrc = Str::startsWith($item->image_url, ['http','https'])
                                ? $item->image_url
                                : asset('storage/'.$item->image_url);
                        @endphp

                        <img src="{{ $imgSrc }}"
                             class="img-fluid rounded"
                             style="max-height:300px;object-fit:contain;">
                    @else
                        <p class="text-muted">No image available</p>
                    @endif
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
