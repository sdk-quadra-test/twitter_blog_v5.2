@extends('layouts.app')

@section('content')

    <!-- ここにページ毎のコンテンツを書く -->
    <style type="text/css">
        .user_icon {
            width: 80px;
        }

    </style>
    <h5 class="mb-3">プロフィール確認画面</h5>

    <div class="row ml-0 mr-0">
        <div class="col-xs-4">
            {!! Form::model($user, ['route' => 'store_profile', 'files' => 'true']) !!}
            {{ method_field('patch') }}
            {!! csrf_field() !!}

            <div class="form-group max-width484 min-width375">
                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('profile', '自己紹介',['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">
                        {!! Form::hidden('profile',$request->profile,['class' => '']) !!}
                        <p class="text-break">{{$request->profile}}</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('icon_url', 'アイコン', ['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">
                        <?php $disk = Storage::disk('s3');
                        $file_name = explode('/', $user->icon_url);
                        $file_name = end($file_name);?>

                        @if($_FILES['icon_url']['size'] > 0)
                            @foreach($_FILES['icon_url'] as $k => $v)
                                {!! Form::hidden("icon_url[{$k}]", htmlspecialchars($v),['class' => '']) !!}
                            @endforeach
                            <img src="{{$tmp_icon_url}}" id="preview"/>
                        {{--default icon(S3 anonymousフォルダ)も見に行く--}}
                        @elseif($disk->exists($file_name) || $disk->exists('anonymous/'.$file_name))
                            <img src="{{$user->icon_url}}" id="preview"/>
                        @endif


                    </div>

                </div>

            </div>
            {!! Form::submit('戻る', ['class' => 'btn btn-default', 'name' => 'back', 'role' => 'button']) !!}
            {!! Form::submit('変更する', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}

        </div>
    </div>

@endsection