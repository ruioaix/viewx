@extends('rxq')

@section('title', 'RXQ Modules only being imported')
@endsection


@section('dataset')
@include('rxqs.only_in_dataset')
@endsection

@section('edges')
@include('rxqs.only_in_edges')
@endsection

