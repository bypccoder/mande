<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryProduct extends Model
{
    use HasFactory;

    protected $table = 'sub_categories_products';

    protected $fillable = [
        'sub_category_id',
        'product_id'
    ];

}
