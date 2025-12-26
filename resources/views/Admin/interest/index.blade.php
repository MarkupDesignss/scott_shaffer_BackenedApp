@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="font-size:1.5rem;font-weight:800">
                Interest Management
            </h1>
            <p class="text-muted mb-0">Manage user interests and categories</p>
        </div>
        <a href="{{ route('admin.interest.create') }}" class="btn btn-primary">
            + Add New Interest
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Total Interests</h6>
                        <h3 class="mb-0">{{ $interests->total() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded fw-bold">
                        #
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Active</h6>
                        <h3 class="mb-0">{{ $interests->where('is_active', true)->count() }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded fw-bold">
                        ON
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Inactive</h6>
                        <h3 class="mb-0">{{ $interests->where('is_active', false)->count() }}</h3>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-3 rounded fw-bold">
                        OFF
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interests Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Name</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interests as $interest)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $loop->iteration }}</td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded me-3">
                                            @if($interest->icon)
                                                <img src="{{ asset('storage/'.$interest->icon) }}"
                                                     width="40" height="40">
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </div>
                                        <span class="fw-medium">{{ $interest->name }}</span>
                                    </div>
                                </td>

                                <td>
                                    @if($interest->icon)
                                        <img src="{{ asset('storage/'.$interest->icon) }}"
                                             width="45" height="45">
                                    @else
                                        <span class="text-muted">No icon</span>
                                    @endif
                                </td>

                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.interest.toggle-status', $interest) }}">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm status-btn {{ $interest->is_active ? 'btn-active' : 'btn-inactive' }}">
                                            {{ $interest->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-2 pe-4">
                                        <a href="{{ route('admin.interest.edit', $interest) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>

                                        <!-- <form action="{{ route('admin.interest.destroy', $interest) }}"
                                              method="POST"
                                              class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirmDelete(event)">
                                                Delete
                                            </button>
                                        </form> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <h5 class="text-muted">No interests found</h5>
                                    <a href="{{ route('admin.interest.create') }}"
                                       class="btn btn-primary mt-2">
                                        + Add Interest
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($interests->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $interests->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: 'This interest will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.closest('form').submit();
        }
    });
}
</script>
@endsection