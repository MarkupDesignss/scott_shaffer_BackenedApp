@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
        {{-- SUCCESS MESSAGE --}}
@if (session('success'))
<div class="relative mb-4 overflow-hidden rounded-xl bg-gradient-to-r from-green-400 to-emerald-300 p-4 text-white shadow-lg" id="success-message">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="mr-3 rounded-full bg-white/20 p-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold">{{ session('success') }}</p>
                <p class="text-sm text-green-100 opacity-90">Success</p>
            </div>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div class="absolute bottom-0 left-0 h-1 w-full bg-white/30">
        <div class="h-full bg-white progress-bar"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(function() {
            // Fade out effect
            successMessage.style.transition = 'opacity 0.5s ease';
            successMessage.style.opacity = '0';

            // Remove from DOM after fade
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 500);
        }, 3000); // 3 seconds
    }
});
</script>
@endif
    <h2 class="text-3xl font-bold bg-gradient-to-r from-teal-500 to-blue-500 bg-clip-text text-transparent">
        Dashboard Overview
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Active Users --}}
        <a href="{{route('admin.user.index')}}" class="relative bg-gradient-to-br from-green-500 to-emerald-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>

            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">All Users</h3>
                <i class="bi bi-person-check text-white text-xl"></i>
            </div>

            <p class="text-4xl font-bold mt-4 text-white">{{ $users ?? 0 }}</p>
            <p class="text-sm text-white/80 mt-2">Current users</p>
        </a>

        {{-- Inactive Users --}}
        <a href="{{route('admin.featured-lists.index')}}" class="relative bg-gradient-to-br from-gray-500 to-slate-600 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>

            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Featured Lists</h3>
                <i class="bi bi-person-x text-white text-xl"></i>
            </div>

            <p class="text-4xl font-bold mt-4 text-white">{{ $featurelist ?? 0 }}</p>
            <p class="text-sm text-white/80 mt-2">All Feature List</p>
        </a>

        {{-- Total Categories --}}
        <a href="{{route('admin.catalog-categories.index')}}" class="relative bg-gradient-to-br from-blue-500 to-indigo-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>

            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Categories</h3>
                <i class="bi bi-folder2-open text-white text-xl"></i>
            </div>

            <p class="text-4xl font-bold mt-4 text-white">{{ $totalCategories ?? 0 }}</p>
            <p class="text-sm text-white/80 mt-2">Total catalog categories</p>
        </a>

        {{-- Total Items --}}
        <a href="{{route('admin.catalog-items.index')}}" class="relative bg-gradient-to-br from-yellow-500 to-amber-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>

            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Items</h3>
                <i class="bi bi-box-seam text-white text-xl"></i>
            </div>

            <p class="text-4xl font-bold mt-4 text-white">{{ $totalItems ?? 0 }}</p>
            <p class="text-sm text-white/80 mt-2">Total catalog items</p>
        </a>

    </div>
</div>

<script>
    document.getElementById("success").
</script>
@endsection
