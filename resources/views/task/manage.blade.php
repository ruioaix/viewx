@extends('index')

@section('title', 'Task manage')
@endsection

@section('script')
@endsection

@section('content')
@if (isset($msg))
<div> {!! $msg !!} </div>
@endif

<div class="col-xs-6">
<p class='bg-info' style='padding: 15px'> the probability will be calculated according to ratio, set whatever you want.</p>
{!! Form::open(['class' => "form-horizontal"]) !!}
    <div class="form-group">
        {!! Form::label('infoprob', 'info', ['class' => "col-sm-3 control-label"]) !!}
        <div class="col-sm-7 input-group">
            {!! Form::text('infoprob', $info, ['class' => "form-control"]) !!}
            <div class="input-group-addon">{{ $info_date }}</div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('gainprob', 'gain', ['class' => "col-sm-3 control-label"]) !!}
        <div class="col-sm-7 input-group">
            {!! Form::text('gainprob', $gain, ['class' => "form-control"]) !!}
            <div class="input-group-addon">{{ $gain_date }}</div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('adjustprob', 'adjust', ['class' => "col-sm-3 control-label"]) !!}
        <div class="col-sm-7 input-group">
            {!! Form::text('adjustprob', $adjust, ['class' => "form-control"]) !!}
            <div class="input-group-addon">{{ $adjust_date }}</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            {!! Form::submit('Update', ['class' => 'btn btn-danger']) !!}
        </div>
    </div>
{!! Form::close() !!}
</div>


@endsection
