@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-2" style="font-size: 1.5rem;font-weight:800">Catalog Categories</h2>
            <p class="text-muted mb-0">Manage your product categories and organization</p>
        </div>
        <a href="{{ route('admin.catalog-categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add New Category
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="mb-0 fw-semibold">All Categories</h6>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="categorySearch" class="form-control border-start-0"
                               placeholder="Search categories...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="categoriesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="60">#</th>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th>Intrest</th>
                            <th>Status</th>
                            <th class="text-center" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="align-middle">
                                <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">                  
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $category->name }}</h6>
                                            @if($category->parent_id)
                                                <small class="text-muted">
                                                    <i class="fas fa-level-up-alt me-1"></i>Subcategory
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-muted">{{ $category->slug }}</code>
                                </td>
                                <td>
                                    @if($category->interest)
                                        <span class="fw-semibold text-dark">
                                            {{ $category->interest->name }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">N/A</span>
                                    @endif
                                </td>
                               <td>
                                    <form method="POST"
                                        action="{{ route('admin.catalog-categories.toggle-status', $category->id) }}"
                                        class="toggle-status-form d-inline">
                                        @csrf
                                        @method('PATCH')

                                        @php
                                            $isActive = $category->status === 'active' || $category->status == 1 || $category->status === true;
                                        @endphp

                                        <button type="submit"
                                                class="btn btn-sm status-btn {{ $isActive ? 'btn-active' : 'btn-inactive' }}">
                                            <i class="bi {{ $isActive ? 'bi-toggle-on' : 'bi-toggle-off' }} me-1"></i>
                                            {{ $isActive ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.catalog-categories.edit', $category->id) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                           data-bs-toggle="tooltip" title="Edit Category">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="{{ route('admin.catalog-categories.show', $category->id) }}"
                                           class="btn btn-sm btn-outline-info rounded-pill px-3"
                                           data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>


                                        <form action="{{ route('admin.catalog-categories.destroy', $category->id) }}"
                                              method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Category">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon bg-light-primary rounded-circle p-4 mb-3">
                                            <i class="fas fa-folder-open fa-2x text-primary"></i>
                                        </div>
                                        <h5 class="text-muted">No categories found</h5>
                                        <p class="text-muted mb-4">Get started by creating your first category</p>
                                        <a href="{{ route('admin.catalog-categories.create') }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($categories->count())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }}
                        of {{ $categories->total() }} categories
                    </div>
                    <nav>
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <div class="avatar avatar-xl bg-danger-soft rounded-circle mb-3">
                        <i class="fas fa-trash fa-lg text-danger"></i>
                    </div>
                    <h5 class="mb-2">Delete this category?</h5>
                    <p class="text-muted mb-0">
                        This action cannot be undone. All products under this category will be affected.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<style>
.category-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-active {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.btn-inactive {
    background-color: #f3f4f6;
    color: #6b7280;
    border: 1px solid #9ca3af;
}

.status-btn {
    border-radius: 50px;
    padding: 6px 14px;
    transition: all 0.2s ease;
}


.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-secondary-soft {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.empty-state {
    max-width: 300px;
    margin: 0 auto;
}

.avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.delete-form {
    display: inline;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

        // Search functionality
        const searchInput = document.getElementById('categorySearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#categoriesTable tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }

        // Delete confirmation modal
        let deleteForm = null;
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const confirmDeleteBtn = document.getElementById('confirmDelete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                deleteForm = this.closest('.delete-form');
                deleteModal.show();
            });
        });

        confirmDeleteBtn.addEventListener('click', function() {
            if (deleteForm) {
                deleteForm.submit();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const statusButtons = document.querySelectorAll('.status-btn');

        statusButtons.forEach(button => {
            button.addEventListener('click', function () {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
        });
    });
});
</script>

@endsection
