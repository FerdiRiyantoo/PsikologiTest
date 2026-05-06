<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KraepelinResult extends Model
{
    protected $table = 'kraepelin_results';
    protected $fillable = [
        'test_session_id',
        'pace_score', 'accuracy_score',
        'endurance_score', 'stability_score',
        'total_answered', 'total_correct', 'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    public function testSession()
    {
        return $this->belongsTo(TestSession::class);
    }
}