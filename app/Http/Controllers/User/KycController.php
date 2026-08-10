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
        $user    = Auth::user();
        $kyc     = KycVerification::forUser($user);
        $setting = WithdrawalSetting::current();
        $kycFee  = (float) (isset($setting->non_kyc_fee_amount) ? $setting->non_kyc_fee_amount : 10.00);

        $isProfileComplete    = $user->isProfileComplete();
        $missingProfileFields = $user->missingProfileFields();

        return view('user.kyc', compact('user', 'kyc', 'kycFee', 'isProfileComplete', 'missingProfileFields'));
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'dob'              => 'required|date',
            'phone'            => 'required|string|max:50',
            'address'          => 'required|string|max:500',
            'city'             => 'required|string|max:100',
            'country'          => 'required|string|max:100',
            'id_type'          => 'required|string|in:passport,national_id,drivers_license',
            'address_doc_type' => 'nullable|string|in:utility_bill,bank_statement,gov_residence_proof',
            'id_front'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_back'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'selfie'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'address_doc'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Construct full name and update profile details
        $fullName = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
        if (empty($fullName)) {
            $fullName = $user->name;
        }

        $user->update([
            'name'    => $fullName,
            'dob'     => $request->dob,
            'phone'   => trim($request->phone),
            'address' => trim($request->address),
            'city'    => trim($request->city),
            'country' => trim($request->country),
        ]);

        // 1. Verify profile is fully updated / complete
        if (!$user->isProfileComplete()) {
            $missing = implode(', ', $user->missingProfileFields());
            return redirect()->back()
                ->withInput()
                ->with('error', "Profile Incomplete: Please complete your profile details ({$missing}) before submitting KYC verification.");
        }

        $kyc = KycVerification::forUser($user);

        // 2. Document Status Validation
        $hasExistingIdFront = !empty($kyc->id_front_path);
        $hasExistingSelfie  = !empty($kyc->selfie_path);
        $hasNewIdFront      = $request->hasFile('id_front');
        $hasNewSelfie       = $request->hasFile('selfie');

        // Require ID Front for Level 2 if not previously uploaded or approved
        if ($kyc->level_2_status !== 'approved' && !$hasExistingIdFront && !$hasNewIdFront) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Document Status Error: Please upload your ID Front document for Identity Verification.');
        }

        // Require Selfie Photo for Level 2 if not previously uploaded or approved
        if ($kyc->level_2_status !== 'approved' && !$hasExistingSelfie && !$hasNewSelfie) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Document Status Error: Please upload your Photo Selfie for Face Verification.');
        }

        $data = [
            'phone_number'     => trim($request->phone),
            'date_of_birth'    => $request->dob,
            'id_type'          => $request->id_type,
            'address_doc_type' => $request->address_doc_type ?? 'utility_bill',
            'full_address'     => trim($request->address),
            'city'             => trim($request->city),
            'country'          => trim($request->country),
            'status'           => 'pending',
        ];

        // Handle Level 2 Uploads & Re-submissions
        if ($hasNewIdFront) {
            $data['id_front_path'] = $request->file('id_front')->store('kyc/identity', 'public');
            $data['level_2_status'] = 'pending';
            $data['level_2_submitted_at'] = now();
        }

        if ($request->hasFile('id_back')) {
            $data['id_back_path'] = $request->file('id_back')->store('kyc/identity', 'public');
        }

        if ($hasNewSelfie) {
            $data['selfie_path'] = $request->file('selfie')->store('kyc/selfies', 'public');
            $data['level_2_status'] = 'pending';
            $data['level_2_submitted_at'] = now();
        }

        if ($kyc->level_2_status === 'rejected' && ($hasNewIdFront || $hasNewSelfie)) {
            $data['level_2_status'] = 'pending';
            $data['level_2_submitted_at'] = now();
        }

        // Handle Level 3 Uploads & Re-submissions
        if ($request->hasFile('address_doc')) {
            $data['address_doc_path'] = $request->file('address_doc')->store('kyc/address', 'public');
            $data['level_3_status'] = 'pending';
            $data['level_3_submitted_at'] = now();
        } elseif ($kyc->level_3_status === 'rejected' && $request->hasFile('address_doc')) {
            $data['level_3_status'] = 'pending';
            $data['level_3_submitted_at'] = now();
        }

        // Default Level 2 status to pending if files exist but was previously unsubmitted
        if ($kyc->level_2_status === 'unsubmitted' && ($hasExistingIdFront || $hasNewIdFront)) {
            $data['level_2_status'] = 'pending';
            $data['level_2_submitted_at'] = now();
        }

        $kyc->update($data);
        $kyc->recalculateProgress();

        return redirect()->back()->with('success', 'KYC Verification details and documents submitted successfully! Admin will review your information.');
    }
}
