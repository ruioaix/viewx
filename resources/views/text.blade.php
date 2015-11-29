@extends('index')


@section('title', $title)
@endsection

@section('content')
<h2> {{ $zid }} </h2>
{!! $res !!}
@endsection
