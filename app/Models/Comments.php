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
        'status', 'oyun',
        'lang',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public static function epin($id = 0)
    {
        $result = self::select('comments.*', "users.name as user_name",  'users.id as user_id')
            ->join('users', 'users.id', '=', 'comments.user')
            ->whereNull('comments.deleted_at')
            ->where('comments.status', '1')
            ->where('comments.oyun', $id)
            ->orderBy('comments.created_at', 'desc')
            ->get();
        return $result;
    }
}
