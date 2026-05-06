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

    public function answers()
    {
        return $this->hasMany(TestAnswer::class);
    }

    public function PapiResult()
    {
        // Parameter: (NamaModel, 'nama_kolom_foreign_key', 'nama_kolom_primary_key')
        return $this->hasOne(PapiResult::class, 'test_session_id', 'id');
    }

    public function KraepelinResult()
    {
        return $this->hasOne(KraepelinResult::class, 'test_session_id', 'id');
    }
}