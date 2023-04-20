<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesPackagesTrade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    protected $table = 'games_packages_trade';

    protected $fillable = [
        'games_titles','indirim',
        'title',
        'description',
        'image',
        'alis_fiyat',
        'satis_fiyat',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
