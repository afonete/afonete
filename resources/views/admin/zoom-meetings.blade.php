@extends('admin.sidebar')

@section('contents')
<div class="container-fluid py-4 px-4 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Flash Messages --}}
        @if(session('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                <span><i class="fas fa-check-circle mr-2 text-emerald-600"></i>{{ session('message') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                <span><i class="fas fa-exclamation-circle mr-2 text-rose-600"></i>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700"><i class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- Header Banner --}}
        <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-md border border-slate-800 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-white flex items-center gap-2">
                    <i class="fas fa-video text-rose-500"></i> Manage Zoom Meetings
                </h1>
                <p class="text-xs text-slate-400 mt-1">Configure live Zoom meeting links and toggle status between Active and Ended. Active meetings appear automatically on user and team leader dashboards.</p>
            </div>
            @php
                $activeMeeting = \App\Models\ZoomMeeting::activeMeeting();
            @endphp
            <div>
                @if($activeMeeting)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 animate-pulse">
                        <i class="fas fa-circle mr-1.5 text-[10px] text-emerald-400"></i> ZOOM LIVE NOW
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-extrabold bg-slate-800 text-slate-400 border border-slate-700">
                        <i class="fas fa-circle mr-1.5 text-[10px] text-slate-500"></i> NO ACTIVE ZOOM
                    </span>
                @endif
            </div>
        </div>

        {{-- Create / Update Form Card --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="text-base font-extrabold text-slate-900 border-b pb-3 mb-4 flex items-center gap-2">
                <i class="fas fa-plus-circle text-indigo-600"></i> Create New Zoom Meeting
            </h3>

            <form method="POST" action="{{ route('admin.zoom.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Meeting Topic / Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="topic" required placeholder="e.g. Official Bifonex Weekly Live Coaching &amp; Presentation" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('topic', 'Official Bifonex Live Zoom Presentation') }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Zoom Meeting Link (URL) <span class="text-rose-500">*</span></label>
                        <input type="url" name="zoom_link" required placeholder="https://us02web.zoom.us/j/123456789..." class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-mono font-bold text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('zoom_link') }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Meeting ID (Optional)</label>
                        <input type="text" name="meeting_id" placeholder="e.g. 849 1234 5678" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono text-slate-800" value="{{ old('meeting_id') }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Passcode (Optional)</label>
                        <input type="text" name="passcode" placeholder="e.g. 123456" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono text-slate-800" value="{{ old('passcode') }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Description / Notes (Optional)</label>
                        <textarea name="description" rows="2" placeholder="e.g. Join our executive leadership team live for system updates and Q&amp;A." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800">{{ old('description') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Initial Meeting Status <span class="text-rose-500">*</span></label>
                        <div class="flex items-center gap-4 mt-1">
                            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-800 cursor-pointer">
                                <input type="radio" name="status" value="active" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200"><i class="fas fa-bolt mr-1"></i> ACTIVE (Visible on Dashboards)</span>
                            </label>
                            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-800 cursor-pointer">
                                <input type="radio" name="status" value="ended" class="text-rose-600 focus:ring-rose-500">
                                <span class="px-2.5 py-1 rounded-lg bg-rose-100 text-rose-800 border border-rose-200"><i class="fas fa-times-circle mr-1"></i> ENDED (Hidden from Dashboards)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs shadow-md transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Save &amp; Set Zoom Meeting
                    </button>
                </div>
            </form>
        </div>

        {{-- All Zoom Meetings Table --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 overflow-x-auto">
            <h3 class="text-base font-extrabold text-slate-900 border-b pb-3 mb-4 flex items-center justify-between">
                <span><i class="fas fa-list text-indigo-600 mr-2"></i> Zoom Meetings History</span>
                <span class="text-xs bg-slate-100 text-slate-700 font-bold px-3 py-1 rounded-full">Total: {{ count($meetings) }}</span>
            </h3>

            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Topic / Title</th>
                        <th class="py-3 px-4">Zoom Link</th>
                        <th class="py-3 px-4">ID / Passcode</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Created Date</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($meetings as $m)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $m->topic }}
                                @if(!empty($m->description))
                                    <div class="text-[11px] text-slate-400 font-normal mt-0.5">{{ $m->description }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-mono text-indigo-600 max-w-xs truncate">
                                <a href="{{ $m->zoom_link }}" target="_blank" class="hover:underline flex items-center gap-1">
                                    <i class="fas fa-external-link-alt text-[10px]"></i> {{ $m->zoom_link }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-700">
                                {{ $m->meeting_id ?: 'N/A' }} / {{ $m->passcode ?: 'N/A' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($m->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 animate-pulse">
                                        <i class="fas fa-circle mr-1 text-[8px] text-emerald-600"></i> ACTIVE (LIVE)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                                        <i class="fas fa-times-circle mr-1 text-rose-600"></i> ENDED
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">{{ $m->created_at ? $m->created_at->format('M d, Y H:i') : '' }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($m->status === 'active')
                                        <form method="POST" action="{{ route('admin.zoom.toggle', $m->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="ended">
                                            <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold rounded-lg text-xs transition" title="Mark Meeting as Ended">
                                                <i class="fas fa-stop-circle mr-1"></i> Set Ended
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.zoom.toggle', $m->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold rounded-lg text-xs transition" title="Set Meeting Active">
                                                <i class="fas fa-play-circle mr-1"></i> Set Active
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.zoom.delete', $m->id) }}" class="inline" onsubmit="return confirm('Delete this Zoom meeting record?')">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <i class="fas fa-video text-3xl mb-2 text-slate-300 block"></i>
                                No Zoom meeting records found. Create a new Zoom meeting above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
