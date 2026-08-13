@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Alert Banner --}}
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 font-semibold" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg text-xl">
                <i class="fas fa-file-contract"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Affiliate Terms &amp; Conditions Settings</h1>
                <p class="text-xs text-slate-500 mt-0.5">Configure the terms displayed when users click "Become An Affiliate" to activate their Binary Status.</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-4xl mx-auto">
        <form action="{{ route('admin.settings.affiliate-terms.update') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-xs font-bold uppercase text-slate-700 mb-2">Affiliate Program Terms &amp; Conditions (Rich Text Editor)</label>
                
                {{-- Rich Text Input --}}
                <input id="terms_content" type="hidden" name="terms_content" value="{{ old('terms_content', $termsContent) }}">
                <trix-editor input="terms_content" class="trix-content bg-slate-50 border border-slate-300 rounded-lg p-3 text-slate-800 text-sm focus:outline-none focus:bg-white min-h-[300px]"></trix-editor>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Affiliate Terms
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
        min-height: 280px !important;
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
