<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all storage, cache, and logs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('cache:clear');
        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('event:clear');
        $this->call('optimize:clear');
        $this->call('queue:clear');
        $this->call('package:discover --ansi');

        // Clear custom storage directories
        exec('rm -rf ' . storage_path('framework/sessions/*'));
        exec('rm -rf ' . storage_path('logs/*'));
        exec('rm -rf ' . storage_path('framework/cache/*'));

        $this->info('All storage and cache cleared successfully!');
        return 0;
    }
}