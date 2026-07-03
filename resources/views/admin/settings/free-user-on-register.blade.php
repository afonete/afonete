@extends('admin.sidebar')
@section('contents')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        
        <!-- Breadcrumb / Back button -->
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <i class="fas fa-user-plus text-blue-600"></i> Enable Free User Access
            </h2>
            <p class="text-gray-500 mt-1">Control if newly registered users are automatically granted instant, signed free standard package access to the dashboard.</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
            <div>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Settings Form Card -->
        <form method="POST" action="{{ route('admin.enable-free-user.update') }}">
            @csrf
            
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-150">
                    <h3 class="font-semibold text-gray-800 text-sm uppercase tracking-wider">Registration Gateway Settings</h3>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        
                        <!-- Description -->
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-800">ON Register Activation</h4>
                            <p class="text-gray-500 text-sm mt-1 leading-relaxed">
                                When enabled, all signups automatically bypass the package paywall and signature contract. 
                                Their profile is immediately credited with <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-bold">has_free_package = 'yes'</span> 
                                and <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-bold">has_paid_package = 'standard'</span>.
                            </p>
                        </div>

                        <!-- Toggle Switch (ON / OFF) -->
                        <div class="flex flex-col items-center gap-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="allow_free_dashboard_access" value="0">
                                <input type="checkbox" name="allow_free_dashboard_access" value="1" class="sr-only peer"
                                       {{ ($settings->allow_free_dashboard_access ?? false) ? 'checked' : '' }}>
                                <div class="w-16 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 
                                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                                            after:content-[''] after:absolute after:top-[4px] after:left-[4px] 
                                            after:bg-white after:border-gray-300 after:border after:rounded-full 
                                            after:h-6 after:w-7 after:transition-all dark:border-gray-600 
                                            peer-checked:bg-green-500"></div>
                            </label>
                            
                            <!-- Dynamic Indicator Text -->
                            <span class="text-xs uppercase font-extrabold tracking-wider 
                                         {{ ($settings->allow_free_dashboard_access ?? false) ? 'text-green-600' : 'text-gray-400' }}">
                                {{ ($settings->allow_free_dashboard_access ?? false) ? 'Status: ON' : 'Status: OFF' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-100 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 rounded-lg shadow-md transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Toggle Status
                </button>
            </div>

        </form>

        <!-- Information / Help Box -->
        <div class="mt-8 p-5 bg-blue-50 border border-blue-100 rounded-xl">
            <h5 class="text-blue-800 font-bold text-sm flex items-center gap-2 mb-2">
                <i class="fas fa-info-circle"></i> Operational Guidance
            </h5>
            <ul class="list-disc pl-5 text-xs text-blue-700 space-y-2 leading-relaxed">
                <li><strong>Turn ON:</strong> Safest for testing or promotional events. Allows zero-cost registration and instantly drops new users into their earning center dashboard without requiring Tron TRC-20 or Plisio payments first.</li>
                <li><strong>Turn OFF:</strong> Recommended for secure production mode. Forces every registering user to complete their deposit and subscribe to a premium package first.</li>
            </ul>
        </div>

    </div>
</div>
@endsection
