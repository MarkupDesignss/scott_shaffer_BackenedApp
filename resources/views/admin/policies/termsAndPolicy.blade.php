<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scott Shaffer Admin - Terms & Policies</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Inter font for better typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800">

    <!-- Page Wrapper -->
    <div class="min-h-screen p-4 md:p-6 lg:p-8">

        <!-- Card Container -->
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">

            <!-- Header with blue and white gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-200 px-6 py-8 md:px-8 md:py-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Terms & Policies</h1>
                        <p class="text-blue-50 text-sm md:text-base">Review all active policies and terms</p>
                    </div>
                    <!-- Optional: Add an icon -->
                    <div class="hidden md:block">
                        <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Stats bar -->
                <div class="mt-6 flex flex-wrap gap-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2 border border-white/30">
                        <span class="text-blue-100 text-sm">Active Policies:</span>
                        <span class="ml-2 font-semibold text-white">{{ $termsAndPolicy->where('is_active', true)->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="px-4 py-6 md:px-8 md:py-8">

                @forelse ($termsAndPolicy->where('is_active', true)->sortBy('order') as $index => $policy)

                    <div class="mb-8 pb-8 border-b border-gray-200 last:border-b-0 last:mb-0 last:pb-0 transition-all duration-200 hover:bg-gray-50/50 rounded-xl p-4 md:p-5">

                        <!-- Policy Header with number badge -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-blue-600 to-blue-400 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                </div>
                            </div>

                            <div class="flex-1">
                                <!-- Policy Name -->
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                    <h2 class="text-lg md:text-xl font-bold text-gray-900 leading-tight">
                                        {{ $policy->name }}
                                    </h2>
                                </div>

                                <!-- Policy Slug -->
                                <div class="flex items-center text-gray-500 text-sm mb-4">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-600">{{ $policy->slug }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Policy Description -->
                        <div class="ml-12 md:ml-14">
                            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($policy->description)) !!}
                            </div>
                        </div>

                    </div>

                @empty

                    <!-- Empty State -->
                    <div class="text-center py-12 md:py-16">
                        <div class="mx-auto w-16 h-16 md:w-20 md:h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg md:text-xl font-semibold text-gray-700 mb-2">No Active Policies</h3>
                        <p class="text-gray-500 max-w-md mx-auto">
                            There are currently no active policies or terms configured. Add policies from the admin panel.
                        </p>
                    </div>

                @endforelse

            </div>

            <!-- Footer -->
            <div class="bg-blue-50 px-6 py-4 border-t border-blue-100">
                <div class="flex flex-col md:flex-row items-center justify-between text-sm text-blue-700">
                    <div class="mb-2 md:mb-0">
                        <span class="font-medium">Scott Shaffer Admin</span> • Policies Management
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ now()->format('F j, Y') }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
