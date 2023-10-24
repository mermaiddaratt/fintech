<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'order_id',
        'price',
        'quantity'
    ];

    public function product() {
        return $this->belongsTo(Products::class);
    }
}
