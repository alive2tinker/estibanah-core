<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'value',
        'question_id',
        'form_response_id'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function formResponse()
    {
        return $this->belongsTo(FormResponse::class);
    }
}
