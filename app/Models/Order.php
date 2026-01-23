<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'order_number',
        'tax_base',
        'tax_amount',
        'total_price',
        'status',
        'tax_invoice_number',
        'shipping_address',
        'shipping_provider',
        'tracking_number',
        'shipped_at',
        'payment_status',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
