@extends('template')
@section('title', 'Channels')
@section('content')
@foreach ($channels as $channel)
        <div class="col-md-3 portfolio-item">
            <a href="{{url('channel/'.$channel->slug)}}"><img class="img-responsive" src="/img/tv.png" ></a>
            <h3><a href="{{url('channel/'.$channel->slug)}}">{{ $channel->title }}</a></h3>
        </div>
    @endforeach

@stop
