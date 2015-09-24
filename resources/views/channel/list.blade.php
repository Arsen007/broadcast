@extends('template')
@section('title', 'Channels')
@section('content')
    <div class="col-lg-12">
        <a href="{{url('channel/create')}}" class="btn btn-default">Create</a>
    </div>
@foreach ($channels as $channel)
        <div class="col-md-3 portfolio-item">
            <a href="{{url('channel/watch/'.$channel->slug)}}"><img class="img-responsive" src="/img/tv.png" ></a>
            <h3><a href="{{url('channel/watch/'.$channel->slug)}}">{{ $channel->title }}</a></h3>
        </div>
    @endforeach

@stop
