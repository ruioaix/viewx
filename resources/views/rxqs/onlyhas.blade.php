@extends('rxq')

@section('title', 'RXQ Modules only import others')
@endsection

@section('dataset')
@include('rxqs.only_has_dataset')
@endsection

@section('edges')
@include('rxqs.only_has_edges')
@endsection
