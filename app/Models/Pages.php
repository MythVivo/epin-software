<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'static_pages';

    protected $fillable = [
        'title',
        'text',
        'link',
        'status',
        'lang',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
