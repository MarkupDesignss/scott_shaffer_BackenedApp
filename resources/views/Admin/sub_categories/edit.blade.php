@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h4>Edit Sub Category</h4>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sub-categories.update', $subCategory->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $category->id == $subCategory->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Sub Category Name</label>
                        <input type="text" name="name" value="{{ $subCategory->name }}" class="form-control" required>
                    </div>

                    {{-- <div class="mb-3">
                        <label>Icon</label>
                        <input type="text" name="icon" value="{{ $subCategory->icon }}" class="form-control">
                    </div> --}}

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $subCategory->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$subCategory->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-success">Update</button>
                    <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
