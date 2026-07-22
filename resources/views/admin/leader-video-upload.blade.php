@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-3xl">

    <a href="{{ route('admin.leader-videos') }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-semibold mb-6">
        <i class="fas fa-arrow-left"></i> Back to Videos
    </a>

    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <h1 class="text-2xl text-slate-800 font-extrabold flex items-center">
            <span class="p-2.5 bg-indigo-600 text-white rounded-xl shadow-md mr-3"><i class="fas fa-upload text-lg"></i></span>
            Upload New Video
        </h1>
        <p class="text-slate-600 text-sm mt-2">Upload a video file or paste a YouTube URL for Team Leaders to view.</p>
    </header>

    @if(session('error'))
        <div class="p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.leader-videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Video Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. How to Refer New Members">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Video Source <span class="text-red-500">*</span></label>
                <p class="text-xs text-gray-400 mb-2">Upload an MP4 file OR paste a YouTube URL below.</p>
                <input type="file" name="video_file" accept="video/mp4" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-bold file:text-xs">
                <input type="text" name="video_url" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm mt-2 focus:ring-blue-500 focus:border-blue-500" placeholder="OR paste YouTube URL: https://youtube.com/watch?v=...">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Target Views</label>
                    <input type="number" name="views_count" min="0" value="0" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Duration (seconds)</label>
                    <input type="number" name="duration_seconds" min="0" value="60" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm font-bold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Location / Target Region</label>
                <input type="text" name="target_region" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm" placeholder="e.g. Global, East Africa, etc.">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.leader-videos') }}" class="bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold py-2.5 px-5 rounded-xl text-sm">Cancel</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm shadow-md">
                    <i class="fas fa-upload mr-1"></i> Upload Video
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
