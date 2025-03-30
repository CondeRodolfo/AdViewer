<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Viewer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 h-full flex flex-col">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Ad Viewer</h1>
            <div class="flex flex-col md:flex-row gap-4 mt-4 md:mt-0 w-full md:w-auto">
                <form id="search-form" action="{{ route('ads.index') }}" method="GET" class="flex flex-row w-full md:w-auto">
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="border border-gray-300 rounded-l py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search ads...">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-r flex-shrink-0">
                        Search
                    </button>
                </form>
                <form id="refresh-form" action="{{ route('ads.refresh') }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    <button id="refresh-button" type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded flex items-center justify-center w-full">
                        <span>Refresh Data</span>
                    </button>
                </form>
            </div>
        </div>

        @if(!empty($search))
        <div class="mb-4">
            <div class="inline-flex items-center">
                <span class="text-gray-600 mr-2">Filter:</span>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">{{ $search }}</span>
                <a href="{{ route('ads.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
        @endif

        <div class="table-container w-full mb-4 flex-grow">
            <div class="relative bg-white rounded-lg shadow h-full">
                <!-- Loader overlay -->
                <div id="loader-overlay" class="loader-overlay rounded-lg">
                    <div class="spinner"></div>
                </div>
                
                <div class="table-scroll h-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($ads->count() > 0)
                                @foreach($ads as $ad)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($ad->creatives_url)
                                                <img src="{{ $ad->creatives_url }}" class="w-16 h-16 object-cover rounded" alt="{{ $ad->name }}">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded">
                                                    <span class="text-gray-500 text-xs">No Image</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $ad->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-700">
                                                @if($ad->payout)
                                                    <div class="mb-1">
                                                        <span class="font-semibold">Payout:</span> {{ $ad->payout }} {{ $ad->payout_currency }}
                                                    </div>
                                                @endif
                                                
                                                @if($ad->app_id)
                                                    <div class="mb-1">
                                                        <span class="font-semibold">App ID:</span> {{ $ad->app_id }}
                                                    </div>
                                                @endif
                                                
                                                @if($ad->kpi)
                                                    <div class="mb-1">
                                                        <span class="font-semibold">KPI:</span> {{ $ad->kpi }}
                                                    </div>
                                                @endif
                                                
                                                @if($ad->leadflow)
                                                    <div class="mb-1">
                                                        <span class="font-semibold">Leadflow:</span> {{ $ad->leadflow }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($ad->description)
                                                <div class="text-sm text-gray-600 max-w-xs">
                                                    {!! Str::limit(strip_tags($ad->description), 100) !!}
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500">No description</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-2">
                                                @if($ad->preview_url)
                                                    <a href="{{ $ad->preview_url }}" target="_blank" class="inline-block border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white px-3 py-1 rounded text-xs">Preview</a>
                                                @endif
                                                
                                                @if($ad->click_url)
                                                    <a href="{{ $ad->click_url }}" target="_blank" class="inline-block border border-green-500 text-green-500 hover:bg-green-500 hover:text-white px-3 py-1 rounded text-xs">Click URL</a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ad->external_id }}<br>
                                            <span class="text-xs">{{ $ad->created_at->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center">
                                        @if(!empty($search))
                                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded inline-block">
                                                No ads found matching "<strong>{{ $search }}</strong>". <a href="{{ route('ads.index') }}" class="underline">Clear search</a>
                                            </div>
                                        @else
                                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded inline-block">
                                                No ads found. Please click the "Refresh Data" button to import ads from the API.
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-2 w-full">
            {{ $ads->withQueryString()->links() }}
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50">
        @if(session('success'))
            <div id="toast-success" class="toast bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-lg flex items-center">
                <div class="text-green-500 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>{{ session('success') }}</div>
                <button type="button" onclick="closeToast('toast-success')" class="ml-auto text-green-500 hover:text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div id="toast-error" class="toast bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-lg flex items-center">
                <div class="text-red-500 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>{{ session('error') }}</div>
                <button type="button" onclick="closeToast('toast-error')" class="ml-auto text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get elements
            const refreshForm = document.getElementById('refresh-form');
            const refreshButton = document.getElementById('refresh-button');
            const searchForm = document.getElementById('search-form');
            const searchButton = searchForm ? searchForm.querySelector('button[type="submit"]') : null;
            const loaderOverlay = document.getElementById('loader-overlay');
            const toasts = document.querySelectorAll('.toast');
            
            // Animate toasts on load - show entrance animation
            toasts.forEach(toast => {
                // Set initial state
                toast.classList.add('showing');
                
                // After animation completes, change to steady state
                setTimeout(() => {
                    toast.classList.remove('showing');
                    toast.classList.add('show');
                }, 500);
                
                // Set auto-dismiss after 4 seconds
                setTimeout(() => {
                    dismissToast(toast);
                }, 4000);
            });
            
            // Handle refresh form submission and loading indicator
            if ((refreshForm || searchForm) && refreshButton) {
                refreshForm.addEventListener('submit', function() {
                    
                    // Disable both buttons
                    refreshButton.classList.add('btn-disabled');
                    refreshButton.disabled = true;
                    searchButton.classList.add('btn-disabled');
                    searchButton.disabled = true;
                    
                    if (loaderOverlay) {
                        loaderOverlay.classList.add('show');
                    }
                });
            }
        });

        // Toast dismiss function
        function dismissToast(toast) {
            // Add hiding class for animation out
            toast.classList.add('hiding');
            toast.classList.remove('show');
            
            // Remove the toast element after animation completes
            setTimeout(() => {
                toast.remove();
            }, 500); // Match transition duration
        }
        
        // Close toast function (for the X button)
        function closeToast(id) {
            const toast = document.getElementById(id);
            if (toast) {
                dismissToast(toast);
            }
        }
    </script>
</body>
</html> 