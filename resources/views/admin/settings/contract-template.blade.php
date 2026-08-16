@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Alert Banner --}}
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 font-semibold" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 font-semibold" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            <ul class="list-disc pl-5 space-y-1 font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg text-xl">
                <i class="fas fa-file-signature"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">User Contract Template</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Manage the full agreement users read and sign on <code>/user/contract</code>,
                    and the short summary shown on <code>/user/contracts/bifonex</code>.
                </p>
            </div>
        </div>
    </div>

    @if($usingFallback)
        <div class="p-4 mb-6 text-sm text-amber-800 rounded-lg bg-amber-50 border border-amber-200 font-semibold max-w-4xl mx-auto" role="alert">
            <i class="fas fa-info-circle mr-1"></i>
            The database currently holds the project's <strong>original full agreement</strong>
            (auto-seeded from the code). This is exactly what users see on <code>/user/contract</code>.
            Modify it below and click Save to publish your version.
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-4xl mx-auto">
        <form action="{{ route('admin.settings.contract-template.update') }}" method="POST">
            @csrf

            {{-- Per-user placeholders reference --}}
            <div class="p-4 mb-6 text-xs text-blue-800 rounded-lg bg-blue-50 border border-blue-200">
                <strong><i class="fas fa-user-tag mr-1"></i> Per-user placeholders</strong> — these are replaced
                automatically with each viewing user's own information (keep them in the text so the
                contract stays personalised):
                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-1 font-mono">
                    <span><code>{USER_NAME}</code> → user's full name (e.g. John Doe)</span>
                    <span><code>{USER_COUNTRY}</code> → user's nationality (e.g. Bulgaria)</span>
                    <span><code>{USER_USERNAME}</code> → user's @username</span>
                    <span><code>{USER_ID}</code> → user's transfer code</span>
                    <span><code>{COMPANY_NAME}</code> → company name (Bifonex)</span>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold uppercase text-slate-700 mb-2">
                    Full Contract / Agreement Body — shown on <code>/user/contract</code>
                </label>
                <input id="body_content" type="hidden" name="body_content" value="{{ old('body_content', $bodyContent) }}">
                <trix-editor input="body_content" class="trix-content bg-slate-50 border border-slate-300 rounded-lg p-3 text-slate-800 text-sm focus:outline-none focus:bg-white min-h-[300px]"></trix-editor>
                <span class="text-[11px] text-slate-400 block mt-1">
                    Stored in the database and shown to every user on /user/contract — edit as needed.
                    The "Mr./ Mrs. / Ms. {USER_NAME}" and "Nationality, {USER_COUNTRY}" lines stay per-user.
                    Saving with an empty editor restores the project's original agreement into the database.
                </span>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold uppercase text-slate-700 mb-2">
                    Short Contract Summary — shown on <code>/user/contracts/bifonex</code>
                </label>
                <input id="summary_content" type="hidden" name="summary_content" value="{{ old('summary_content', $summaryContent) }}">
                <trix-editor input="summary_content" class="trix-content bg-slate-50 border border-slate-300 rounded-lg p-3 text-slate-800 text-sm focus:outline-none focus:bg-white min-h-[200px]"></trix-editor>
                <span class="text-[11px] text-slate-400 block mt-1">
                    Stored in the database and shown on /user/contracts/bifonex (as PDF).
                    Saving with an empty editor restores the original default summary into the database.
                </span>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Contract Template
                </button>
            </div>
        </form>
    </div>

</div>

{{-- Ensure Trix Editor CSS/JS --}}
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
<style>
    trix-editor {
        min-height: 220px !important;
        background-color: #f8fafc !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #1e293b !important;
    }
    trix-toolbar .trix-button-group {
        border-color: #cbd5e1 !important;
        background-color: #ffffff !important;
        border-radius: 0.375rem !important;
    }
</style>
@endsection
