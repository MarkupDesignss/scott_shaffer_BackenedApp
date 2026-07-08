@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h4 style="font-size: 1.5rem;font-weight:800">Add Sub Category</h4>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sub-categories.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Sub Category Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    {{-- <div class="mb-3">
                        <label>Icon (optional)</label>
                        <input type="text" name="icon" class="form-control">
                    </div> --}}

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-success">Save</button>
                    <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
