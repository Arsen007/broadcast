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
        return self::where(['slug' => $slug])->first();
    }

    public static function createRtmpServer($key,$ip,$slug)
    {
        $app_conf_path = $_ENV['RTMP_APPS_CONF_PATH'];
        $config_template = sprintf("
application %s{
", $key);
        $config_template .= sprintf("   live on;
    record_unique on;
    record all;
    record_path /tmp/broadcast_channels/%s;
    record_max_size 50000K;",$slug);
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
        $channel_records_path = $_ENV['CHANNEL_RECORDS_PATH'];
        if(!is_dir($channel_records_path)){
            mkdir($channel_records_path);
        }
        if(!is_dir($channel_records_path.$slug)){
            mkdir($channel_records_path.$slug);
            chmod($channel_records_path.$slug,0777);
        }

        file_put_contents($app_conf_path . $key . '.conf', $config_template);
    }

    public static function getLiveStatuses(){
        $channels = self::all();
        $channel_records_path = $_ENV['CHANNEL_RECORDS_PATH'];
        $result = [];
        $last_file_names = [];
        foreach($channels as $channel){
            $files = scandir($channel_records_path.$channel->slug);
            foreach($files as $key => $file_name){
                if($file_name == '.' || $file_name == '..'){
                    unset($files[$key]);
                }
            }
            if(!empty($files)){
                $last_file_names[$channel->id] = end($files);
            }else{
                $last_file_names[$channel->id] = false;
            }
        }
        $size1 = [];
        foreach($last_file_names as $key => $file_name){
            if($file_name){
                $size1[$key] = filesize($channel_records_path.$channel->slug.'/'.$file_name);
            }else{
                $size1[$key] = 0;
            }
        }
        sleep(2);
        foreach($last_file_names as $key => $file_name){
            if($size1[$key] != filesize($channel_records_path.$channel->slug.'/'.$file_name)){
                $result[$key] = true;
            }else{
                $result[$key] = false;
            }
        }
        return $result;
    }
}
