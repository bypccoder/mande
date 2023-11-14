<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $table = 'orders';

    protected $fillable = [
        'person_id',
        'date_order',
        'amount',
        'form_method_id',
        'payment_method_id',
        'invoice_type_id',
        'date_start_credit',
        'date_end_credit',
        'status_id',
        'user_id',
        'type_order'
    ];
}
