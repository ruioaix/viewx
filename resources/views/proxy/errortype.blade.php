@extends('index')

@section('title', 'ErrorType of Proxy')
@endsection

@section('content')
<h1> Proxy Error Type </h1>

<ul>
@foreach ($paes as $key => $value) 
<li> {{ $key }}: {{ $value }} </li>
@endforeach
</ul>

@endsection
