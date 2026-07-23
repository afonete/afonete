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

    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl text-slate-800 font-extrabold tracking-tight flex items-center">
                    <span class="p-2.5 bg-indigo-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </span>
                    Team Leaders Announcements
                </h1>
                <p class="text-slate-600 text-sm mt-2 font-medium">Create and manage dynamic announcements shown in the Team Leaders and Super Leaders dashboards.</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition duration-150 text-sm">
                <i class="fas fa-plus"></i> Create Announcement
            </a>
        </div>
    </header>

    @if($announcements->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-bullhorn text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm">No announcements created yet.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Content</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($announcements as $index => $ann)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 font-extrabold">{{ $ann->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-sm truncate">{{ $ann->content }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ann->sort_order }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($ann->is_active)
                                    <span class="bg-green-100 text-green-700 font-bold text-xs px-2.5 py-1 rounded-full">Active</span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 font-bold text-xs px-2.5 py-1 rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.announcements.toggle', $ann->id) }}" class="inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition shadow-sm">
                                        <i class="fas fa-toggle-on mr-1.5"></i> Toggle
                                    </a>
                                    <a href="{{ route('admin.announcements.delete', $ann->id) }}" onclick="return confirm('Delete this announcement?')" class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition shadow-sm">
                                        <i class="fas fa-trash mr-1.5"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
