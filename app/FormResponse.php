<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    protected $fillable = [
        'form_id',
        'user_id'
    ];

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
