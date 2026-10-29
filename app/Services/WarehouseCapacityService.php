<?php

namespace App\Services;

use App\Models\{Gudang, Stok};
use Illuminate\Support\Collection;

class WarehouseCapacityService
{
    public function warehouses(): Collection
    {
        $stocks = Stok::with('produk.uom')->where('quantity', '>', 0)->get()->groupBy('gudang_id');
        return Gudang::with(['areas.raks'])->orderBy('nama_gudang')->get()->map(function ($warehouse) use ($stocks) {
            $warehouseStocks = $stocks->get($warehouse->id, collect());
            $areas = $warehouse->areas->sortBy('kode_area')->map(function ($area) use ($warehouseStocks) {
                $areaStocks = $warehouseStocks->where('area_id', $area->id);
                $racks = $area->raks->where('gudang_id', $area->gudang_id)->sortBy('kode_rak')->map(fn ($rack) => [
                    'id' => $rack->id, 'code' => $rack->kode_rak, 'position' => $rack->posisi,
                    'type' => $rack->jenis_rak, 'dimensions' => $rack->dimensi, 'material' => $rack->bahan_rak, 'description' => $rack->keterangan,
                    'active' => (bool) $rack->rak_status,
                    'capacity' => $this->measure($rack->kapasitas_max, $areaStocks->where('rak_id', $rack->id)),
                ])->values();
                return [
                    'id' => $area->id, 'name' => $area->nama_area, 'code' => $area->kode_area,
                    'type' => $area->jenis_area, 'pic' => $area->pic, 'description' => $area->keterangan,
                    'temperature' => $area->suhu, 'humidity' => $area->kelembaban,
                    'active' => (bool) $area->area_status, 'racks' => $racks,
                    'unassigned' => $areaStocks->filter(fn ($stock) => !$racks->contains('id', $stock->rak_id))->sum('quantity'),
                    'capacity' => $this->measure($area->kapasitas_area, $areaStocks),
                ];
            })->values();
            return [
                'id' => $warehouse->id, 'name' => $warehouse->nama_gudang, 'code' => $warehouse->kode_gudang,
                'location' => $warehouse->lokasi, 'active' => (bool) $warehouse->gudang_status,
                'type' => $warehouse->jenis_gudang, 'pic' => $warehouse->pic, 'description' => $warehouse->deskripsi,
                'capacity' => $this->measure($warehouse->kapasitas, $warehouseStocks), 'areas' => $areas,
                'unassigned' => $warehouseStocks->filter(fn ($stock) => !$areas->contains('id', $stock->area_id))->sum('quantity'),
            ];
        });
    }

    private function number(string $value): ?float
    {
        $value = trim($value);
        if (preg_match('/^\d{1,3}(?:\.\d{3})+(?:,\d+)?$/', $value)) $value = str_replace(['.', ','], ['', '.'], $value);
        elseif (preg_match('/^\d+(?:[.,]\d+)?$/', $value)) $value = str_replace(',', '.', $value);
        else return null;
        $number = (float) $value;
        return is_finite($number) && $number > 0 ? $number : null;
    }

    private function volume(?string $dimensions): ?float
    {
        $dimensions = mb_strtolower(trim($dimensions ?? ''));
        if (!preg_match('/^([\d.,]+)\s*[x×]\s*([\d.,]+)\s*[x×]\s*([\d.,]+)\s*(mm|cm|m)$/u', $dimensions, $matches)) return null;
        $values = array_map(fn ($value) => $this->number($value), array_slice($matches, 1, 3));
        if (in_array(null, $values, true)) return null;
        $scale = ['mm' => .001, 'cm' => .01, 'm' => 1][$matches[4]];
        return array_product($values) * $scale ** 3;
    }

    private function unit(string $unit): string
    {
        $unit = mb_strtolower(trim($unit));
        return match ($unit) {
            'pcs', 'pc', 'piece', 'pieces', 'buah' => 'pcs',
            'pallet', 'palet', 'pallets' => 'pallet',
            'roll', 'rol', 'gulung' => 'roll',
            'box', 'dus', 'karton' => 'box',
            'meter', 'metre', 'm' => 'meter',
            default => $unit,
        };
    }

    public function measure(?string $rawCapacity, Collection $stocks): array
    {
        $stocks = $stocks->filter(fn ($stock) => $stock->quantity > 0);
        $result = [
            'raw' => $rawCapacity, 'quantity' => $stocks->sum('quantity'), 'products' => $stocks->pluck('produk_id')->unique()->count(),
            'known' => false, 'status' => 'unknown', 'color' => 'secondary', 'label' => 'Belum terukur',
            'used' => null, 'limit' => null, 'unit' => '', 'percent' => null, 'bar' => 0, 'remaining' => null, 'excess' => 0,
            'message' => 'Kapasitas belum diisi. Atur kapasitas pada data lokasi.',
        ];
        $capacity = mb_strtolower(trim($rawCapacity ?? ''));
        if ($capacity === '') return $result;
        if (!preg_match('/^([\d.,]+)\s*([\p{L}³3]*)$/u', $capacity, $matches) || !($limit = $this->number($matches[1]))) {
            $result['message'] = 'Format kapasitas belum dapat dihitung. Gunakan satu nilai, misalnya 1000 kg, 50 m3, atau 200 pcs.';
            return $result;
        }
        $unit = $matches[2]; $mode = 'uom';
        if (in_array($unit, ['kg','kilogram'], true)) { $unit = 'kg'; $mode = 'weight'; }
        elseif (in_array($unit, ['ton','tonne'], true)) { $limit *= 1000; $unit = 'kg'; $mode = 'weight'; }
        elseif (in_array($unit, ['m3','m³'], true)) { $unit = 'm³'; $mode = 'volume'; }
        elseif (in_array($unit, ['', 'unit', 'item', 'items'], true)) { $unit = 'unit stok'; $mode = 'count'; }
        else $unit = $this->unit($unit);
        if ($mode === 'uom' && !in_array($unit, ['pcs','pallet','roll','box','meter'], true)) {
            $result['message'] = 'Satuan kapasitas belum didukung. Gunakan kg, ton, m3, pcs, pallet, roll, box, atau meter.';
            return $result;
        }
        $result['limit'] = $limit; $result['unit'] = $unit;
        $used = 0; $missing = 0;
        foreach ($stocks as $stock) {
            $product = $stock->produk;
            $perUnit = match ($mode) {
                'weight' => $product && (float) $product->weight > 0 ? (float) $product->weight : ($product && (float) $product->berat > 0 ? (float) $product->berat : null),
                'volume' => $this->volume($product?->dimensi),
                'count' => 1,
                default => $product && $this->unit($product->uom?->name ?? '') === $unit ? 1 : null,
            };
            if ($perUnit === null) { $missing++; continue; }
            $used += $perUnit * $stock->quantity;
        }
        if ($missing) {
            $result['message'] = match ($mode) {
                'weight' => 'Berat per satuan stok belum tersedia pada '.$missing.' baris stok.',
                'volume' => 'Dimensi per satuan stok belum valid pada '.$missing.' baris stok. Gunakan PxLxT dengan satuan cm, mm, atau m.',
                default => 'Satuan barang pada '.$missing.' baris stok tidak sesuai dengan satuan kapasitas.',
            };
            return $result;
        }
        $percent = $used / $limit * 100;
        [$status, $color, $label] = match (true) {
            $percent >= 100 => ['full', 'danger', $percent > 100 ? 'Melebihi kapasitas' : 'Penuh'],
            $percent >= 80 => ['near', 'warning', 'Hampir penuh'],
            $percent >= 60 => ['moderate', 'info', 'Terisi'],
            default => ['free', 'success', 'Lega'],
        };
        return array_merge($result, [
            'known' => true, 'status' => $status, 'color' => $color, 'label' => $label,
            'used' => $used, 'percent' => round($percent, 1), 'bar' => min(100, round($percent, 1)),
            'remaining' => max(0, $limit - $used), 'excess' => max(0, $used - $limit),
            'message' => match ($mode) { 'count' => 'Berdasarkan jumlah kuantitas stok, bukan luas atau volume fisik.', 'weight' => 'Estimasi berat barang per satuan stok.', 'volume' => 'Estimasi volume barang; belum memperhitungkan ruang antarbarang.', default => 'Berdasarkan kuantitas dengan satuan barang yang sesuai.' },
        ]);
    }
}
