@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">
        Export Logs – {{ $segment->name }}
    </h4>

    <a href="{{ route('admin.segments.index') }}" class="btn btn-secondary mb-3">
        ← Back to Segments
    </a>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Exported By</th>
                        <th>File</th>
                        <th>Filters Snapshot</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exports as $export)
                        <tr>
                            <td>{{ $export->admin->name ?? 'Admin' }}</td>
                            <td>
                                <a href="{{ Storage::url($export->file_path) }}"
                                   target="_blank">
                                    Download CSV
                                </a>
                            </td>
                            <td>
                                <pre class="mb-0">{{ json_encode($export->filters_snapshot, JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td>{{ $export->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No exports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
