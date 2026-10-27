<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class KasirSale extends Model
{
    protected $table = 'kasir_sales';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = ['sale_date' => 'date'];
    public function items() { return $this->hasMany(KasirSaleItem::class, 'sale_id'); }
    public function outgoing() { return $this->belongsTo(PengeluaranBarang::class, 'pengeluaran_id'); }
    public function account() { return $this->belongsTo(SumberKeuangan::class, 'sumber_id'); }
    public function scopeCompleted($query) { return $query->whereHas('outgoing', fn ($q) => $q->where('status', 'completed')); }
}
