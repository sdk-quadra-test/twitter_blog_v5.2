@extends('layouts.app')

@section('content')

    @include('twitter.concern.timeline',['timeline' => $timeline])

@endsection