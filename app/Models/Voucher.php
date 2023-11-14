<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;


    protected $table = 'vouchers';

    protected $fillable = [
        'voucher_type_id',
        'order_id',
        'charge_code',
        'document',
        'client',
        'address',
        'phone',
        'email',
        'payment_condition',
        'vat',
        'subtotal',
        'total',
        'cash',
        'change',
        'status_id',
        'path_xml',
        'path_pdf',
        'charge_code_note_credit',
        'data_path_note_credit',
        'is_note_credit'
    ];
}
