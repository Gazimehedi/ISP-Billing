<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Olt\MonitoringService;

class SyncOltSignals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'olt:sync-signals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync signal data for all active OLT devices and connected ONUs';

    protected $monitoringService;

    public function __construct(MonitoringService $monitoringService)
    {
        parent::__construct();
        $this->monitoringService = $monitoringService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting OLT signal synchronization...');
        
        $result = $this->monitoringService->syncAllSignals();
        
        $this->info("✓ Synced {$result['total_synced']} ONU signals");
        
        if (!empty($result['errors'])) {
            $this->warn('Errors encountered:');
            foreach ($result['errors'] as $error) {
                $this->error("  - {$error}");
            }
        }
        
        $this->info('Signal synchronization completed.');
        
        return Command::SUCCESS;
    }
}
