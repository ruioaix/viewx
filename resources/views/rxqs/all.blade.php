@extends('rxq')

@section('title', 'RXQ Modules Relations')
@endsection

@section('dataset')
@include('rxqs.all_dataset')
@endsection

@section('edges')
@include('rxqs.all_edges')
@endsection
