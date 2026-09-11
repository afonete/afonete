<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FcStreamlineRankService;

class EvaluateFcStreamlineRanks extends Command
{
    protected $signature   = 'fc-ranks:evaluate';
    protected $description = 'Evaluate FC VIP Streamline Rank progress for all FC users (activations, completions, expiries). Safe to run hourly.';

    public function handle()
    {
        $stats = FcStreamlineRankService::evaluate();

        $this->info("fc-ranks:evaluate done. Activated={$stats['activated']}  PendingAdmin={$stats['pending']}  Expired={$stats['expired']}");
        return 0;
    }
}
