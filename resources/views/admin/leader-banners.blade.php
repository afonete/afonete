@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    @if(session('message'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1">{{ session('message') }}</span>
            <button type="button" class="ml-auto bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-100" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm">
            <i class="fas fa-exclamation-circle text-lg mr-3 text-red-600"></i>
            <span class="font-medium flex-1">{{ session('error') }}</span>
            <button type="button" class="ml-auto bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-100" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <header class="bg-gradient-to-r from-pink-50 to-purple-50 border border-pink-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl text-slate-800 font-extrabold tracking-tight flex items-center">
                    <span class="p-2.5 bg-pink-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-images text-xl"></i>
                    </span>
                    Marketing Banners & Creatives
                </h1>
                <p class="text-slate-600 text-sm mt-2 font-medium">Upload banner images and marketing creatives for Team Leaders to download and share.</p>
            </div>
            <a href="{{ route('admin.leader-banners.upload') }}" class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition duration-150 text-sm">
                <i class="fas fa-plus"></i> Upload New Banner
            </a>
        </div>
    </header>

    @php
        $allBanners = \App\Models\TeamLeaderBanner::latest()->get();
        $pendingCount = $allBanners->where('status', 'pending')->count();
        $approvedCount = $allBanners->where('status', 'approved')->count();
        $rejectedCount = $allBanners->where('status', 'rejected')->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-4 border shadow-sm text-center">
            <p class="text-xs text-gray-400 font-bold uppercase">Total</p>
            <p class="text-2xl font-extrabold text-slate-800">{{ $allBanners->count() }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-amber-200 shadow-sm text-center">
            <p class="text-xs text-amber-400 font-bold uppercase">Pending</p>
            <p class="text-2xl font-extrabold text-amber-600">{{ $pendingCount }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-green-200 shadow-sm text-center">
            <p class="text-xs text-green-400 font-bold uppercase">Approved</p>
            <p class="text-2xl font-extrabold text-green-600">{{ $approvedCount }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-red-200 shadow-sm text-center">
            <p class="text-xs text-red-400 font-bold uppercase">Rejected</p>
            <p class="text-2xl font-extrabold text-red-600">{{ $rejectedCount }}</p>
        </div>
    </div>

    {{-- Banner Grid --}}
    @if($allBanners->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-image text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm">No banners uploaded yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($allBanners as $b)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="h-44 bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($b->image_path)
                            <img src="{{ asset('storage/' . $b->image_path) }}" alt="{{ $b->title }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x250?text=Banner'">
                        @else
                            <i class="fas fa-image text-4xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-slate-800 text-sm truncate">{{ $b->title }}</h4>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $b->description }}</p>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t">
                            <div>
                                @if($b->status === 'approved' || $b->status === 'active')
                                    <span class="bg-green-100 text-green-700 font-bold text-[10px] px-2 py-0.5 rounded-full">Approved</span>
                                @elseif($b->status === 'rejected')
                                    <span class="bg-red-100 text-red-700 font-bold text-[10px] px-2 py-0.5 rounded-full">Rejected</span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 font-bold text-[10px] px-2 py-0.5 rounded-full">Pending</span>
                                @endif
                            </div>
                            <div class="flex gap-1">
                                @if($b->status !== 'approved' && $b->status !== 'active')
                                    <a href="{{ route('admin.leader-banners.approve', $b->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-2 rounded-lg text-[10px]"><i class="fas fa-check"></i></a>
                                @endif
                                @if($b->status !== 'rejected')
                                    <form action="{{ route('admin.leader-banners.reject', $b->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg text-[10px]" onclick="return confirm('Reject this banner?')"><i class="fas fa-times"></i></button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.leader-banners.delete', $b->id) }}" onclick="return confirm('Delete?')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-1 px-2 rounded-lg text-[10px]"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
