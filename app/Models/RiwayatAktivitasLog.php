<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RiwayatAktivitasLog extends Model
{
    protected $table = 'activity_logs';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'module',
        'action',
        'description',
        'user_id'
    ];

    protected static function booted()
    {
        static::creating(function ($log) {
            if (!$log->id) {
                $log->id = (string) Str::uuid();
            }
        });
    }

    public static function add($module, $action, $description = null, $userId = null)
    {
        return self::create([
            'module'      => $module,
            'action'      => $action,
            'description' => $description,
            'user_id'     => $userId ?? optional(Auth::user())->id,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
