<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    protected $table = 'papi_answers';
    protected $fillable = ['test_session_id', 'question_number', 'chosen_option'];

    public function testSession()
    {
        return $this->belongsTo(TestSession::class, 'test_session_id');
    }
}