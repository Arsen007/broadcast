@extends('template')
@section('title', 'Update Channel - '.$channel->title)
@section('content')

    <div class="col-lg-12">
        <a href="{{url('')}}" class="btn btn-default">Channel List</a>
    </div>
    <div class="col-lg-6">
        {!! Form::open([ 'route' => 'update' ]) !!}
        {!!  Form::hidden('id', $channel->id) !!}
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {!! Form::label('title', 'Title') !!}
            {!!   Form::text('title', $channel->title, ['class' => 'form-control']) !!}
            {!!  $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
            {!! Form::label('slug', 'Slug') !!}
            {!!   Form::text('slug', $channel->slug, ['class' => 'form-control']) !!}
            {!!  $errors->first('slug', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('allow_all') ? ' has-error' : '' }}">
            {!! Form::label('', 'Publishing access') !!}<br>

            <div class="col-lg-offset-1">
                {!! Form::label('allow_all', 'All') !!}
                {!!   Form::radio('allow_all', 1, $channel->allow_all == 1,[]) !!}<br>
                {!! Form::label('allow_all', 'Ip') !!}
                {!!   Form::radio('allow_all', 0, $channel->allow_all == 0,[]) !!}
                {!!  $errors->first('allow_all', '<p class="help-block">:message</p>') !!}
                    <div class="col-lg-12 ip_container">
                        {!!   Form::text('allowed_ips', $channel->allowed_ips, ['class' => 'form-control','placeholder' => 'IP address (155.84.55.1)']) !!}
                        {!!  $errors->first('allowed_ips', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-12{{ $errors->has('slug') ? ' has-error' : '' }}">
            {!! Form::submit('Save',['class' => 'btn btn-primary']) !!}
        </div>

        {!!Form::close()!!}
    <script>
        $(function () {
            $('[name="title"]').on('change keyup', function () {
                $('[name="slug"]').val(convertToSlug($(this).val()))
            });


        });
        $(document).on('click','.add_ip', function () {
            var ip_input_str = '<div class="col-lg-12 ip_container">'+
                                    '<input class="form-control" placeholder="IP address (127.0.0.1)" name="ip['+Math.random().toString(36).substring(7)+']" type="text">'+
                                    '<button class="btn btn-primary add_ip" type="button">+</button>'+
                                    '<button class="btn btn-danger remove_ip" type="button">-</button>'
                                '</div>';
            $('.ip_addresses_container').append(ip_input_str);
        });
        $(document).on('click','.remove_ip', function () {
            $(this).closest('.ip_container').remove();
        });
    </script>
@stop
