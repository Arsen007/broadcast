@extends('template')
@section('title', 'Create new channel')
@section('content')

<div class="col-lg-7">
{!! Form::open([ 'route' => 'store' ]) !!}

    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
        {!! Form::label('title', 'Title') !!}
        {!!   Form::text('title', null, ['class' => 'form-control']) !!}
        {!!  $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
        {!! Form::label('slug', 'Slug') !!}
        {!!   Form::text('slug', null, ['class' => 'form-control']) !!}
        {!!  $errors->first('slug', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
        {!! Form::submit('Send') !!}
    </div>

{!!Form::close()!!}</div>
<script>
    $(function () {
        $('[name="title"]').on('change keyup', function () {
            $('[name="slug"]').val(convertToSlug($(this).val()))
        })
    })
</script>
@stop
