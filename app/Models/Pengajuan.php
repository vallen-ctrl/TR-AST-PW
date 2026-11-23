<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengajuan extends Model
{
    protected $table = 'memiliki';

    // Tabel memiliki tidak punya kolom created_at dan updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_beasiswa',
        'NamaUniversitas',
        'Semester',
        'ipk',
        'AlamatUniv',
        'UploadFilePath',
        'NamaFile',
        'TanggalDaftar',
        'StatusDiterima',
    ];

    protected function casts(): array
    {
        return [
            'Semester'      => 'integer',
            'ipk'           => 'float',
            'TanggalDaftar' => 'datetime',
        ];
    }

    
    //Pelamar yang mengajukan.
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    
    //Beasiswa yang dilamar.
    
    public function beasiswa(): BelongsTo
    {
        return $this->belongsTo(Beasiswa::class, 'id_beasiswa');
    }
}