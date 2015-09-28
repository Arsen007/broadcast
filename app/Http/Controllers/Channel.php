<?php

namespace App\Http\Controllers;

use App\Channels;
use App\Http\Requests\CreateNewChannelRequest;
use App\Http\Requests\Request;
use App\Http\Requests\UpdateChannelRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Tests\Config\EnvParametersResourceTest;

class Channel extends Controller
{
    protected $layout = 'template.blade';

    public function index(){
        $model = new Channels();
        $channels = $model->all();
        return view('channel.list',[
            'channels' => $channels
        ]);
    }

    public function view_channel($channel_slug){
        $channel = Channels::getChannel($channel_slug);
        $records = Channels::getChannelRecords($channel_slug);

        return view('channel.view',[
            'channel' => $channel,
            'records' => $records
        ]);
    }

    public function view_video($channel_slug,$file_name){
        $channel = Channels::getChannel($channel_slug);
        return view('channel.view_video',[
            'channel' => $channel,
            'file_name' => $file_name,
        ]);
    }

    public function create_channel(){
        return view('channel.create',[
//            'channel' => $channel
        ]);
    }

    public function update_channel($channel_slug){
        $channel = Channels::getChannel($channel_slug);
        return view('channel.update',[
            'channel' => $channel
        ]);
    }

    public function store(CreateNewChannelRequest $request){
        $requestAttr = $request->all();
        $requestAttr['key']= Str::random(10);
        $ip = $requestAttr['allow_all'] == 0?$requestAttr['allowed_ips']:false;
        Channels::create($requestAttr);
        Channels::createRtmpServer($requestAttr['key'],$ip,$requestAttr['slug']);
        shell_exec('./php_root');

        return redirect(url('/'));

    }
    public function update(UpdateChannelRequest $request){
        $requestAttr = $request->all();
        $ip = $requestAttr['allow_all'] == 0?$requestAttr['allowed_ips']:false;
        $channel = Channels::find($requestAttr['id']);
            $channel->title = $requestAttr['title'];
            $channel->slug = $requestAttr['slug'];
            $channel->allow_all = $requestAttr['allow_all'];
            $channel->allowed_ips = $requestAttr['allowed_ips'];
            $channel->save();
        Channels::createRtmpServer($channel->key,$ip,$requestAttr['slug']);
        shell_exec('./php_root');

        return redirect(url('/'));

    }


    public function delete($id){
        $channel = Channels::find($id);
        Channels::destroy($id);
        Channels::removeRtmpServer($channel->key);
        shell_exec('./php_root');
        return redirect(url('/'));

    }

    public function getStatusesAjax(){
        $statuses = Channels::getLiveStatuses();
        return json_encode($statuses);
    }
}