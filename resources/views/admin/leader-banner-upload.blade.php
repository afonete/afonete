@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-3xl">

    <a href="{{ route('admin.leader-banners') }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-semibold mb-6">
        <i class="fas fa-arrow-left"></i> Back to Banners
    </a>

    <header class="bg-gradient-to-r from-pink-50 to-purple-50 border border-pink-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <h1 class="text-2xl text-slate-800 font-extrabold flex items-center">
            <span class="p-2.5 bg-pink-600 text-white rounded-xl shadow-md mr-3"><i class="fas fa-upload text-lg"></i></span>
            Upload New Banner
        </h1>
        <p class="text-slate-600 text-sm mt-2">Upload a marketing banner image for Team Leaders to download and share on social media.</p>
    </header>

    @if(session('error'))
        <div class="p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.leader-banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Banner Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm focus:ring-pink-500 focus:border-pink-500" placeholder="e.g. Join BIFONEX Today!">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Banner Image <span class="text-red-500">*</span></label>
                <input type="file" name="banner_file" required accept="image/*" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:bg-pink-50 file:text-pink-700 file:font-bold file:text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm focus:ring-pink-500 focus:border-pink-500" placeholder="Brief description of the banner..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Landing URL (optional)</label>
                <input type="url" name="landing_url" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm" placeholder="https://...">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Target Views</label>
                    <input type="number" name="views_count" min="0" value="0" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Duration (seconds)</label>
                    <input type="number" name="duration_seconds" min="0" value="30" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm font-bold">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.leader-banners') }}" class="bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold py-2.5 px-5 rounded-xl text-sm">Cancel</a>
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm shadow-md">
                    <i class="fas fa-upload mr-1"></i> Upload Banner
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
