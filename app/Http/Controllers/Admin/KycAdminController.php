<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use App\Models\User;
use App\Models\WithdrawalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycAdminController extends Controller
{
    public function index(Request $request)
    {
        KycVerification::forUser(Auth::user());
        $query = KycVerification::with(['user', 'reviewer'])->latest();

        if ($request->filled('filter')) {
            $filter = $request->filter;
            if ($filter === 'pending') {
                $query->where(function ($q) {
                    $q->where('status', 'pending')
                      ->orWhere('level_1_status', 'pending')
                      ->orWhere('level_2_status', 'pending')
                      ->orWhere('level_3_status', 'pending');
                });
            } elseif ($filter === 'approved') {
                $query->where('overall_percentage', 100);
            } elseif ($filter === 'rejected') {
                $query->where('status', 'rejected');
            } elseif (in_array($filter, ['level1', 'level2', 'level3'])) {
                $levelCol = str_replace('level', 'level_', $filter) . '_status';
                $query->where($levelCol, 'pending');
            }
        }

        $verifications = $query->paginate(20)->withQueryString();

        $setting = WithdrawalSetting::current();
        $nonKycFee = (float) (isset($setting->non_kyc_fee_amount) ? $setting->non_kyc_fee_amount : 10.00);

        $pendingCount = KycVerification::where('status', 'pending')
            ->orWhere('level_1_status', 'pending')
            ->orWhere('level_2_status', 'pending')
            ->orWhere('level_3_status', 'pending')
            ->count();

        return view('admin.kyc-verifications', compact('verifications', 'nonKycFee', 'pendingCount'));
    }

    public function show($id)
    {
        $kyc = KycVerification::with(['user', 'reviewer'])->findOrFail($id);
        $setting = WithdrawalSetting::current();
        $nonKycFee = (float) (isset($setting->non_kyc_fee_amount) ? $setting->non_kyc_fee_amount : 10.00);

        return view('admin.kyc-detail', compact('kyc', 'nonKycFee'));
    }

    public function reviewLevel(Request $request, $id, $level)
    {
        $request->validate([
            'action'      => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $kyc = KycVerification::findOrFail($id);
        $action = $request->action;
        $statusVal = $action === 'approve' ? 'approved' : 'rejected';

        if (!in_array($level, ['1', '2', '3'])) {
            return back()->with('error', 'Invalid KYC Level.');
        }

        $statusCol = "level_{$level}_status";
        $appCol    = "level_{$level}_approved_at";
        $notesCol  = "level_{$level}_admin_notes";

        $kyc->{$statusCol} = $statusVal;
        $kyc->{$notesCol}  = $request->admin_notes;
        if ($action === 'approve') {
            $kyc->{$appCol} = now();
        }
        $kyc->reviewed_by = Auth::id();

        $kyc->save();
        $kyc->recalculateProgress();

        $levelNames = [
            '1' => 'Level 1 – Basic (Phone Number)',
            '2' => 'Level 2 – Identity (ID + Selfie + DOB)',
            '3' => 'Level 3 – Address (Residence Proof)',
        ];
        $levelName = $levelNames[$level] ?? "Level {$level}";

        $msg = $action === 'approve'
            ? "{$levelName} APPROVED successfully. Completion progress updated to {$kyc->overall_percentage}%."
            : "{$levelName} REJECTED with notes.";

        return back()->with('message', $msg);
    }

    public function updateNonKycFee(Request $request)
    {
        $request->validate([
            'non_kyc_fee_amount' => 'required|numeric|min:0',
        ]);

        $setting = WithdrawalSetting::current();
        if (\Illuminate\Support\Facades\Schema::hasColumn('withdrawal_settings', 'non_kyc_fee_amount')) {
            $setting->update([
                'non_kyc_fee_amount' => (float) $request->non_kyc_fee_amount,
            ]);
        }

        return back()->with('message', 'Non-KYC unverified fee amount updated to $' . number_format($request->non_kyc_fee_amount, 2));
    }
}
