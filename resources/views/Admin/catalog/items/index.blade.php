@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-2">Catalog Items</h2>
            <p class="text-muted mb-0">Manage your product catalog items</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.catalog-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Add New Item
            </a>
            <a href="{{ route('admin.catalog-categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-folder me-2"></i>Manage Categories
            </a>
        </div>
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
                <div class="col-md-4">
                    <h6 class="mb-0 fw-semibold">All Items</h6>
                </div>
                <div class="col-md-8">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="itemSearch" class="form-control border-start-0"
                                       placeholder="Search items...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="60">#</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-center" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="align-middle">
                                <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="item-thumbnail me-3">
                                            @if($item->image_url)
                                                <img src="{{ $item->image_url }}"
                                                     alt="{{ $item->name }}"
                                                     class="rounded"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light-primary rounded d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $item->name }}</h6>
                                            @if($item->description)
                                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                                    {{ Str::limit($item->description, 50) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->category)
                                        <span class="badge bg-light-primary text-primary rounded-pill px-3 py-2">
                                            <i class="fas fa-folder me-1"></i>
                                            {{ $item->category->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-light-secondary text-secondary rounded-pill px-3 py-2">
                                            <i class="fas fa-times me-1"></i>
                                            Uncategorized
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('admin.items.toggle-status', $item->id) }}"
                                        class="toggle-status-form d-inline">
                                        @csrf
                                        @method('PATCH')

                                        @php
                                            $isActive = $item->status === 'active' || $item->status == 1 || $item->status === true;
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
                                        <a href="{{ route('admin.catalog-items.show', $item->id) }}"
                                           class="btn btn-sm btn-outline-info rounded-pill px-3"
                                           data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.catalog-items.edit', $item->id) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                           data-bs-toggle="tooltip" title="Edit Item">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.catalog-items.destroy', $item->id) }}"
                                              method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Item">
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
                                            <i class="fas fa-box-open fa-2x text-primary"></i>
                                        </div>
                                        <h5 class="text-muted">No items found</h5>
                                        <p class="text-muted mb-4">Get started by creating your first catalog item</p>
                                        <a href="{{ route('admin.catalog-items.create') }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Item
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($items->count())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }}
                        of {{ $items->total() }} items
                    </div>
                    <nav>
                        {{ $items->links('pagination::bootstrap-5') }}
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
                    <h5 class="mb-2">Delete this item?</h5>
                    <p class="text-muted mb-0">
                        This action cannot be undone. All data associated with this item will be permanently deleted.
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
@endsection

<style>
.item-thumbnail img {
    transition: transform 0.2s;
}

.item-thumbnail img:hover {
    transform: scale(1.1);
}

.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-light-secondary {
    background-color: rgba(108, 117, 125, 0.1) !important;
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

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
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

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

    // Search functionality
    const searchInput = document.getElementById('itemSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#itemsTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    // Category filter
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            filterTable();
        });
    }

    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterTable();
        });
    }

    function filterTable() {
        const categoryValue = categoryFilter ? categoryFilter.value : '';
        const statusValue = statusFilter ? statusFilter.value : '';
        const rows = document.querySelectorAll('#itemsTable tbody tr');

        rows.forEach(row => {
            const categoryCell = row.querySelector('td:nth-child(3)');
            const statusCell = row.querySelector('td:nth-child(4)');

            let showRow = true;

            if (categoryValue && categoryCell) {
                const categoryId = categoryCell.querySelector('.badge')?.getAttribute('data-category-id');
                if (categoryId !== categoryValue) {
                    showRow = false;
                }
            }

            if (statusValue && statusCell) {
                const statusBadge = statusCell.querySelector('.badge');
                const isActive = statusBadge.classList.contains('bg-success-soft');
                const rowStatus = isActive ? 'active' : 'inactive';
                if (rowStatus !== statusValue) {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
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
