<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'users';

    protected $fillable = [
        'nip',
        'name',
        'nik',
        'username',
        'email',
        'no_telepon',
        'alamat',
        'password',
        'role_id',
        'divisi_id',
        'jabatan_id',
        'tanggal_bergabung',
        'jenis_kelamin',
        'status_kepegawaian',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_bergabung' => 'date',
    ];

    // ... (casts and booted remain same, skipped for brevity in replacement unless needing change)

    // Boot method for UUID
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Custom wrappers methods
    public static function createPengguna($data)
    {
        return self::create($data);
    }

    public function updatePengguna($data)
    {
        return $this->update($data);
    }

    public function deletePengguna()
    {
        return $this->delete();
    }

    // Add relations
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
