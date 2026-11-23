<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beasiswa extends Model
{
    protected $table = 'beasiswa';

    // Tabel beasiswa tidak punya kolom created_at dan updated_at
    public $timestamps = false;

    protected $fillable = [
        'Nama',
        'Deskripsi',
        'Dibuka',
        'Ditutup',
        'JumlahKursi',
        'SudahMasuk',
        'status',
        'SyaratUtama',
        'Jenjang',
    ];

    protected function casts(): array
    {
        return [
            'Dibuka'      => 'datetime',
            'Ditutup'     => 'datetime',
            'JumlahKursi' => 'integer',
            'SudahMasuk'  => 'integer',
        ];
    }

    
     //Semua pengajuan yang masuk ke beasiswa ini.
     
    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'id_beasiswa');
    }

    
    //Sisa kuota = jumlah kursi dikurangi yang sudah diterima.
    
    public function sisaKuota(): int
    {
        return max(0, $this->JumlahKursi - $this->SudahMasuk);
    }

    
    //Apakah beasiswa ini masih bisa dilamar saat ini.
    
    public function bisaDilamar(): bool
    {
        return $this->status === 'dibuka'
            && $this->sisaKuota() > 0
            && now()->between($this->Dibuka, $this->Ditutup);
    }
}