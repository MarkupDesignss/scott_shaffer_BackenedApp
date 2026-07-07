@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Sub-Category Details</h4>

            <div>
                <a href="{{ route('admin.sub-categories.edit', $subCategory->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>

                <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-sm btn-secondary">
                    Back
                </a>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">ID</div>
                    <div class="col-md-9">{{ $subCategory->id }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">Category</div>
                    <div class="col-md-9">
                        {{ $subCategory->category?->name ?? '-' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">Sub-Category Name</div>
                    <div class="col-md-9">{{ $subCategory->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">Slug</div>
                    <div class="col-md-9 text-muted">{{ $subCategory->slug }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">Status</div>
                    <div class="col-md-9">
                        @if ($subCategory->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-3 fw-semibold">Created At</div>
                    <div class="col-md-9">
                        {{ $subCategory->created_at->format('d M Y, h:i A') }}
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
