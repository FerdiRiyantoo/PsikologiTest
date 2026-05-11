<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'tempat_lahir',
        'tanggal_lahir',
        'usia',
        'alamat',
        'pendidikan_terakhir',
        'jurusan',
        'posisi_jabatan_terakhir',
        'tanggal_tes',
        'jenis_tes',
        'status',
        'access_code',
        'approved_at','magic_token', 'magic_token_expires_at',
    ];

    protected $casts = [
        'magic_token_expires_at' => 'datetime',
        'approved_at'            => 'datetime',
    ];

    public function testSession()
    {
        return $this->hasOne(TestSession::class);
    }
}