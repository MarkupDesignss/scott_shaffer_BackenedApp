@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Featured Lists</h1>
            <p class="text-muted mb-0">Manage your featured content lists</p>
        </div>
        <a href="{{ route('admin.featured-lists.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i>
            Create New List
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Lists</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lists->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Lists</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $lists->where('status', 'live')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">All Featured Lists</h6>
                {{-- <div class="d-flex gap-2">
                    <select class="form-select form-select-sm w-auto">
                        <option>All Status</option>
                        <option>Live</option>
                        <option>Draft</option>
                    </select>
                    <input type="text" class="form-control form-control-sm" placeholder="Search lists...">
                </div> --}}
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">ID</th>
                            <th>List Title</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th width="140" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lists as $list)
                            <tr>
                                <td class="fw-semibold text-muted">#{{ $list->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-light rounded p-2">
                                                <i class="fas fa-list text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $list->title }}</h6>
                                            <small class="text-muted">Created {{ $list->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $list->category->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                        Top {{ $list->list_size }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $list->status === 'live' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-1">
                                        <i class="fas fa-circle me-1" style="font-size: 8px"></i>
                                        {{ ucfirst($list->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        {{-- <button class="btn btn-sm btn-outline-secondary border-0 order-down" data-id="{{ $list->id }}">
                                            <i class="fas fa-chevron-down"></i>
                                        </button> --}}
                                        <span class="fw-bold">{{ $list->display_order }}</span>
                                        {{-- <button class="btn btn-sm btn-outline-secondary border-0 order-up" data-id="{{ $list->id }}">
                                            <i class="fas fa-chevron-up"></i>
                                        </button> --}}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.featured-lists.edit', $list) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- <a href="#" class="btn btn-sm btn-outline-success" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </a> --}}
                                        <form method="POST"
                                              action="{{ route('admin.featured-lists.destroy', $list) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-title="{{ $list->title }}"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-5">
                                        <i class="fas fa-list fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No featured lists found</h5>
                                        <p class="text-muted mb-4">Get started by creating your first featured list</p>
                                        <a href="{{ route('admin.featured-lists.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create List
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- @if($lists->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $lists->links() }}
            </div>
        @endif --}}
    </div>

</div>
{{-- SweetAlert2 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
{{-- SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const title = this.dataset.title;

            Swal.fire({
                title: 'Delete Featured List?',
                html: `Are you sure you want to delete <strong>"${title}"</strong>?<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@endsection
