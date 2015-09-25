<?php

namespace App;

use App\library\phpJSO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Channels extends Model
{
    protected $table = 'channels';
    protected $fillable = ['title', 'slug','key','allowed_ips','allow_all'];

    public static function getChannel($slug)
    {
        return self::where([])->first();
    }

    public static function createRtmpServer($key,$ip)
    {
        $app_conf_path = $_ENV['RTMP_APPS_CONF_PATH'];
        $config_template = sprintf("
application %s{
", $key);
        $config_template .= "   live on;
    #record_unique on;
    record all;
    record_path /tmp;
    record_max_size 1K;";
        if($ip){
            $config_template .= sprintf("
    deny publish all;
    allow publish %s;
            ", $ip);
        }else{
            $config_template .= "
    allow publish all;
            ";
        }
        $config_template .= "
}";

        file_put_contents($app_conf_path . $key . '.conf', $config_template);
    }
}
