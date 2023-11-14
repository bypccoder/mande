<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboProduct extends Model
{
    use HasFactory;

    protected $table = 'combos_products';

    protected $fillable = [
        'combo_id',
        'product_id',
    ];

}
