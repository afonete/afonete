<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use App\Models\User;
use App\Models\WithdrawalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $kyc    = KycVerification::forUser($user);
        $setting = WithdrawalSetting::current();
        $kycFee  = (float) (isset($setting->non_kyc_fee_amount) ? $setting->non_kyc_fee_amount : 10.00);

        return view('user.kyc', compact('user', 'kyc', 'kycFee'));
    }

    public function submitLevel1(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'phone_number' => 'required|string|max:50',
        ]);

        $kyc = KycVerification::forUser($user);

        $kyc->update([
            'phone_number'         => trim($request->phone_number),
            'level_1_status'       => 'pending',
            'level_1_submitted_at' => now(),
        ]);

        $user->update(['phone' => trim($request->phone_number)]);

        $kyc->recalculateProgress();

        return redirect()->back()->with('success', 'Level 1 Basic Verification (Phone Number) submitted. Awaiting admin approval.');
    }

    public function submitLevel2(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'id_type'       => 'required|string|in:passport,national_id,drivers_license',
            'date_of_birth' => 'required|date',
            'id_front'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_back'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'selfie'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $kyc = KycVerification::forUser($user);

        $frontPath  = $request->file('id_front')->store('kyc/identity', 'public');
        $backPath   = $request->hasFile('id_back') ? $request->file('id_back')->store('kyc/identity', 'public') : null;
        $selfiePath = $request->file('selfie')->store('kyc/selfies', 'public');

        $kyc->update([
            'id_type'              => $request->id_type,
            'date_of_birth'        => $request->date_of_birth,
            'id_front_path'        => $frontPath,
            'id_back_path'         => $backPath,
            'selfie_path'          => $selfiePath,
            'level_2_status'       => 'pending',
            'level_2_submitted_at' => now(),
        ]);

        $kyc->recalculateProgress();

        return redirect()->back()->with('success', 'Level 2 Identity Verification (ID + Selfie + DOB) submitted. Awaiting admin approval.');
    }

    public function submitLevel3(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'address_doc_type' => 'required|string|in:utility_bill,bank_statement,gov_residence_proof',
            'full_address'     => 'required|string|max:500',
            'city'             => 'required|string|max:100',
            'country'          => 'required|string|max:100',
            'address_doc'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $kyc = KycVerification::forUser($user);

        $docPath = $request->file('address_doc')->store('kyc/address', 'public');

        $kyc->update([
            'address_doc_type'     => $request->address_doc_type,
            'full_address'         => trim($request->full_address),
            'city'                 => trim($request->city),
            'country'              => trim($request->country),
            'address_doc_path'     => $docPath,
            'level_3_status'       => 'pending',
            'level_3_submitted_at' => now(),
        ]);

        $user->update([
            'country' => trim($request->country),
        ]);

        $kyc->recalculateProgress();

        return redirect()->back()->with('success', 'Level 3 Address Verification (Residence Proof) submitted. Awaiting admin approval.');
    }
}
