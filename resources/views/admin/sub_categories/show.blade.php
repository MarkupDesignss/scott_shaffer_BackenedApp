@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="h3 mb-2">Sub-Category Details</h4>

            <div>
                <a href="{{ route('admin.sub-categories.edit', $subCategory->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>

                <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- Details Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">

                        <tbody>



                            <tr>
                                <th class="bg-light fw-bold">Category</th>
                                <td>{{ $subCategory->category?->name ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th class="bg-light fw-bold">Sub-Category Name</th>
                                <td>{{ $subCategory->name }}</td>
                            </tr>

                            <tr>
                                <th class="bg-light fw-bold">Slug</th>
                                <td>{{ $subCategory->slug }}</td>
                            </tr>

                            <tr>
                                <th class="bg-light fw-bold">Status</th>
                                <td>
                                    @if ($subCategory->status)
                                        <span class="badge bg-success px-3 py-2">Active</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">Inactive</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-light fw-bold">Created At</th>
                                <td>{{ $subCategory->created_at->format('d M Y, h:i A') }}</td>
                            </tr>

                            <tr>
                                <th class="bg-light fw-bold">Updated At</th>
                                <td>{{ $subCategory->updated_at->format('d M Y, h:i A') }}</td>
                            </tr>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
