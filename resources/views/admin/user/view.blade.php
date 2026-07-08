@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-2 px-sm-3 px-md-4">

        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2 gap-sm-0">
            <div>
                <h4 class="h3 mb-2"">User Details</h4>
            </div>
            <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-sm w-sm-auto">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        {{-- BASIC USER INFO --}}
        <div class="card mb-3">
            <div class="card-header" style="font-size: 1.2rem;font-weight:700">Basic Information</div>
            <div class="card-body p-0 p-sm-3">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%; min-width: 120px;">Name</th>
                                <td>{{ $user->full_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td class="text-break">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- USER PROFILE --}}
        <div class="card mb-3">
            <div class="card-header" style="font-size:1.1rem;font-weight:700">Profile Details</div>
            <div class="card-body p-0 p-sm-3">
                @if ($user->profile)
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 30%; min-width: 120px;">Age Band</th>
                                    <td>{{ $user->profile->age_band ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $user->profile->city ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Budget</th>
                                    <td>{{ $user->profile->dining_budget ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Has Dogs</th>
                                    <td>{{ isset($user->profile->has_dogs) ? ($user->profile->has_dogs ? 'Yes' : 'No') : '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted p-3">No profile data available.</p>
                @endif
            </div>
        </div>

        {{-- USER INTERESTS --}}
        <div class="card mb-3">
            <div class="card-header" style="font-size:1.1rem;font-weight:700">User Interests</div>
            <div class="card-body p-0 p-sm-3">
                @if ($user->interests->count())
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60%;">Interest</th>
                                    <th>Added On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->interests as $interest)
                                    <tr>
                                        <td>{{ $interest->name }}</td>
                                        <td>{{ optional($interest->pivot->created_at)->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted p-3">No interests selected.</p>
                @endif
            </div>
        </div>
        {{-- USER LISTS (Commented out - kept responsive) --}}
        <!-- <div class="card mt-4">
                        <div class="card-header" style="font-size:1.1rem;font-weight:700">
                            User Lists
                        </div>

                        <div class="card-body p-0 p-sm-3">
                            @if ($user->lists->count())
    @foreach ($user->lists as $list)
    <div class="border rounded p-3 p-sm-4 mb-3">

                                        {{-- LIST HEADER --}}
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-2 gap-2 gap-sm-0">
                                            <h5 class="mb-0" style="font-size:1rem;font-weight:500">
                                                {{ $list->title }}
                                                <span class="badge bg-secondary ms-2">
                                                    {{ ucfirst($list->visibility) }}
                                                </span>
                                            </h5>

                                            <small class="text-muted">
                                                Created: {{ $list->created_at->format('d M Y') }}
                                            </small>
                                        </div>

                                        {{-- LIST META --}}
                                        <div class="mb-2 d-flex flex-wrap gap-1">
                                            <span class="badge bg-info">Size: {{ $list->list_size }}</span>
                                            <span class="badge bg-warning text-dark">
                                                Status: {{ ucfirst($list->status ?? 'draft') }}
                                            </span>
                                        </div>

                                        {{-- LIST ITEMS --}}
                                        @if ($list->items->count())
    <div class="table-responsive mt-3">
                                                <table class="table table-sm table-bordered mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="10%">#</th>
                                                            <th>Item</th>
                                                            <th width="20%">Type</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($list->items->sortBy('position') as $item)
    <tr>
                                                                <td>{{ $item->position }}</td>
                                                                <td class="text-break">
                                                                    {{ $item->custom_text ?? 'null' }}
                                                                </td>
                                                                <td>
                                                                    <span class="badge {{ $item->catalog_item_id ? 'bg-primary' : 'bg-secondary' }}">
                                                                        {{ $item->catalog_item_id ? 'Catalog Item' : 'Custom Text' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
@else
    <p class="text-muted mt-2">No items added to this list.</p>
    @endif

                                    </div>
    @endforeach
@else
    <p class="text-muted p-3">User has not created any lists.</p>
    @endif
                        </div>
                    </div> -->

    </div>
@endsection
