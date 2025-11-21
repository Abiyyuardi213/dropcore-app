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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'tanggal_bergabung' => 'date',
            'password'          => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->id) {
                $user->id = (string) Str::uuid();
            }

            if (!$user->nip) {
                $user->nip = self::generateNIP();
            }
        });

        static::created(function ($user) {
            RiwayatAktivitasLog::add(
                'user',
                'create',
                "Menambah user {$user->name} ({$user->username})",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($user) {
            RiwayatAktivitasLog::add(
                'user',
                'update',
                "Mengubah user {$user->name} ({$user->username})",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($user) {
            RiwayatAktivitasLog::add(
                'user',
                'delete',
                "Menghapus user {$user->name} ({$user->username})",
                optional(Auth::user())->id
            );
        });
    }

    public static function generateNIP()
    {
        $kode_inti = "00";

        $bulan = now()->format('m');
        $tahun = now()->format('y');

        $jumlahPegawai = self::count() + 1;
        $nomor_urut = str_pad($jumlahPegawai, 4, '0', STR_PAD_LEFT); // 0001

        return $kode_inti . $bulan . $tahun . $nomor_urut;
    }

    public static function createPengguna($data)
    {
        return self::create([
            'nip'               => $data['nip'] ?? self::generateNIP(),
            'name'              => $data['name'],
            'nik'               => $data['nik'],
            'username'          => $data['username'],
            'email'             => $data['email'],
            'no_telepon'        => $data['no_telepon'] ?? null,
            'alamat'            => $data['alamat'] ?? null,
            'password'          => $data['password'],
            'role_id'           => $data['role_id'],
            'divisi_id'         => $data['divisi_id'] ?? null,
            'jabatan_id'        => $data['jabatan_id'] ?? null,
            'tanggal_bergabung' => $data['tanggal_bergabung'] ?? null,
            'jenis_kelamin'     => $data['jenis_kelamin'] ?? null,
            'status_kepegawaian'=> $data['status_kepegawaian'] ?? 'aktif',
            'profile_picture'   => $data['profile_picture'] ?? null,
        ]);
    }

    public function updatePengguna($data)
    {
        return $this->update([
            'name'              => $data['name'] ?? $this->name,
            'nik'               => $data['nik'] ?? $this->nik,
            'username'          => $data['username'] ?? $this->username,
            'email'             => $data['email'] ?? $this->email,
            'no_telepon'        => $data['no_telepon'] ?? $this->no_telepon,
            'alamat'            => $data['alamat'] ?? $this->alamat,
            'profile_picture'   => $data['profile_picture'] ?? $this->profile_picture,
            'role_id'           => $data['role_id'] ?? $this->role_id,
            'divisi_id'         => $data['divisi_id'] ?? $this->divisi_id,
            'jabatan_id'        => $data['jabatan_id'] ?? $this->jabatan_id,
            'tanggal_bergabung' => $data['tanggal_bergabung'] ?? $this->tanggal_bergabung,
            'jenis_kelamin'     => $data['jenis_kelamin'] ?? $this->jenis_kelamin,
            'status_kepegawaian'=> $data['status_kepegawaian'] ?? $this->status_kepegawaian,
        ]);
    }

    public function deletePengguna()
    {
        return $this->delete();
    }

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

    public function getAuthIdentifierName()
    {
        return 'username';
    }
}

