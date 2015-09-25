<?php

namespace App\Http\Controllers;

use App\Channels;
use App\Http\Requests\CreateNewChannelRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
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
        return view('channel.view',[
            'channel' => $channel
        ]);
    }

    public function create_channel(){
        return view('channel.create',[
//            'channel' => $channel
        ]);
    }

    public function update_channel(){
        return view('channel.update',[
//            'channel' => $channel
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
}