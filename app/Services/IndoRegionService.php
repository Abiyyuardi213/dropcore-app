<?php

namespace App\Services;

use App\Models\Wilayah;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndoRegionService
{
    protected $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    public function syncWilayah()
    {
        // 1. Ensure "Indonesia" Wilayah exists
        $wilayah = Wilayah::firstOrCreate(
            ['id' => '62'],
            ['negara' => 'Indonesia', 'name' => 'Indonesia', 'status_wilayah' => 1] // Ensure 'name' is present
        );
        return $wilayah;
    }

    public function syncProvinces()
    {
        try {
            $wilayah = $this->syncWilayah();

            $response = Http::withoutVerifying()
                ->timeout(60)
                ->retry(3, 100)
                ->get("{$this->baseUrl}/provinces.json");

            if ($response->successful()) {
                $provinces = $response->json();
                foreach ($provinces as $provData) {
                    Provinsi::firstOrCreate(
                        ['id' => $provData['id']],
                        ['name' => $provData['name'], 'wilayah_id' => $wilayah->id]
                    );
                }
                return count($provinces);
            }
            return 0;
        } catch (\Exception $e) {
            Log::error("Error syncing provinces: " . $e->getMessage());
            throw $e;
        }
    }

    public function syncCities()
    {
        try {
            // Ensure provinces exist, but users should run sync provinces first or we iterate existing
            $provinces = Provinsi::all();
            $count = 0;

            foreach ($provinces as $provinsi) {
                $response = Http::withoutVerifying()
                    ->timeout(30)
                    ->get("{$this->baseUrl}/regencies/{$provinsi->id}.json");

                if ($response->successful()) {
                    $cities = $response->json();
                    foreach ($cities as $cityData) {
                        Kota::firstOrCreate(
                            ['id' => $cityData['id']],
                            ['name' => $cityData['name'], 'provinsi_id' => $provinsi->id]
                        );
                        $count++;
                    }
                }
            }
            return $count;
        } catch (\Exception $e) {
            Log::error("Error syncing cities: " . $e->getMessage());
            throw $e;
        }
    }

    public function syncDistricts()
    {
        // This is heavy, maybe limit or iterate
        // For web request consistency, this might timeout if we do ALL cities.
        // We will try running it. If it times out, we might need a better strategy.
        set_time_limit(300); // 5 minutes

        $cities = Kota::all();
        $count = 0;

        foreach ($cities as $kota) {
            $response = Http::withoutVerifying()
                ->timeout(10) // Short timeout per request
                ->get("{$this->baseUrl}/districts/{$kota->id}.json");

            if ($response->successful()) {
                $districts = $response->json();
                foreach ($districts as $distData) {
                    Kecamatan::firstOrCreate(
                        ['id' => $distData['id']],
                        ['name' => $distData['name'], 'kota_id' => $kota->id]
                    );
                    $count++;
                }
            }
        }
        return $count;
    }

    public function syncVillages()
    {
        // extremely heavy
        set_time_limit(600); // 10 minutes

        $districts = Kecamatan::all();
        $count = 0;

        foreach ($districts as $kecamatan) {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->get("{$this->baseUrl}/villages/{$kecamatan->id}.json");

            if ($response->successful()) {
                $villages = $response->json();
                foreach ($villages as $villageData) {
                    Kelurahan::firstOrCreate(
                        ['id' => $villageData['id']],
                        ['name' => $villageData['name'], 'kecamatan_id' => $kecamatan->id]
                    );
                    $count++;
                }
            }
        }
        return $count;
    }
}
