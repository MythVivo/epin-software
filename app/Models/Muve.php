<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Muve extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = false;


    protected $table = 'muve_games';

    protected $fillable = [
        'muveId',
        'muveCode',
        'muveCountry',
        'muveCurrency',
        'muvePrice',
        'title',
        'steamId',
        'supLang',
        'winGer',
        'macGer',
        'linuxGer',
        'winSup',
        'macSup',
        'linuxSup',
        'developers',
        'categories',
        'metaScore',
        'metaLink',
        'releaseDate',
        'description',
        'image',
        'background',
        'images',
        'videos',
        'link',
        'status',
        'lang',
        'sira',
        'created_at',
        'updated_at',
        'deleted_at',
        'alis',
    ];

}
