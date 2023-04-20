<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'faq_categories';

    protected $fillable = [
        'title',
        'status',
        'lang',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
