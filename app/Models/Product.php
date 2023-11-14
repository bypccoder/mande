<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    
    protected $table = 'products';

    protected $fillable = [
        'sub_category_id',
        'name',
        'description',
        'cover_image',
        'buy_price',
        'sales_price',
        'product_type_id',
        'product_enable',
        'code'
    ];
}
