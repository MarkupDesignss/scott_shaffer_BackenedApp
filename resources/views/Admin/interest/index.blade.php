@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Interest Management</h1>
            <p class="text-muted mb-0">Manage user interests and categories</p>
        </div>
        <a href="{{ route('admin.interest.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i>
            Add New Interest
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Interests</h6>
                            <h3 class="mb-0">{{ $interests->total() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-tags text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active</h6>
                            <h3 class="mb-0">{{ $interests->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Inactive</h6>
                            <h3 class="mb-0">{{ $interests->where('is_active', false)->count() }}</h3>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-pause-circle text-secondary" style="font-size: 1.5rem;"></i>
                        </div>
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
                            <th class="ps-4">#ID</th>
                            <th>Name</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interests as $interest)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $interest->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded me-3">
                                            @if($interest->icon)
                                                <i class="{{ $interest->icon }}" style="font-size: 1.2rem;"></i>
                                            @else
                                                <i class="bi bi-tag" style="font-size: 1.2rem;"></i>
                                            @endif
                                        </div>
                                        <span class="fw-medium">{{ $interest->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded">{{ $interest->icon ?? 'No icon' }}</code>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.interest.toggle-status', $interest) }}" class="toggle-status-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm status-btn {{ $interest->is_active ? 'btn-active' : 'btn-inactive' }}">
                                            <i class="bi {{ $interest->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }} me-1"></i>
                                            {{ $interest->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 pe-4">
                                        <a href="{{ route('admin.interest.edit', $interest) }}"
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.interest.destroy', $interest) }}"
                                              method="POST"
                                              class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger d-flex align-items-center"
                                                    onclick="return confirmDelete(event)">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                        <h5 class="mt-3 text-muted">No interests found</h5>
                                        <p class="text-muted">Get started by adding your first interest</p>
                                        <a href="{{ route('admin.interest.create') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-circle me-1"></i> Add Interest
                                        </a>
                                    </div>
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
            <nav aria-label="Page navigation">
                {{ $interests->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    @endif
</div>

<style>
    .card {
        border-radius: 10px;
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table td {
        padding: 1rem 0.5rem;
        vertical-align: middle;
    }

    .table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

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
        color: #065f46;
    }

    .btn-inactive:hover {
        background-color: #fecaca;
        color: #991b1b;
    }

    .btn-outline-primary, .btn-outline-danger {
        border-width: 1px;
    }

    .toggle-status-form {
        margin: 0;
    }

    .status-btn {
        min-width: 100px;
        transition: all 0.2s ease;
    }

    .delete-form {
        margin: 0;
    }

    .table-responsive {
        border-radius: 10px;
    }

    /* Custom alert styling */
    .alert {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
        function confirmDelete(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This interest will be permanently deleted. This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.closest('form').submit();
                    }
                });

                return false;
            }
    // Add animation to status toggle buttons
    document.addEventListener('DOMContentLoaded', function() {
        const statusButtons = document.querySelectorAll('.status-btn');

        statusButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Add a small animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Add fade-in effect to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });
</script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

@endsection
