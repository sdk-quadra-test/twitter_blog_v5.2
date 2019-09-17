@extends('layouts.app')

@section('content')

    <style type="text/css">
        .user_icon {
            width: 80px;
        }

    </style>

    <h5 class="mb-3">アカウント編集</h5>


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row ml-0 mr-0">
        <div class="col-xs-4">

            {!! Form::model($user, ['route' => 'confirm_account']) !!}
            {{ method_field('patch') }}
            {!! csrf_field() !!}

            <div class="form-group max-width484 min-width400">

                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('name', 'ユーザー名',['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">
                        {!! Form::text('name', null, ['class' => 'form-control', 'size' => 50]) !!}
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('disp_name', '表示名',['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">
                        {!! Form::text('disp_name', null, ['class' => 'form-control', 'size' => 50]) !!}
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('password', 'パスワード', ['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">
                        {!! Form::password('password', ['class' => 'form-control', 'size' => 50, 'id' => 'input-pass']) !!}

                        <span class="small">確認</span>
                        {!! Form::password('confirm_password', ['class' => 'form-control', 'size' => 50, 'id' => 'input-confirm-pass']) !!}
                        <input class="main__crd__checkbox" type="checkbox" id="disp-pass" name="disp-pass" value="">
                        <label for="disp-pass">パスワードの表示</label>
                    </div>
                </div>

            </div>

            {{link_to_route('profile','キャンセル',[$user->id.'/profile'],['class' => 'btn btn-default', 'role' => 'button'] )}}
            {!! Form::submit('確認画面へ', ['class' => 'btn btn-primary', 'role' => 'button']) !!}

            {!! Form::close() !!}
        </div>
    </div>





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

