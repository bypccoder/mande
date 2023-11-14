<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname_1',
        'lastname_2',
        'document_type_id',
        'document',
        'phone',
        'email',
        'status_id'
    ];
}
