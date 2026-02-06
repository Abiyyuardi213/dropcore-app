<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IndoRegionService;

class SyncIndoRegionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-wilayah {--depth=city : Depth of sync (province, city, district, village)} {--force : Force update existing data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Indonesia Regional Data (Provinsi, Kota, Kecamatan, Kelurahan) from External API';

    /**
     * Execute the console command.
     */
    public function handle(IndoRegionService $regionService)
    {
        $this->info('Starting Regional Data Sync...');
        $depth = $this->option('depth');

        $levels = ['province' => 1, 'city' => 2, 'district' => 3, 'village' => 4];
        $targetLevel = $levels[$depth] ?? 2;

        try {
            // 1. Wilayah & Provinces (Level 1)
            $this->info("Syncing Wilayah & Provinces...");
            $regionService->syncProvinces();

            if ($targetLevel >= 2) {
                $this->info("Syncing Cities...");
                $regionService->syncCities();
            }

            if ($targetLevel >= 3) {
                $this->info("Syncing Districts (This may take a while)...");
                $regionService->syncDistricts();
            }

            if ($targetLevel >= 4) {
                $this->info("Syncing Villages (This will take a long time)...");
                $regionService->syncVillages();
            }

            $this->newLine();
            $this->info("Sync Completed Successfully!");
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
