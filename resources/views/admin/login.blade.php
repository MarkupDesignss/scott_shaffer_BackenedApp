@extends('layouts.auth')

@section('title', 'Admin Login')

@section('styles')
<style>
    .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .logo {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: contain;
        background: linear-gradient(45deg, var(--primary-light, #14b8a6), #ffffff);
        padding: 6px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .brand-text {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    .login-form .brand-text {
        color: #1f2937;
    }
</style>
@endsection

@section('content')
<div class="login-form bg-white p-8 rounded-lg shadow-lg w-full max-w-md">

    {{-- LOGO AND BRAND --}}
    <div class="logo-container"
     style="display:flex;justify-content: center;">
        <img 
            src="https://www.markupdesigns.net/scott-shafer/favicon.png" 
            style=" width: 100px;height: 100px;display:flex;justify-content: center;"
            alt="Scott Shafer Logo"
            class="logo"
        >
    </div>

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
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required>
            </div>

            <div>
                <label class="block text-gray-700">Password</label>

                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500 pr-10"
                        required
                    >

                    <!-- Eye Icon -->
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-teal-600 focus:outline-none"
                    >
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                   c4.478 0 8.268 2.943 9.542 7
                                   -1.274 4.057 -5.064 7 -9.542 7
                                   -4.477 0 -8.268 -2.943 -9.542 -7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600 transition">
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

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19
                   c-4.478 0 -8.268 -2.943 -9.543 -7
                   a9.97 9.97 0 012.19 -3.568M6.18 6.18
                   A9.97 9.97 0 0112 5
                   c4.478 0 8.268 2.943 9.543 7
                   a9.978 9.978 0 01-4.132 5.411M15 12
                   a3 3 0 00-3-3" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3l18 18" />
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5
                   c4.478 0 8.268 2.943 9.542 7
                   -1.274 4.057 -5.064 7 -9.542 7
                   -4.477 0 -8.268 -2.943 -9.542 -7z" />
        `;
    }
}
</script>

@endsection