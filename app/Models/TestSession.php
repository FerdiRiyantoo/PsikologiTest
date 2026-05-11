<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestSession extends Model
{
    protected $fillable = [
        'access_request_id', 'status', 'started_at', 'completed_at'
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function accessRequest()
    {
        return $this->belongsTo(AccessRequest::class);
    }

    // Relasi ke hasil PAPI ← ini yang belum ada
    public function result()
    {
        return $this->hasOne(PapiResult::class);
    }

    // Relasi ke jawaban PAPI
    public function answers()
    {
        return $this->hasMany(TestAnswer::class);
    }

    // Relasi ke hasil Kraepelin
    public function kraepelinResult()
    {
        return $this->hasOne(KraepelinResult::class);
    }

    // Relasi ke jawaban Kraepelin
    public function kraepelinAnswers()
    {
        return $this->hasMany(KraepelinAnswer::class);
    }
}