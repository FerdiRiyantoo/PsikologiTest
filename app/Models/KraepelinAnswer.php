<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KraepelinAnswer extends Model
{
    protected $fillable = [
        'test_session_id', 'column_number', 'row_number',
        'digit_a', 'digit_b', 'user_answer', 'is_correct',
    ];

    public function testSession()
    {
        return $this->belongsTo(TestSession::class);
    }
}
?>