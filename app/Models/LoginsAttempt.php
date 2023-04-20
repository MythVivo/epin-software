<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginsAttempt extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'logins_attempt';

    protected $fillable = [
        'user',
        'chanel',
        'failed',
        'created_at',
    ];
}
