@extends('layouts.admin')

@section('content')
<div class="container">

    <h3 class="mb-4" style="font-weight: 700 !important;">Edit App Version</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.app_versions.update', $version->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Latest Version</label>
            <input type="text"
                   name="latest_version"
                   class="form-control"
                   value="{{ old('latest_version', $version->latest_version) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Minimum Required Version</label>
            <input type="text"
                   name="min_required_version"
                   class="form-control"
                   value="{{ old('min_required_version', $version->min_required_version) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Android URL</label>
            <input type="url"
                   name="android_url"
                   class="form-control"
                   value="{{ old('android_url', $version->android_url) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">iOS URL</label>
            <input type="url"
                   name="ios_url"
                   class="form-control"
                   value="{{ old('ios_url', $version->ios_url) }}">
        </div>

        <div class="mb-3">
            <label>
                <input type="checkbox"
                       name="force_update"
                       value="1"
                       {{ $version->force_update ? 'checked' : '' }}>
                Force Update
            </label>
        </div>

        <button type="submit" class="btn btn-success">
            Update
        </button>

        <a href="{{ route('admin.app_versions.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </form>

</div>
@endsection