<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $table = 'istatistik';

    protected $fillable = [
        'ip',
        'date',
        'page',
        'device',
        'browser',
        'ms',
        'tekil',
    ];
}
