@extends('layouts.app')

@section('content')

    <style type="text/css">
        .user_icon {
            width: 80px;
        }

    </style>

    <h2>{{$user->name}}のフォロー一覧</h2>
    @foreach($following_list as $f)

        <img src="{{$f->icon_url}}" class="user_icon" alt=""/><br/>
        {{ $f->disp_name }}
        {{ $f->name }}<br />
        {{ $f->profile}}
        <hr>

    @endforeach


@endsection