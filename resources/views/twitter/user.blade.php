@extends('layouts.app')

@section('content')

    <ul class="media-list">

        @foreach($user as $k => $v)



            <li class="media">
                <div class="media-left">
                    <a href="/profile/{{$v->id}}/timeline"><img class="media-object img-rounded mini-profile" src="{{$v->icon_url}}" /></a>
                </div>
                <div class="media-body">
                    <div>
                        <span>{!! $v->disp_name !!}</span>
                        <span>{!! '(@'.$v->name.')' !!}</span>
                    </div>
                    <div>
                        <a href="/profile/{{$v->id}}/profile/">view profile</a>
                    </div>

                </div>
            </li>

            <hr>

        @endforeach

    </ul>

    {{ $user->links() }}

@endsection

