@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-2xl">

    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div>
            <h1 class="text-2xl sm:text-3xl text-slate-800 font-extrabold tracking-tight flex items-center">
                <span class="p-2.5 bg-indigo-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                    <i class="fas fa-bullhorn text-xl"></i>
                </span>
                Create New Announcement
            </h1>
            <p class="text-slate-600 text-sm mt-2 font-medium">Broadcast a new system announcement to all Team Leaders and Super Leaders.</p>
        </div>
    </header>

    @if ($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm">
            <div class="font-bold flex items-center mb-1 text-red-700">
                <i class="fas fa-exclamation-circle text-lg mr-2"></i> Fix the following validation issues:
            </div>
            <ul class="list-disc pl-5 mt-1 text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <form action="{{ route('admin.announcements.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Announcement Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" required value="{{ old('title') }}" placeholder="e.g. Server Maintenance or New Promo Campaign" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
            </div>

            <div class="mb-5">
                <label for="content" class="block text-sm font-bold text-slate-700 mb-2">Content <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" required rows="5" placeholder="Write the announcement description details here..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">{{ old('content') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="sort_order" class="block text-sm font-bold text-slate-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                </div>
                <div class="flex items-center mt-6">
                    <label class="inline-flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-5 w-5 mr-2">
                        <span class="text-sm font-bold text-slate-700">Set as Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-xl text-sm transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition shadow-sm">
                    <i class="fas fa-paper-plane mr-2"></i> Save Announcement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
