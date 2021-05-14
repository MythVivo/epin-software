<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'comments';

    protected $fillable = [
        'user',
        'text',
        'status',
        'lang',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
