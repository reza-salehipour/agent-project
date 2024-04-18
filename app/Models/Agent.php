<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'title',
        'first_name',
        'initial',
        'last_name',
    ];


}
