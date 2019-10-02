@extends('layouts.app')

@section('content')

    <h5 class="mb-3">新規登録 確認画面</h5>

    <div class="row ml-0 mr-0">
        <div class="col-xs-4">
            {!! Form::model($user, ['route' => 'store_signup']) !!}
            {!! csrf_field() !!}

            <div class="form-group max-width484 min-width375">


                {!! Form::label('name', 'ユーザー名',['class' => 'control-label mb-0']) !!}

                {!! Form::hidden('name', $request->name, ['class' => '']) !!}
                <p class="text-break mb-0">{{$request->name}}</p>
                <hr>

                {!! Form::label('disp_name', '表示名',['class' => 'control-label mb-0']) !!}

                {!! Form::hidden('disp_name', $request->disp_name, ['class' => '']) !!}
                <p class="text-break mb-0">{{$request->disp_name}}</p>
                <hr>

                <span>{!! Form::label('password', 'パスワード',['class' => 'control-label mb-0']) !!}</span>

                {!! Form::hidden('password', $request->password, ['class' => '']) !!} <span class="small text-muted">(セキュリティの為,非表示にしています)</span>
                <p class="text-break mb-0">●●●●●●</p>


            </div>

            {!! Form::submit('戻る', ['class' => 'btn btn-default', 'name' => 'back', 'role' => 'button']) !!}
            {!! Form::submit('ユーザー登録する', ['class' => 'btn btn-primary', 'role' => 'button']) !!}

            {!! Form::close() !!}
        </div>
    </div>


@endsection