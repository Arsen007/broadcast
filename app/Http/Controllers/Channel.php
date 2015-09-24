<?php

namespace App\Http\Controllers;

use App\Channels;

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
}