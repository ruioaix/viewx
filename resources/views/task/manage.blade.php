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
            <div class="input-group-addon">updated at {{ $info_date }}</div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('gainprob', 'gain', ['class' => "col-sm-3 control-label"]) !!}
        <div class="col-sm-7 input-group">
            {!! Form::text('gainprob', $gain, ['class' => "form-control"]) !!}
            <div class="input-group-addon">updated at {{ $gain_date }}</div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('adjustprob', 'adjust', ['class' => "col-sm-3 control-label"]) !!}
        <div class="col-sm-7 input-group">
            {!! Form::text('adjustprob', $adjust_common, ['class' => "form-control"]) !!}
            <div class="input-group-addon">updated at {{ $adjust_common_date }}</div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('adjustlprob', 'adjust list', ['class' => "col-sm-3 control-label"]) !!}
        <div class="col-sm-7 input-group">
            <fieldset disabled>
            {!! Form::text('adjustlprob', $adjust_list, ['class' => "form-control"]) !!}
            </fieldset>
            <div class="input-group-addon">updated at {{ $adjust_list_date }}</div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('adjustcprob', 'adjust comp', ['class' => "col-sm-3 control-label"]) !!}
        <div class="col-sm-7 input-group">
            {!! Form::text('adjustcprob', $adjust_cheating, ['class' => "form-control"]) !!}
            <div class="input-group-addon">updated at {{ $adjust_cheating_date }}</div>
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
