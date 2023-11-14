<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'quantity',
        'voucherType',
        'voucherSerial',
        'voucherNumber',
        'voucherTax',
        'person_id'
    ];

}
