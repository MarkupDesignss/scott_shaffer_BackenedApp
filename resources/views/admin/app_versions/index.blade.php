@extends('layouts.admin')

@section('content')
<div class="container">

    <h3 class="mb-4 fw-bold"style="font-size:1.5rem;font-weight:800">App Versions</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr.No.</th>
                <th>Latest Version</th>
                <th>Min Required Version</th>
                <th>Force Update</th>
                <th>Android URL</th>
                <th>iOS URL</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($versions as $version)
                <tr>
                    <td>{{ $version->id }}</td>
                    <td>{{ $version->latest_version }}</td>
                    <td>{{ $version->min_required_version }}</td>
                    <td>
                        {{ $version->force_update ? 'Yes' : 'No' }}
                    </td>
                    <td>{{ $version->android_url }}</td>
                    <td>{{ $version->ios_url }}</td>
                    <td>
                        <a href="{{ route('admin.app_versions.edit', $version->id) }}"
                           class="btn btn-primary btn-sm">
                            Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        No records found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
