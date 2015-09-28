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
    play /tmp/broadcast_channels/%s;
    record_max_size 50000K;",$key);
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
        if(!is_dir($channel_records_path.$key)){
            mkdir($channel_records_path.$key);
            chmod($channel_records_path.$key,0777);
        }

        file_put_contents($app_conf_path . $key . '.conf', $config_template);
    }

    public static function removeRtmpServer($key)
    {
        $channel_records_path = $_ENV['CHANNEL_RECORDS_PATH'];
        $app_conf_path = $_ENV['RTMP_APPS_CONF_PATH'];
        if(is_dir($channel_records_path.$key)){
            File::deleteDirectory($channel_records_path.$key);
        }
        if(File::exists($app_conf_path . $key . '.conf')){
            File::delete($app_conf_path . $key . '.conf');
        }

    }

    public static function getLiveStatuses(){
        $channels = self::all();
        $channel_records_path = $_ENV['CHANNEL_RECORDS_PATH'];
        $result = [];
        $last_file_names = [];
        $i = 0;
        foreach($channels as $channel){
            $files = scandir($channel_records_path.$channel->key);
            foreach($files as $key => $file_name){
                if($file_name == '.' || $file_name == '..'){
                    unset($files[$key]);
                }
            }
            if(!empty($files)){
                $last_file_names[$i] = end($files);
            }else{
                $last_file_names[$i] = false;
            }
            $i++;
        }
        foreach($last_file_names as $key => $file_name){
            if(strlen($file_name) > 0){
                $result[$channels->get($key)->id] = (time() - filemtime($channel_records_path.$channels->get($key)->key.'/'.$file_name)) < 5;
            }else{
                $result[$channels->get($key)->id] = false;
            }
        }


        return $result;
    }

    public static function getChannelRecordsPath($slug){
        $channel_records_path = $_ENV['CHANNEL_RECORDS_PATH'];
        $channel = self::where(['slug' => $slug])->first();
        return $channel_records_path.$channel->key;
    }

    public static function getChannelRecords($slug){
        $records_path = self::getChannelRecordsPath($slug);
        $result = [];
        if(is_dir($records_path)){
            $files = scandir($records_path);
            foreach($files as $file_name){
                if($file_name == '.' || $file_name == '..'){
                    continue;
                }
                $timestump = filemtime($records_path.'/'.$file_name);
                $result[] = [
                    't' => $timestump,
                    'name' => $file_name,
                    'full_path' => $records_path.'/'.$file_name
                ];
            }
        }
        return $result;
    }
}
