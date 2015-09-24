<?php

namespace App;

use App\library\phpJSO;
use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
    protected $table = 'channels';

    public static function getChannel($slug){
        return self::where([])->first();
    }
}
