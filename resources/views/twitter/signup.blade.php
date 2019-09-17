@extends('layouts.app')

@section('content')

    <!-- ここにページ毎のコンテンツを書く -->

    @if(Session::has('err_status'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                <li>{{ session('err_status') }}</li>
            </ul>
        </div>
    @endif



    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row">
        <div class="col-6">
            <div id="signup-form">
                <p class="mb-3">項目に入力して新規登録して下さい</p>
                {!! Form::model($user, ['route' => 'confirm_signup']) !!}
                {!! csrf_field() !!}
                <div class="form-group">
                    {!! Form::label('name', 'ユーザー名:') !!} <span class="small text-muted">(login時に使います)</span>
                    {!! Form::text('name', null, ['class' => 'form-control mb-2']) !!}
                    {!! Form::label('disp_name', '表示名:') !!}
                    {!! Form::text('disp_name', null, ['class' => 'form-control mb-2']) !!}
                    {!! Form::label('password', 'パスワード:') !!} <span class="small text-muted">(login時に使います)</span>
                    {!! Form::password('password', ['class' => 'form-control mb-2', 'id' => 'input-pass']) !!}
                    {!! Form::label('confirm_password', 'パスワード確認:') !!}
                    {!! Form::password('confirm_password', ['class' => 'form-control', 'id' => 'input-confirm-pass']) !!}
                    <input class="main__crd__checkbox" type="checkbox" id="disp-pass" name="disp-pass" value="">
                    <label for="disp-pass">パスワードの表示</label>
                </div>

                {!! Form::submit('確認画面へ', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}

                <p class="small text-muted mt-3">ログインは{{link_to_route('login','こちら',[],['class' => ''] )}}</p>
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
            console.log("here");
            if ($(this).prop("checked")) {
                $("#input-pass").attr("type", "text");
                $("#input-confirm-pass").attr("type", "text");
            } else {
                $("#input-pass").attr("type", "password");
                $("#input-confirm-pass").attr("type", "password");
            }
        })
    </script>
@endsection