<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesPackages extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    protected $table = 'games_packages';

    protected $fillable = [
        'games_titles',
        'title',
        'text',
        'image',
        'price',
        'discount_type',
        'discount_amount',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
