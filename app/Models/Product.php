<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'im', 'name', 'free_shipping', 'description', 'price'
    ];
}
