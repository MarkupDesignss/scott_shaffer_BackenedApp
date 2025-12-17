@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">User Details</h4>

    {{-- BASIC USER INFO --}}
    <div class="card mb-4">
        <div class="card-header"  style="font-size: 1.5rem;font-weight:800">Basic Information</div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $user->id }}</td>
                </tr>
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
        <div class="card-header">Profile Details</div>
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
    <div class="card">
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

</div>
@endsection
