@extends('template')
@section('title', 'Channels')
@section('content')
    <div class="col-lg-12">
        <a href="{{url('channel/create')}}" class="btn btn-default">Create</a>
    </div>
@foreach ($channels as $channel)
        <div class="col-md-3 portfolio-item" id="{{$channel->id}}">
            <a href="{{url('channel/watch/'.$channel->slug)}}"><img class="img-responsive" src="/img/tv.png" ></a>
            <span class="live-indicate offline"></span>
            <h3><a href="{{url('channel/watch/'.$channel->slug)}}">{{ $channel->title }}</a></h3>
        </div>
    @endforeach
    <script>
        $(function () {
            setInterval(function () {
                $.ajax({
                    url: '{{url('channel/status')}}',
                    data: {},
                    async: false,
                    type: "get",
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i,e) {
                            if(e){
                                $('#'+i+' .live-indicate').removeClass('offline').addClass('online');
                            }else{
                                $('#'+i+' .live-indicate').removeClass('online').addClass('offline');
                            }
                        })
                    }
                });
            },2000);

        })
    </script>
@stop
