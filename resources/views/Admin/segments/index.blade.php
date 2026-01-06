@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header with Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-1">Audience Segments</h1>
                    <p class="text-muted mb-0">Manage and organize your customer segments</p>
                </div>
                <a href="{{ route('admin.segments.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Create Segment
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Segments Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">All Segments</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 border-0">
                                <span class="fw-semibold">Segment Name</span>
                            </th>
                            <th class="py-3 border-0">
                                <span class="fw-semibold">Estimate Users</span>
                            </th>
                            <th class="py-3 border-0">
                                <span class="fw-semibold">Status</span>
                            </th>
                            <th class="py-3 border-0">
                                <span class="fw-semibold">Created</span>
                            </th>
                            <th class="pe-4 py-3 border-0 text-center">
                                <span class="fw-semibold">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($segments as $segment)
                            <tr class="border-top">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="segment-avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">{{ $segment->name }}</h6>
                                            <small class="text-muted">
                                                @php
                                                    $filterCount = count(array_filter($segment->filters ?? []));
                                                @endphp
                                                {{ $filterCount }} filter{{ $filterCount !== 1 ? 's' : '' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user me-2 text-muted"></i>
                                        <span class="fw-medium">{{ number_format($segment->users_count ?? 0) }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <form method="POST"
                                        action="{{ route('admin.segments.toggle-status', $segment) }}"
                                        class="toggle-status-form">
                                        @csrf

                                        <button type="submit"
                                                class="btn btn-sm status-btn {{ $segment->status === 'active' ? 'btn-active' : 'btn-inactive' }}">
                                            <i class="bi {{ $segment->status === 'active' ? 'bi-toggle-on' : 'bi-toggle-off' }} me-1"></i>
                                            {{ ucfirst($segment->status) }}
                                        </button>
                                    </form>
                                </td>

                                <td class="py-3">
                                    <span class="text-muted">{{ $segment->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="pe-4 py-3 text-center">
                                    <div class="btn-group" role="group">
                                        {{-- <a href="{{ route('admin.segments.edit', $segment) }}"
                                           class="btn btn-sm btn-outline-primary rounded-start-2">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a> --}}


                                    <a href="{{ route('admin.segments.edit', $segment) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Edit Segment">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!--<form action="{{ route('admin.segments.destroy', $segment) }}"-->
                                    <!--          method="POST" class="d-inline delete-form">-->
                                    <!--    @csrf-->
                                    <!--    @method('DELETE')-->
                                    <!--    <button type="submit"-->
                                    <!--        class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-btn"-->
                                    <!--        data-bs-toggle="tooltip"-->
                                    <!--        data-bs-placement="top"-->
                                    <!--        data-bs-container="body"-->
                                    <!--        data-bs-offset="0,8"-->
                                    <!--        title="Delete Segment"-->
                                    <!--        data-segment-name="{{ $segment->name }}">-->
                                    <!--        <i class="fas fa-trash-alt"></i>-->
                                    <!--    </button>-->
                                    <!--</form>-->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <h5 class="mb-2">No segments created yet</h5>
                                        <p class="mb-0">Get started by creating your first audience segment</p>
                                        <a href="{{ route('admin.segments.create') }}" class="btn btn-primary mt-3">
                                            <i class="fas fa-plus me-1"></i>Create First Segment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($segments->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $segments->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .btn-active {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.btn-inactive {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.btn-active:hover {
    background-color: #a7f3d0;
}

.btn-inactive:hover {
    background-color: #fecaca;
}

.status-btn {
    min-width: 100px;
    border-radius: 50px;
    transition: all 0.2s ease;
}

.toggle-status-form {
    margin: 0;
}

</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const segmentName = this.getAttribute('data-segment-name');
        const form = this.closest('.delete-form');

        Swal.fire({
            title: 'Do you want to delete?',
            html: `You are about to delete the segment <strong>${segmentName}</strong>.<br><small class="text-danger">This action cannot be undone.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'No, Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

new bootstrap.Tooltip(tooltipTriggerEl, {
    container: 'body',
    offset: [0, 8]
});
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

@endsection
