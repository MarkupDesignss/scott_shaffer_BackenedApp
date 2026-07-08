@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="bg-white p-8 rounded shadow w-full max-w-md">

    <h2 class="text-xl font-bold mb-4 text-center">Reset Password</h2>

    @if ($errors->any())
        <div class="text-red-500 mb-3 text-center">
            {{ $errors->first() }}
        </div>
    @endif

<form method="POST" action="{{ route('admin.reset.password') }}">
    @csrf

    <label>New Password</label>
    <input type="password" name="password" required class="w-full border p-2 mb-3">

    <label>Confirm Password</label>
    <input type="password" name="password_confirmation" required class="w-full border p-2 mb-4">

    <button class="w-full bg-teal-500 text-white py-2 rounded">
        Reset Password
    </button>
</form>

</div>
@endsection
