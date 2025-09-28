<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $fillable = ['content'];

    public function notable()
    {
        return $this->morphTo();
    }
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = ucfirst($value);
    }
}
