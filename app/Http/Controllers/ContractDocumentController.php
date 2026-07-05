<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractDocumentController extends Controller
{
    /**
     * User page: show their own signed Bifonex contract inside the system.
     * No user download route/button is provided.
     */
    public function userIndex()
    {
        $contract = Contract::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        $pdfBase64 = null;
        if ($contract) {
            $pdfBase64 = base64_encode($this->pdfBinary($contract));
        }

        return view('contracts.user-bifonex-contract', compact('contract', 'pdfBase64'));
    }

    /** Admin list of all signed client contracts. */
    public function adminIndex(Request $request)
    {
        $query = Contract::with('user')->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contract', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('user', 'like', "%{$search}%");
                  });
            });
        }

        $contracts = $query->paginate(20)->withQueryString();

        return view('admin.contracts.index', compact('contracts'));
    }

    /** Admin detail page with inline PDF preview and download button. */
    public function adminShow(Contract $contract)
    {
        $contract->load('user');
        $pdfBase64 = base64_encode($this->pdfBinary($contract));

        return view('admin.contracts.show', compact('contract', 'pdfBase64'));
    }

    /** Admin-only PDF download. */
    public function adminDownload(Contract $contract)
    {
        $contract->load('user');
        $filename = 'bifonex-contract-' . ($contract->user->user ?? $contract->user_id) . '-' . $contract->id . '.pdf';

        return response($this->pdfBinary($contract), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
        ]);
    }

    private function pdfBinary(Contract $contract): string
    {
        $contract->loadMissing('user');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('chroot', public_path());

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('contracts.pdf.bifonex-contract', [
            'contract'         => $contract,
            'user'             => $contract->user,
            'signatureDataUri' => $this->signatureDataUri($contract),
        ])->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function signatureDataUri(Contract $contract): ?string
    {
        $file = trim((string) $contract->contract);
        if ($file === '') {
            return null;
        }

        $candidates = [
            public_path('signature/' . $file),
            public_path($file),
            storage_path('app/public/' . $file),
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                $mime = mime_content_type($path) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        return null;
    }
}
