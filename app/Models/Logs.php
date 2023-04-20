<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'logs';

    protected $fillable = [
        'user',
        'category',
        'icon',
        'text',
        'lang',
        'created_at',
    ];
}
