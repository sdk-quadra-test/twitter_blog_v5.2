@extends('layouts.app')

@section('content')

    <div class="row">
        <aside class="col-sm-4">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="card-title">{!! $profile->disp_name !!} {!! '(@'.$profile->name.')' !!}</span>
                </div>
                <div class="card-body">
                    <img class="rounded card-responsive" src="{{$profile->icon_url}}" alt="">
                </div>
            </div>
            {{-- 自分にはfollowボタン出さない --}}
            @if(Session::has('user_id'))
                @if($profile->id !== session('user_id'))
                    <button id='follow_user' class="btn btn-primary btn-block mt-0">+ follow</button>
                    <button id='unfollow_user' class="btn btn-danger btn-block mt-0">- unfollow</button>
                    <div id="result_msg"></div>
                @endif
            @endif
        </aside>

        <div class="col-sm-8">
            <ul class="nav nav-tabs nav-justified mb-3">
                <li class="nav-item"><a href="/profile/{{$profile->id}}/timeline"
                                        class="nav-link {{ Request::is('profile/*/timeline') ? 'active' : '' }}">
                        Timeline <span class="badge badge-secondary">{{$user_tweet_count}}</span></a>
                </li>

                <li class="nav-item"><a href="/profile/{{$profile->id}}/following"
                                        class="nav-link {{ Request::is('profile/*/following') ? 'active' : '' }}">
                        Following <span class="badge badge-secondary">{{$user_follow_count}}</span></a>
                </li>

                <li class="nav-item"><a href="/profile/{{$profile->id}}/profile"
                                        class="nav-link {{ Request::is('profile/*/profile') ? 'active' : '' }}">Profile</a>
                </li>
            </ul>

            @if(Request::is('profile/*/timeline'))
                @include('twitter.concern.timeline',['profile' => $profile, 'timeline' => $timeline])
            @elseif(Request::is('profile/*/following'))
                @include('twitter.concern.following',['following_list' => $following_list])
            @elseif(Request::is('profile/*/profile'))
                @include('twitter.concern.profile',['profile' => $profile])
            @endif

        </div>
    </div>


    <script>
        $(function () {
            // 初期状態
            @if($follow_count < 1)
            $('#follow_user').css('display', 'block');
            $('#unfollow_user').css('display', 'none');
            @elseif($follow_count >= 1)
            $('#follow_user').css('display', 'none');
            $('#unfollow_user').css('display', 'block');
            @endif

            $('#follow_user').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{!! route('follow_user', ['following_user_id' => $profile->id]) !!}",
                    type: 'POST',
                    data: {'id': {{$profile->id}}, '_method': 'POST'}
                })
                // Ajaxリクエストが成功した場合
                    .done(function (data) {

                        $('#follow_user').toggle();
                        $('#unfollow_user').toggle();
                        $('#result_msg').addClass('alert alert-success mt-3 pb-1');
                        $('#result_msg').html('<h6>{{$profile->disp_name}}をフォローしました</h6>');
                    })
                    .fail(function (data) {
                        $('#result_msg').addClass('alert alert-danger mt-3 pb-1');
                        $('#result_msg').html('<h6>{{$profile->disp_name}}のフォローに失敗しました.管理者に問い合わせて下さい</h6>');
                    });
            });

            $('#unfollow_user').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{!! route('unfollow_user', ['following_user_id' => $profile->id]) !!}",
                    type: 'POST',
                    data: {'id': {{$profile->id}}, '_method': 'POST'}
                })
                // Ajaxリクエストが成功した場合
                    .done(function (data) {
                        console.log("b_success");
                        $('#follow_user').toggle();
                        $('#unfollow_user').toggle();
                        $('#result_msg').addClass('alert alert-success mt-3 pb-1');
                        $('#result_msg').html('<h6>{{$profile->disp_name}}のフォローを解除しました</h6>');
                    })
                    .fail(function (data) {
                        $('#result_msg').addClass('alert alert-danger mt-3 pb-1');
                        $('#result_msg').html('<h6>{{$profile->disp_name}}のフォロー解除に失敗しました.管理者に問い合わせて下さい</h6>');
                    });
            });
        });
    </script>

@endsection

