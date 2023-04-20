<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'text',
        'text_short',
        'image',
        'link',
        'status',
        'lang',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
