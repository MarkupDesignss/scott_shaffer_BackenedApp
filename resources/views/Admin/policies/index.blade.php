@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-3">
        <div>
            <h1 class="h3 fw-bold text-gray-800">Policies Management</h1>
            <p class="text-muted mb-0">Manage your website policies and terms</p>
        </div>
        <a href="{{ route('admin.policies.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Create New Policy
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Policies</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $policies->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-contract fa-2x text-gray-300"></i>
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
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Active Policies</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $policies->where('is_active', true)->count() }}
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

    <!-- Policies Table -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">All Policies</h6>
                <div class="input-group" style="width: 300px;">
                    <span class="input-group-text bg-transparent">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0"
                           id="policySearch" placeholder="Search policies...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="policiesTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Policy Name</th>
                            <th width="100">Version</th>
                            <th width="100">Status</th>
                            <th width="100">Order</th>
                            <th width="120">Last Updated</th>
                            <th width="150" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policies as $policy)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-file-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $policy->name }}</h6>
                                        <small class="text-muted">
                                            {{ Str::limit($policy->description, 50) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                    v{{ $policy->version ?? '1.0' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $policy->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $policy->order }}</span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $policy->updated_at->format('M d, Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.policies.edit', $policy) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="{{ route('admin.policies.show', $policy) }}"
                                       class="btn btn-sm btn-outline-info"
                                       data-bs-toggle="tooltip"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('admin.policies.destroy', $policy) }}"
                                          method="POST"
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                                    <h5>No Policies Found</h5>
                                    <p class="text-muted">Create your first policy to get started</p>
                                    <a href="{{ route('admin.policies.create') }}"
                                       class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create Policy
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
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this policy? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.empty-state {
    padding: 3rem 1rem;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.04);
}
.badge {
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    const searchInput = document.getElementById('policySearch');
    const tableRows = document.querySelectorAll('#policiesTable tbody tr');

    searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    let currentForm = null;

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            currentForm = this;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (currentForm) {
            currentForm.submit();
        }
    });
});
</script>
