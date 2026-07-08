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
                                <i class="fas fa-users fa-2x text-primary"></i>
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
                                <i class="fas fa-user-check fa-2x text-success"></i>
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
                                <i class="fas fa-globe fa-2x text-warning"></i>
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
                                <i class="fas fa-calendar-alt fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Main Card --}}
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0 fw-bold text-gray-800">User List</h5>
                    </div>
                    <div class="col-md-6">
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
                    <table class="table align-middle mb-0" style="border:1px solid #dee2e6;">
                        <thead style="background:#fff;" class="table-light">
                            <tr>
                                <th class="fw-bold py-3 ps-4 border" style="font-size:16px;">Sr.No.</th>
                                <th class="fw-bold py-3 border" style="font-size:16px;">User</th>
                                <th class="fw-bold py-3 border" style="font-size:16px;">Contact</th>
                                <th class="fw-bold py-3 border" style="font-size:16px;">Location</th>
                                <th class="fw-bold py-3 border" style="font-size:16px;">Interests</th>
                                <th class="fw-bold py-3 border text-center" style="font-size:16px;">Status</th>
                                <th class="fw-bold py-3 border" style="font-size:16px;">Joined</th>
                                <th class="fw-bold py-3 border text-center" style="font-size:16px;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($data['users'] as $user)
                                <tr>

                                    {{-- Sr No --}}
                                    <td class="border py-4 ps-4 fw-semibold">
                                        {{ $loop->iteration + ($data['users']->currentPage() - 1) * $data['users']->perPage() }}
                                    </td>

                                    {{-- User --}}
                                    <td class="border py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center fw-bold me-3"
                                                style="width:48px;height:48px;font-size:18px;">
                                                {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="fw-bold" style="font-size:16px;">
                                                    {{ $user->full_name }}
                                                </div>

                                                <small class="text-muted">
                                                    ID #{{ $user->id }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Contact --}}
                                    <td class="border py-4">
                                        <div class="fw-semibold">
                                            {{ $user->phone ?: '-' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $user->email }}
                                        </small>
                                    </td>

                                    {{-- Location --}}
                                    <td class="border py-4">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $user->country ?: '-' }}
                                    </td>

                                    {{-- Interests --}}
                                    <td class="border py-4" style="min-width:220px;">
                                        @if ($user->interests->count())
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($user->interests as $interest)
                                                    <span class="badge rounded-pill border px-3 py-2"
                                                        style="background:#f8f9fa;color:#0d6efd;border-color:#d0d7de;">
                                                        {{ $interest->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                No interests
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="border py-4 text-center">
                                        <form method="POST" action="{{ route('admin.user.toggle-status', $user->id) }}"
                                            class="toggle-status-form">
                                            @csrf

                                            @if ($user->status)
                                                <button type="submit" class="btn rounded-pill px-4 py-2 fw-semibold"
                                                    style="background:#c9f5df;color:#006b4f;border:1px solid #9ce7c1;min-width:130px;">
                                                    <i class="bi bi-toggle-on me-1"></i>
                                                    Active
                                                </button>
                                            @else
                                                <button type="submit" class="btn rounded-pill px-4 py-2 fw-semibold"
                                                    style="background:#ffe3e3;color:#b42318;border:1px solid #f5b5b5;min-width:130px;">
                                                    <i class="bi bi-toggle-off me-1"></i>
                                                    Inactive
                                                </button>
                                            @endif
                                        </form>
                                    </td>

                                    {{-- Joined --}}
                                    <td class="border py-4">
                                        <div class="fw-semibold">
                                            {{ $user->created_at->format('d M Y') }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $user->created_at->format('h:i A') }}
                                        </small>
                                    </td>

                                    {{-- Action --}}
                                    <td class="border py-4 text-center">
                                        <a href="{{ route('admin.users.view', $user->id) }}" class="text-secondary me-3"
                                            style="font-size:20px;text-decoration:none;">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 border">
                                        <i class="bi bi-people fs-1 text-secondary"></i>

                                        <h5 class="mt-3 mb-1">
                                            No users found
                                        </h5>

                                        <p class="text-muted mb-0">
                                            There are no users available.
                                        </p>
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

        .btn-outline-primary,
        .btn-outline-danger {
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

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const rows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('keyup', function() {
                const value = this.value.toLowerCase();

                rows.forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(value) ?
                        '' :
                        'none';
                });
            });
        });
    </script>

@endsection
