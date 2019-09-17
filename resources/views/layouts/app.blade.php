@inject('notice','App\Http\Controllers\NoticeController')

        <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>TweetBoard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    {{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    {{--    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>

</head>

<body>
<header class="mb-4">
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <a class="navbar-brand" href="/timeline">TweetBoard</a>

        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    {!! Session::has('user_id') ? '<a href="#" class="text-white btn btn-success pull-left" data-toggle="modal" data-target="#basicModal" id="linkModal">tweetする</a>' : '' !!}
                </li>
                <li>{!! link_to_route('timeline', 'タイムライン', [], ['class' => '']) !!}</li>

                <li><a href="/user">全ユーザー</a></li>

                @if(Session::has('user_id'))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{session('disp_name')}}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                @if($notice->count_notice() > 0)
                                    <a href="/notice">通知 <span class="badge badge-danger">{{$notice->count_notice()}}</span></a>
{{--                                    {!! link_to_route('notice', '通知('.$notice->count_notice().')', [], ['class' => '']) !!}--}}
                                @else
                                    {!! link_to_route('notice', '通知', [], ['class' => '']) !!}
                                @endif
                            </li>
                            <li>{!! link_to_route('profile', 'プロフィール', [session('user_id').'/timeline'], ['class' => '']) !!}</li>
                            <li role="separator" class="divider"></li>
                            <li>{!! link_to_route('logout', 'ログアウト', [], ['class' => '']) !!}</li>
                        </ul>
                    </li>
                @else
                    <li>{!! link_to_route('login', 'ログイン', [], ['class' => '']) !!}</li>
                @endif
            </ul>
        </div>
    </nav>
</header>

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">TweetBoard</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                {{--<h3>つぶやく</h3>--}}
                <div class="row">
                    <div class="col-12">
                        {!! Form::open(['url' => '/store_tweet', 'files' => false])!!}
                        {!! csrf_field() !!}
                        <div class="form-group">
                            @if(Session::has('err_empty'))
                                <script>
                                    $('#linkModal').click();
                                </script>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li>{{ session('err_empty') }}</li>
                                    </ul>
                                </div>
                            @endif

                            {!! Form::label('content', '内容:') !!}
                            {!! Form::textarea('content', null, ['class' => 'form-control','rows' => 3]) !!}
                        </div>

                        <div class="modal-footer">
                            {!! Form::submit('Tweetする', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container">
    @yield('content')
</div>

<footer>TweetBoard., <cite title="Source Title">progra-master</cite></footer>

</body>
</html>