@extends('layouts.admin')

@section('content')
<div class="container-fluid px-0">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                <div>
                    <strong class="d-block mb-1">Please fix the following errors:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
        {{-- @dd($items); --}}
    <div class="card border-0 shadow-sm mx-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="card-title mb-1 fw-semibold text-dark">Featured List Items</h5>
                    <p class="text-muted mb-0">Manage your featured catalog items</p>
                </div>
                <a href="{{ route('admin.featured-list-items.create') }}"
                   class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Add New Item
                </a>
            </div>

            <div class="table-responsive rounded border">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-semibold small" style="width: 80px">
                                S.No.
                            </th>
                            <th class="py-3 text-uppercase text-muted fw-semibold small">Item Name</th>
                            <th class="py-3 text-uppercase text-muted fw-semibold small">List Name</th>
                            <th class="py-3 text-uppercase text-muted fw-semibold small" style="width: 120px">Position</th>
                            <th class="pe-4 py-3 text-uppercase text-muted fw-semibold small" style="width: 150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="align-middle">
                               <td class="ps-4 fw-medium text-muted">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="fw-medium">
                                    @if($item->catalogItem)
                                        <span class="text-dark">{{ $item->catalogItem->name }}</span>
                                    @else
                                        <span class="text-danger fst-italic">Item not found</span>
                                    @endif
                                </td>
                                <td class="fw-medium">
                                    @if($item->featuredList)
                                        <span class="text-dark">{{ $item->featuredList->title }}</span>
                                    @else
                                        <span class="text-danger fst-italic">List not found</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2">{{ $item->position }}</span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.featured-list-items.edit', $item) }}"
                                           class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 px-3">
                                            <i class="fas fa-edit fa-sm"></i>
                                            Edit
                                        </a>

                                        <!-- <form method="POST"
                                              action="{{ route('admin.featured-list-items.destroy', $item) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this featured item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 px-3">
                                                <i class="fas fa-trash fa-sm"></i>
                                                Delete
                                            </button>
                                        </form> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted mb-2">No featured items found</h6>
                                        <p class="text-muted small">Start by adding your first featured item</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

