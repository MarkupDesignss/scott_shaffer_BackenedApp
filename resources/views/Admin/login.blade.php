@extends('layouts.auth')

@section('title', 'Admin Login')

@section('styles')
<style>
    .logo {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(45deg, var(--primary-light), #ffffff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-dark);
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.8);
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<div class="login-form bg-white p-8 rounded-lg shadow-lg w-full max-w-md">

    <div class="logo mb-4">SS</div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
        Admin Login
    </h2>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="text-green-600 mb-4 text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div class="text-red-500 mb-4 text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="space-y-4">

            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                       required>
            </div>

            <div>
                <label class="block text-gray-700">Password</label>
                <input type="password"
                       name="password"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600 transition">
                Login
            </button>

        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.forgot-password.form') }}"
               class="text-sm text-teal-600 hover:text-teal-800 font-medium">
                Forgot your password?
            </a>
        </div>
    </form>

</div>
@endsection
