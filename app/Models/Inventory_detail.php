<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory_detail extends Model
{
    use HasFactory;

    protected $table = 'inventories_details';

    protected $fillable = [
        'inventory_id',
        'product_id',
        'quantity',
        'purchase_price',
        'sale_price',
        'subtotal'        
    ];
}
