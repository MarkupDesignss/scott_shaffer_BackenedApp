@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4" style="font-size:1.3rem;font-weight:800">User Details</h4>

    {{-- BASIC USER INFO --}}
    <div class="card mb-4">
        <div class="card-header" style="font-size: 1.5rem;font-weight:700">Basic Information</div>
        <div class="card-body">
            <table class="table table-bordered">
                {{-- <tr>
                    <th>ID</th>
                    <td>{{ $user->id }}</td>
                </tr> --}}
                <tr>
                    <th>Name</th>
                    <td>{{ $user->full_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
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
            </table>
        </div>
    </div>

    {{-- USER PROFILE --}}
    <div class="card mb-4">
        <div class="card-header" style="font-size:1.3rem;font-weight:700">Profile Details</div>
        <div class="card-body">
            @if($user->profile)
            <table class="table table-bordered">
                <tr>
                    <th>Age Band</th>
                    <td>{{ $user->profile->age_band }}</td>
                </tr>
                <tr>
                    <th>City</th>
                    <td>{{ $user->profile->city }}</td>
                </tr>
                <tr>
                    <th>Dining Budget</th>
                    <td>{{ $user->profile->dining_budget }}</td>
                </tr>
                <tr>
                    <th>Has Dogs</th>
                    <td>{{ $user->profile->has_dogs ? 'Yes' : 'No' }}</td>
                </tr>
            </table>
            @else
            <p class="text-muted">No profile data available.</p>
            @endif
        </div>
    </div>

    {{-- USER INTERESTS --}}
    <div class="card" style="font-size:1.3rem;font-weight:700">
        <div class="card-header">User Interests</div>
        <div class="card-body">
            @if($user->interests->count())
            <ul class="list-group">
                @foreach($user->interests as $interest)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $interest->name }}
                    <small class="text-muted">
                        Added: {{ $interest->pivot->created_at->format('d M Y') }}
                    </small>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-muted">No interests selected.</p>
            @endif
        </div>
    </div>

    {{-- USER LISTS --}}
    <!-- <div class="card mt-4">
        <div class="card-header" style="font-size:1.3rem;font-weight:700">
            User Lists
        </div>

        <div class="card-body">
            @if($user->lists->count())

                @foreach($user->lists as $list)
                    <div class="border rounded p-3 mb-4">

                        {{-- LIST HEADER --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0" style="font-size:1.2rem;font-weight:500">
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
                        <div class="mb-2">
                            <span class="badge bg-info">Size: {{ $list->list_size }}</span>
                            <span class="badge bg-warning text-dark">
                                Status: {{ ucfirst($list->status ?? 'draft') }}
                            </span>
                        </div>

                        {{-- LIST ITEMS --}}
                        @if($list->items->count())
                            <table class="table table-sm table-bordered mt-3">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">#</th>
                                        <th>Item</th>
                                        <th width="20%">Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list->items->sortBy('position') as $item)
                                    {{-- @dd($item); --}}
                                        <tr>
                                            <td>{{ $item->position }}</td>

                                            <td>
                                                {{ $item->custom_text ?? "null" }}
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
                        @else
                            <p class="text-muted mt-2">No items added to this list.</p>
                        @endif

                    </div>
                @endforeach

            @else
                <p class="text-muted">User has not created any lists.</p>
            @endif
        </div>
    </div> -->


</div>
@endsection