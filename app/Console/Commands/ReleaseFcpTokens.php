<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FcpTokenService;

class ReleaseFcpTokens extends Command
{
    protected $signature   = 'tokens:release-fcp';
    protected $description = 'Release monthly FC VIP locked-token installments into AVAILABLE_TOKEN (12-month schedule).';

    public function handle()
    {
        $count = FcpTokenService::releaseDueInstallments();
        $this->info("FCP tokens: {$count} schedule(s) processed.");
        return 0;
    }
}
