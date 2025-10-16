<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Sale extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'total_price', 'sale_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }


    public function notes()
    {
        return $this->morphMany(Notes::class, 'notable');
    }


    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_price, 2) . ' BDT';
    }

    // protected static function booted()
    // {
    //     static::created(function ($sale) {
    //         Cache::tags('sales')->flush();
    //     });

    //     static::updated(function ($sale) {
    //         Cache::tags('sales')->flush();
    //     });

    //     static::deleted(function ($sale) {
    //         Cache::tags('sales')->flush();
    //     });
    // }
}