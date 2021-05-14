<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesTitles extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    protected $table = 'games_titles';

    protected $fillable = [
        'game',
        'title',
        'text',
        'type',
        'image',
        'link',
        'status',
        'lang',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
