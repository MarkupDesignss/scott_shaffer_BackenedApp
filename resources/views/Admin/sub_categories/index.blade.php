@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h4 style="font-size: 1.5rem;font-weight:800">Sub Categories</h4>
            <a href="{{ route('admin.sub-categories.create') }}" class="btn btn-primary">
                + Add Sub Category
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subCategories as $sub)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sub->category->name }}</td>
                                <td>{{ $sub->name }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.sub-categories.toggle-status', $sub->id) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('POST')

                                        @php
                                            $isActive = $sub->status === 'active' || $sub->status == 1;
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
                                        <a href="{{ route('admin.sub-categories.show', $sub->id) }}"
                                            class="btn btn-sm btn-outline-info rounded-pill px-3">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.sub-categories.edit', $sub->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($subCategories->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">No Data Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
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
            border: 1px solid #a7f3d0;
        }

        .btn-inactive {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
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
@endsection
