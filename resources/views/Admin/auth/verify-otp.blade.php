@extends('layouts.auth')

@section('title', 'Verify OTP')

@section('content')
<div class="bg-white p-8 rounded shadow w-full max-w-md">

    <h2 class="text-xl font-bold mb-4 text-center">Verify OTP</h2>

    @if ($errors->any())
        <div class="text-red-500 mb-3 text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.otp.verify') }}">
        @csrf

        <input type="hidden" name="email" value="{{ session('email') }}">

        <label>Enter OTP</label>
        <input type="text"
               name="otp"
               maxlength="6"
               required
               class="w-full border p-2 mb-4">

        <button class="w-full bg-teal-500 text-white py-2 rounded">
            Verify OTP
        </button>
    </form>
</div>
@endsection
