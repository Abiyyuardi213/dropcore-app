<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    public function handle()
    {
        $this->info('Starting Regional Data Sync...');
        $depth = $this->option('depth');

        $levels = ['province' => 1, 'city' => 2, 'district' => 3, 'village' => 4];
        $targetLevel = $levels[$depth] ?? 2;

        try {
            // 1. Ensure "Indonesia" Wilayah exists
            // We use '62' as ID for Indonesia based on ISO or just hardcode
            $wilayah = \App\Models\Wilayah::firstOrCreate(
                ['id' => '62'],
                ['name' => 'Indonesia']
            );
            $this->info("Wilayah: {$wilayah->name} (Checked)");

            // 2. Fetch Provinces
            $this->info("Fetching Provinces...");
            $provinces = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->timeout(60)
                ->retry(3, 100)
                ->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                ->json();

            $bar = $this->output->createProgressBar(count($provinces));
            $bar->start();

            foreach ($provinces as $provData) {
                // Use API ID as our ID
                $provinsi = \App\Models\Provinsi::firstOrCreate(
                    ['id' => $provData['id']],
                    ['name' => $provData['name'], 'wilayah_id' => $wilayah->id]
                );

                if ($targetLevel >= 2) {
                    $this->syncCities($provinsi, $provData['id'], $targetLevel);
                }

                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
            $this->info("Sync Completed Successfully!");
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }

    private function syncCities($provinsi, $apiProvId, $targetLevel)
    {
        $cities = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->timeout(60)
            ->retry(3, 100)
            ->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$apiProvId}.json")
            ->json();

        foreach ($cities as $cityData) {
            $kota = \App\Models\Kota::firstOrCreate(
                ['id' => $cityData['id']],
                ['name' => $cityData['name'], 'provinsi_id' => $provinsi->id]
            );

            if ($targetLevel >= 3) {
                $this->syncDistricts($kota, $cityData['id'], $targetLevel);
            }
        }
    }

    private function syncDistricts($kota, $apiCityId, $targetLevel)
    {
        $districts = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->timeout(60)
            ->retry(3, 100)
            ->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$apiCityId}.json")
            ->json();

        foreach ($districts as $distData) {
            $kecamatan = \App\Models\Kecamatan::firstOrCreate(
                ['id' => $distData['id']],
                ['name' => $distData['name'], 'kota_id' => $kota->id]
            );

            if ($targetLevel >= 4) {
                $this->syncVillages($kecamatan, $distData['id']);
            }
        }
    }

    private function syncVillages($kecamatan, $apiDistId)
    {
        $villages = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->timeout(60)
            ->retry(3, 100)
            ->get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$apiDistId}.json")
            ->json();

        foreach ($villages as $villageData) {
            \App\Models\Kelurahan::firstOrCreate(
                ['id' => $villageData['id']],
                ['name' => $villageData['name'], 'kecamatan_id' => $kecamatan->id]
            );
        }
    }
}
