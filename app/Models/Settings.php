<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Settings extends Model

{

    use HasFactory;



    protected $table = 'settings';



    protected $fillable = [

        'site_name',

        'description',

        'footer_text',

        'meta',

        'analytics',

        'tel_1',

        'tel_2',

        'email_1',

        'email_2',

        'facebook',

        'twitter',

        'youtube',

        'linkedin',

        'instagram',

        'twitch',

        'address',

        'address_iframe',

        'robots',

        'logo',

        'favicon'

        ];

}

