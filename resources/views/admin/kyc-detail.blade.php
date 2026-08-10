@extends('admin.sidebar')

@section('contents')
<div class="container-fluid py-4 px-4 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto space-y-6">

        <div class="mb-2">
            <a href="{{ route('admin.kyc.index') }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Back to KYC Verifications List
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                <span><i class="fas fa-check-circle mr-2 text-emerald-600"></i>{{ session('message') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @php
            $u = $kyc->user;
            $idTypeMap = [
                'passport'        => 'Passport',
                'national_id'     => 'National ID Card',
                'drivers_license' => 'Driver\'s License',
            ];
            $idTypeTitle = $idTypeMap[$kyc->id_type] ?? strtoupper($kyc->id_type ?: 'N/A');

            $addressDocMap = [
                'utility_bill'        => 'Utility Bill (Electricity, Water, Gas, Internet)',
                'bank_statement'      => 'Bank Statement / Credit Card Statement',
                'gov_residence_proof' => 'Government Residence Certificate / Tax Proof',
            ];
            $addressDocTitle = $addressDocMap[$kyc->address_doc_type] ?? strtoupper($kyc->address_doc_type ?: 'N/A');
        @endphp

        {{-- Header Banner --}}
        <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-md border border-slate-800 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-white flex items-center gap-2">
                    <i class="fas fa-user-check text-amber-400"></i> KYC Inspection: @ {{ $u->user ?? 'User #' . $kyc->user_id }}
                </h1>
                <p class="text-xs text-slate-400 mt-1">Review uploaded identity documents, selfie verification, and address proof for each level independently.</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-amber-400 font-bold uppercase tracking-wider block">Overall KYC Completion</span>
                <span class="text-2xl font-extrabold text-white">{{ $kyc->overall_percentage }}%</span>
            </div>
        </div>

        {{-- USER PROFILE OVERVIEW CARD --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-4 border-b pb-2 flex items-center gap-2">
                <i class="fas fa-user-circle text-indigo-600"></i> User Profile Details (Synced from User Profile)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div><strong>Full Name:</strong> <span class="font-bold text-slate-900">{{ $u->name ?? 'N/A' }}</span></div>
                <div><strong>Username / Handle:</strong> <span class="font-bold text-indigo-600 font-mono">@ {{ $u->user ?? 'N/A' }}</span></div>
                <div><strong>Email Address:</strong> <span class="font-bold text-slate-900">{{ $u->email ?? 'N/A' }}</span></div>

                <div><strong>Phone Number:</strong> <span class="font-bold text-slate-900">{{ $kyc->phone_number ?: ($u->phone ?: 'N/A') }}</span></div>
                <div>
                    <strong>Date of Birth:</strong> 
                    <span class="font-bold text-slate-900">
                        @if($kyc->date_of_birth)
                            {{ $kyc->date_of_birth instanceof \DateTimeInterface ? $kyc->date_of_birth->format('M d, Y') : \Carbon\Carbon::parse($kyc->date_of_birth)->format('M d, Y') }}
                        @elseif($u->dob)
                            {{ $u->dob instanceof \DateTimeInterface ? $u->dob->format('M d, Y') : \Carbon\Carbon::parse($u->dob)->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div><strong>Member ID / Code:</strong> <span class="font-bold text-slate-900 font-mono">{{ $u->transfer_code ?? ($u->getTransferCode() ?? 'N/A') }}</span></div>

                <div class="md:col-span-3 pt-2 border-t mt-1">
                    <strong>Residential Address:</strong> 
                    <span class="font-bold text-slate-900">
                        {{ $kyc->full_address ?: ($u->address ?: 'N/A') }}, 
                        {{ $kyc->city ?: ($u->city ?: 'N/A') }}, 
                        {{ $kyc->country ?: ($u->country ?: 'N/A') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- LEVEL 2 REVIEW CARD (50% - Identity: Government ID, Selfie, DOB) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between border-b pb-3 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-id-card text-indigo-600"></i> Level 2 – Identity Verification (50%)
                    </h3>
                    <small class="text-xs text-slate-500">Government ID + Selfie / Live Face Photo + Date of Birth</small>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $kyc->level_2_status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($kyc->level_2_status === 'pending' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600') }}">
                    {{ strtoupper($kyc->level_2_status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs mb-4">
                <div><strong>ID Type:</strong> <span class="font-bold text-slate-900">{{ $idTypeTitle }}</span></div>
                <div>
                    <strong>Date of Birth:</strong> 
                    <span class="font-bold text-slate-900">
                        @if($kyc->date_of_birth)
                            {{ $kyc->date_of_birth instanceof \DateTimeInterface ? $kyc->date_of_birth->format('M d, Y') : \Carbon\Carbon::parse($kyc->date_of_birth)->format('M d, Y') }}
                        @elseif($u->dob)
                            {{ $u->dob instanceof \DateTimeInterface ? $u->dob->format('M d, Y') : \Carbon\Carbon::parse($u->dob)->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div><strong>Submitted Date:</strong> {{ $kyc->level_2_submitted_at ? $kyc->level_2_submitted_at->format('M d, Y H:i') : 'N/A' }}</div>
            </div>

            {{-- Document Image Previews --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                {{-- ID Front --}}
                <div class="bg-slate-50 p-3 rounded-xl border text-center">
                    <span class="block text-xs font-bold text-slate-700 mb-2">ID Front Document</span>
                    @if($kyc->id_front_path)
                        <a href="{{ asset('storage/' . $kyc->id_front_path) }}" target="_blank" class="inline-block">
                            <img src="{{ asset('storage/' . $kyc->id_front_path) }}" alt="ID Front" class="max-h-36 rounded-lg mx-auto shadow-sm border">
                        </a>
                    @else
                        <span class="text-xs text-slate-400 italic py-6 block">No document uploaded</span>
                    @endif
                </div>

                {{-- ID Back --}}
                <div class="bg-slate-50 p-3 rounded-xl border text-center">
                    <span class="block text-xs font-bold text-slate-700 mb-2">ID Back Document</span>
                    @if($kyc->id_back_path)
                        <a href="{{ asset('storage/' . $kyc->id_back_path) }}" target="_blank" class="inline-block">
                            <img src="{{ asset('storage/' . $kyc->id_back_path) }}" alt="ID Back" class="max-h-36 rounded-lg mx-auto shadow-sm border">
                        </a>
                    @else
                        <span class="text-xs text-slate-400 italic py-6 block">N/A / Optional</span>
                    @endif
                </div>

                {{-- Selfie Photo --}}
                <div class="bg-slate-50 p-3 rounded-xl border text-center">
                    <span class="block text-xs font-bold text-slate-700 mb-2">Selfie / Live Face Photo</span>
                    @if($kyc->selfie_path)
                        <a href="{{ asset('storage/' . $kyc->selfie_path) }}" target="_blank" class="inline-block">
                            <img src="{{ asset('storage/' . $kyc->selfie_path) }}" alt="Selfie Photo" class="max-h-36 rounded-lg mx-auto shadow-sm border">
                        </a>
                    @else
                        <span class="text-xs text-slate-400 italic py-6 block">No photo uploaded</span>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('admin.kyc.review', [$kyc->id, 2]) }}" class="space-y-3 pt-3 border-t">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Admin Review Notes</label>
                    <input type="text" name="admin_notes" value="{{ old('admin_notes', $kyc->level_2_admin_notes) }}" placeholder="Optional notes for user..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="submit" name="action" value="reject" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold rounded-xl text-xs transition">
                        <i class="fas fa-times-circle mr-1"></i> Reject Level 2
                    </button>
                    <button type="submit" name="action" value="approve" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Approve Level 2 (50%)
                    </button>
                </div>
            </form>
        </div>

        {{-- LEVEL 3 REVIEW CARD (100% - Address: Utility Bill / Bank Statement / Gov Proof) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between border-b pb-3 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-emerald-600"></i> Level 3 – Address Verification (100%)
                    </h3>
                    <small class="text-xs text-slate-500">Utility Bill / Bank Statement / Government Residence Proof</small>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $kyc->level_3_status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($kyc->level_3_status === 'pending' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600') }}">
                    {{ strtoupper($kyc->level_3_status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs mb-4">
                <div><strong>Document Type:</strong> <span class="font-bold text-slate-900">{{ $addressDocTitle }}</span></div>
                <div>
                    <strong>Address:</strong> 
                    <span class="font-bold text-slate-900">
                        {{ $kyc->full_address ?: ($u->address ?: 'N/A') }}, 
                        {{ $kyc->city ?: ($u->city ?: 'N/A') }}, 
                        {{ $kyc->country ?: ($u->country ?: 'N/A') }}
                    </span>
                </div>
                <div><strong>Submitted Date:</strong> {{ $kyc->level_3_submitted_at ? $kyc->level_3_submitted_at->format('M d, Y H:i') : 'N/A' }}</div>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border text-center mb-4">
                <span class="block text-xs font-bold text-slate-700 mb-2">Proof of Residence Document</span>
                @if($kyc->address_doc_path)
                    <a href="{{ asset('storage/' . $kyc->address_doc_path) }}" target="_blank" class="inline-block bg-white p-3 rounded-lg border">
                        <i class="fas fa-file-pdf text-rose-500 text-2xl mr-2"></i>
                        <span class="text-xs font-bold text-indigo-600 underline">View Address Document</span>
                    </a>
                @else
                    <span class="text-xs text-slate-400 italic py-4 block">No document uploaded</span>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.kyc.review', [$kyc->id, 3]) }}" class="space-y-3 pt-3 border-t">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Admin Review Notes</label>
                    <input type="text" name="admin_notes" value="{{ old('admin_notes', $kyc->level_3_admin_notes) }}" placeholder="Optional notes for user..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="submit" name="action" value="reject" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold rounded-xl text-xs transition">
                        <i class="fas fa-times-circle mr-1"></i> Reject Level 3
                    </button>
                    <button type="submit" name="action" value="approve" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Approve Level 3 (100% Full KYC)
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
