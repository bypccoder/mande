<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class No_Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id'
    ];
}
