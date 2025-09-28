<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use  SoftDeletes;
    protected $fillable = ['name', 'price', 'description', 'added_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }


    public function notes()
    {
        return $this->morphMany(Notes::class, 'noteable');
    }
}
