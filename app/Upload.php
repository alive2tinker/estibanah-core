<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'link',
        'name',
        'type'
    ];

    public function formResponse()
    {
        return $this->belongsTo(FormResponse::class);
    }
}
