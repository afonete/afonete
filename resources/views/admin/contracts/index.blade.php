@extends('admin.sidebar')

@section('contents')
<div class="container bg-white min-h-screen py-4 px-3">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <div>
            <h1 class="text-2xl uppercase text-slate-700 font-bold">Bifonex Contract</h1>
            <p class="text-gray-500 text-sm">Client Contract — all signed client contracts.</p>
        </div>
        <form method="GET" action="{{ route('admin.contracts.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, username..."
                   class="border border-gray-300 rounded px-3 py-2 text-sm w-72">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm" type="submit">Search</button>
        </form>
    </div>

    @if(session('message'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">{{ session('message') }}</div>
    @endif

    <div class="overflow-x-auto border border-gray-200 rounded shadow-sm">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Client</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Signed At</th>
                    <th class="px-4 py-3">Signature File</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contracts as $contract)
                    <tr class="bg-white border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ ($contracts->currentPage() - 1) * $contracts->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $contract->user->name ?? $contract->name }}</td>
                        <td class="px-4 py-3">{{ $contract->user->email ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $contract->user->user ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $contract->created_at ? $contract->created_at->format('Y-m-d H:i') : '—' }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $contract->contract }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.contracts.show', $contract->id) }}"
                               class="inline-flex items-center px-3 py-1 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-xs">
                                View Details
                            </a>
                            <a href="{{ route('admin.contracts.download', $contract->id) }}"
                               class="inline-flex items-center px-3 py-1 rounded bg-green-600 hover:bg-green-700 text-white text-xs ml-1">
                                Download PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No signed contracts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($contracts->hasPages())
        <div class="mt-4">
            {{ $contracts->links() }}
        </div>
    @endif
</div>
@endsection
