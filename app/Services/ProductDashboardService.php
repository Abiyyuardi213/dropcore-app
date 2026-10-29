<?php

namespace App\Services;

use App\Models\{Category, Products, Stok};
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ProductDashboardService
{
    public function data(int $days): array
    {
        $products = Products::with(['category','uom'])->withSum('stok as stock_quantity','quantity')->orderBy('name')->get();
        foreach ($products as $product) {
            $qty = (int) $product->stock_quantity;
            $product->stock_quantity = $qty;
            $product->stock_status = match (true) {
                $qty <= 0 => 'empty',
                (float) $product->min_stock > 0 && $qty <= (float) $product->min_stock => 'low',
                (float) $product->max_stock > 0 && $qty > (float) $product->max_stock => 'over',
                default => 'normal',
            };
        }
        $stocked = $products->where('stock_quantity','>',0);
        $priced = $stocked->filter(fn ($product) => $product->purchase_price !== null && (float) $product->purchase_price >= 0);
        $summary = [
            'products'=>$products->count(), 'stocked'=>$stocked->count(),
            'empty'=>$products->where('stock_status','empty')->count(), 'low'=>$products->where('stock_status','low')->count(),
            'over'=>$products->where('stock_status','over')->count(), 'normal'=>$products->where('stock_status','normal')->count(),
            'purchase_value'=>$priced->sum(fn ($product) => (int) round((float) $product->purchase_price * 100) * $product->stock_quantity) / 100,
            'valued_products'=>$priced->count(), 'missing_cost'=>$stocked->count() - $priced->count(),
            'missing_min'=>$products->filter(fn ($product) => (float) $product->min_stock <= 0)->count(),
            'missing_sku'=>$products->filter(fn ($product) => trim($product->sku ?? '') === '')->count(),
            'negative'=>$products->where('stock_quantity','<',0)->count(),
            'invalid_limits'=>$products->filter(fn ($product) => (float) $product->max_stock > 0 && (float) $product->min_stock > (float) $product->max_stock)->count(),
            'unlocated'=>Stok::where('quantity','>',0)->where(fn ($query) => $query->whereNull('gudang_id')->orWhereNull('area_id')->orWhereNull('rak_id'))->count(),
        ];
        $stockByUnit = $stocked->groupBy(fn ($product) => $product->uom->name ?? 'Tanpa satuan')->map(fn ($group, $unit) => ['unit'=>$unit,'quantity'=>$group->sum('stock_quantity'),'products'=>$group->count()])->values();
        $categories = Category::orderBy('category_name')->get()->map(function ($category) use ($products) {
            $group = $products->where('category_id',$category->id);
            return ['name'=>$category->category_name,'products'=>$group->count(),'stocked'=>$group->where('stock_quantity','>',0)->count()];
        });
        $uncategorized = $products->filter(fn ($product) => !$product->category);
        if ($uncategorized->isNotEmpty()) $categories->push(['name'=>'Tanpa kategori','products'=>$uncategorized->count(),'stocked'=>$uncategorized->where('stock_quantity','>',0)->count()]);
        $stockAlerts = $products->filter(fn ($product) => in_array($product->stock_status,['empty','low']))->sortBy(fn ($product) => [$product->stock_status === 'empty' ? 0 : 1, $product->stock_quantity - (float) $product->min_stock])->take(10)->values();
        $overstock = $products->where('stock_status','over')->sortByDesc(fn ($product) => $product->stock_quantity - (float) $product->max_stock)->take(5)->values();

        $end = CarbonImmutable::now('Asia/Jakarta')->startOfDay(); $start = $end->subDays($days - 1);
        $incoming = $this->movements('penerimaan', $start, $end);
        $outgoing = $this->movements('pengeluaran', $start, $end);
        $chart = ['labels'=>[], 'incoming'=>[], 'outgoing'=>[]];
        $inByDay = $incoming->groupBy('date'); $outByDay = $outgoing->groupBy('date');
        for ($date = $start; $date->lte($end); $date = $date->addDay()) {
            $key = $date->toDateString();
            $chart['labels'][] = $date->format('d/m');
            $chart['incoming'][] = $inByDay->get($key,collect())->sum('quantity');
            $chart['outgoing'][] = $outByDay->get($key,collect())->sum('quantity');
        }
        $movements = [
            'incoming_quantity'=>$incoming->sum('quantity'),'outgoing_quantity'=>$outgoing->sum('quantity'),
            'incoming_count'=>$incoming->count(),'outgoing_count'=>$outgoing->count(),
            'start'=>$start, 'end'=>$end, 'days'=>$days,
        ];
        $recentDocuments = $incoming->concat($outgoing)->sortByDesc('date')->take(6)->values();
        return compact('summary','stockByUnit','categories','stockAlerts','overstock','chart','movements','recentDocuments');
    }

    private function movements(string $type, CarbonImmutable $start, CarbonImmutable $end)
    {
        $document = $type.'_barang'; $details = $document.'_detail';
        return DB::table($document.' as doc')
            ->join($details.' as detail','detail.'.$type.'_id','=','doc.id')
            ->where('doc.status','completed')->whereDate('doc.tanggal_'.$type,'>=',$start->toDateString())->whereDate('doc.tanggal_'.$type,'<=',$end->toDateString())
            ->select('doc.id','doc.no_'.$type.' as number','doc.tanggal_'.$type.' as date')
            ->selectRaw('SUM(detail.qty) as quantity')
            ->groupBy('doc.id','doc.no_'.$type,'doc.tanggal_'.$type)
            ->orderByDesc('doc.tanggal_'.$type)->orderBy('doc.id')->get()
            ->map(fn ($row) => ['id'=>$row->id,'number'=>$row->number,'date'=>CarbonImmutable::parse($row->date)->toDateString(),'quantity'=>(int)$row->quantity,'type'=>$type]);
    }
}
