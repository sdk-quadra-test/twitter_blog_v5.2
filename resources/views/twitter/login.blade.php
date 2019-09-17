@extends('layouts.app')

@section('content')

    <!-- ここにページ毎のコンテンツを書く -->

    @if(Session::has('err_status'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                <li>{{ session('err_status') }}</li>
            </ul>
        </div>
{{--    @endif--}}

    @elseif (count($errors) > 0)
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
{{--    @endif--}}

    @elseif(Session::has('success_signup'))
        <div class="alert alert-success">
            <ul class="mb-0">
                <li>{{ session('success_signup') }}</li>
            </ul>
        </div>
    @endif



    <div class="row">
        <div class="col-6">
            <div id="login-form" class="mb-3">
                <p class="mb-3">項目に入力してログインして下さい</p>
                {!! Form::model($user, ['route' => 'validate_login']) !!}
                {!! csrf_field() !!}
                <div class="form-group">
                    {!! Form::label('name', 'ユーザー名:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control mb-2']) !!}

                    {!! Form::label('password', 'パスワード:') !!}
                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'input-pass']) !!}
                    <input class="main__crd__checkbox" type="checkbox" id="disp-pass" name="disp-pass" value="">
                    <label for="disp-pass">パスワードの表示</label>
                </div>

                {!! Form::submit('ログイン', ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}

                <p class="small text-muted mt-3">新規登録は{{link_to_route('signup','こちら',[],['class' => ''] )}}</p>
            </div>
        </div>
    </div>



    <style type="text/css">
        #login-form a:hover, #signup-form a:hover {
            text-decoration: none;
        }
    </style>



    <script>
        $("#disp-pass").change(function () {
            if ($(this).prop("checked")) {
                $("#input-pass").attr("type", "text");
            } else {
                $("#input-pass").attr("type", "password");
            }
        })
    </script>
@endsection