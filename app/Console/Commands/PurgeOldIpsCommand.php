<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PurgeOldIpsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:old-ips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days=30;
        $this->info('Purging records older than '.$days.' days');
        $purged=\App\Models\IpGeolocation::purgeOldRecords($days);
        $this->info('Purged '.$purged.' records');
    }
}
