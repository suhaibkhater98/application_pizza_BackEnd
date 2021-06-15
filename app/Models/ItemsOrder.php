<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
    ];
}
