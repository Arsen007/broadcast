<?php

namespace App;

use App\library\phpJSO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Channels extends Model
{
    protected $table = 'channels';
    protected $fillable = ['title', 'slug'];

    public static function getChannel($slug)
    {
        return self::where([])->first();
    }

    public static function createRtmpServer($slug)
    {
        $app_conf_path = $_ENV['RTMP_APPS_CONF_PATH'];
        $ips = "127.0.0.1";
        $config_template = sprintf("
application %s{
", $slug);
        $config_template .= "   live on;
    #record_unique on;
    record all;
    record_path /tmp;
    record_max_size 1K;
    deny publish all;";
        if($ips){
            $config_template .= sprintf("
    allow publish %s;
            ", $ips);

        }
        $config_template .= "
}";

        file_put_contents($app_conf_path . $slug . '.conf', $config_template);
    }
}
