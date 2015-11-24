@extends('index')

@section('title', 'Task manage')
@endsection

@section('script')
@endsection

@section('content')

<div class="col-xs-6">
{!! Form::open(['class' => "form-horizontal"]) !!}
    <div class="form-group">
        {!! Form::label('infoprob', 'info', ['class' => "col-sm-6 control-label"]) !!}
        <div class="col-sm-6">
            {!! Form::text('infoprob', $info, ['class' => "form-control"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('gainprob', 'gain', ['class' => "col-sm-6 control-label"]) !!}
        <div class="col-sm-6">
            {!! Form::text('gainprob', $gain, ['class' => "form-control"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('adjustprob', 'adjust', ['class' => "col-sm-6 control-label"]) !!}
        <div class="col-sm-6">
            {!! Form::text('adjustprob', $adjust_common, ['class' => "form-control"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('adjustlprob', 'adjust list', ['class' => "col-sm-6 control-label"]) !!}
        <div class="col-sm-6">
            {!! Form::text('adjustlprob', $adjust_list, ['class' => "form-control"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('adjustcprob', 'adjust comp', ['class' => "col-sm-6 control-label"]) !!}
        <div class="col-sm-6">
            {!! Form::text('adjustcprob', $adjust_complete, ['class' => "form-control"]) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-6 col-sm-10">
            {!! Form::submit('Update', ['class' => 'btn btn-default']) !!}
        </div>
    </div>
{!! Form::close() !!}
</div>


@endsection
