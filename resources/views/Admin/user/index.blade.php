@extends('layouts.admin')


@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 fw-bold text-gray-800 mb-2" style="font-size: 1.5rem;font-weight:800">Users Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
        </div>
        {{-- <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add User
            </button>
        </div> --}}
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-3 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $data['users']->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-3 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Active Users</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $data['users']->where('status', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-3 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Countries</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $data['users']->unique('country')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-3 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                This Month
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $data['thisMonthUsers'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Card --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-bold text-gray-800">User List</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="Search users...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-secondary text-xs font-weight-bold">#</th>
                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bold">User</th>
                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bold">Contact</th>
                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bold">Location</th>
                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bold">Interests</th>
                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bold">Status</th>
                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bold text-end pe-4">Joined Date</th>
                            <th class="py-3 text-uppercase text-secondary text-xs font-weight-bold text-end pe-4">Action</th>
                        </tr>
                    </thead>

                    <tbody class="border-top-0">
                        @forelse ($data['users'] as $user)
                        <tr class="border-bottom" >
                            <td class="ps-4 py-3">
                                <span class="fw-bold text-gray-600">
                                    {{ $loop->iteration + ($data['users']->currentPage() - 1) * $data['users']->perPage() }}
                                </span>
                            </td>

                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            <span class="text-primary fw-bold">
                                                {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-gray-800">{{ $user->full_name }}</h6>
                                        {{-- <small class="text-muted">ID: {{ $user->id }}</small> --}}
                                    </div>
                                </div>
                            </td>

                            <td class="py-3">
                                <div class="text-gray-800 fw-medium">{{ $user->phone }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>

                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <span class="fw-medium">{{ $user->country }}</span>
                                </div>
                            </td>

                            <td class="py-3">
                                @if($user->interests->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($user->interests as $interest)
                                            <span class="badge bg-info bg-opacity-10 text-info-emphasis fw-medium px-2 py-1">
                                                {{ $interest->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">No interests</span>
                                @endif
                            </td>

                            <td>
                                <form method="POST" action="{{ route('admin.user.toggle-status', $user->id) }}" class="toggle-status-form">
                                    @csrf
                                    <button type="submit" class="btn btn-sm status-btn {{ $user->status ? 'btn-active' : 'btn-inactive' }}">
                                        <i class="bi {{ $user->status ? 'bi-toggle-on' : 'bi-toggle-off' }} me-1"></i>
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>

                            <td class="py-3 text-end pe-4">
                                <div class="text-gray-800 fw-medium">{{ $user->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $user->created_at->format('h:i A') }}</small>
                            </td>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-view fa-3x text-gray-300 mb-3"></i>
                                    <a href="{{ route('admin.users.view', $user->id) }}"
                                        class="btn btn-sm btn-primary">
                                            View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                                    <h5 class="text-gray-500">No users found</h5>
                                    <p class="text-muted">Start by adding your first user</p>
                                    <button class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-2"></i>Add User
                                    </button>
                                </div>
                            </td>
                        </tr>


                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card Footer with Pagination --}}
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $data['users']->firstItem() ?? 0 }} to {{ $data['users']->lastItem() ?? 0 }}
                    of {{ $data['users']->total() }} entries
                </div>
                <div>
                    {{ $data['users']->links() }}
                </div>
            </div>
        </div>
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
        border-radius: 50px;
        transition: all 0.2s ease;
    }

</style>

<script>
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

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.querySelector('input[name="search"]');
        const rows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('keyup', function () {
            const value = this.value.toLowerCase();

            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value)
                ? ''
                : 'none';
            });
        });
    });
</script>

@endsection
