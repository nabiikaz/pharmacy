@extends('layouts.dashboard')


@section("content")



<dashboard_purchases :batchId="{{$id}}"></dashboard_purchases>

@endsection