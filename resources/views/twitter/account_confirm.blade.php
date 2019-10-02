@extends('layouts.app')

@section('content')
    <h5 class="mb-3">アカウント確認画面</h5>

    <div class="row ml-0 mr-0">
        <div class="col-xs-4">
            {!! Form::model($user, ['route' => 'store_account']) !!}
            {{ method_field('patch') }}
            {!! csrf_field() !!}

            <div class="form-group max-width484 min-width375">

                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('name', 'ユーザー名',['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">
                        {!! Form::hidden('name', $request->name, ['class' => '']) !!}
                        <p class="text-break mb-0">{{$request->name}}</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('disp_name', '表示名',['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">
                        {!! Form::hidden('disp_name', $request->disp_name, ['class' => '']) !!}
                        <p class="text-break mb-0">{{$request->disp_name}}</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('password', 'パスワード',['class' => 'control-label mb-0']) !!}</span>  <span class="small text-muted">(セキュリティの為,非表示にしています)</span>
                    </div>
                    <div class="card-body">
                        {!! Form::hidden('password', $request->password, ['class' => '']) !!}
                        <p class="text-break mb-0">●●●●●●</p>
                    </div>
                </div>

            </div>

            {!! Form::submit('戻る', ['class' => 'btn btn-default', 'name' => 'back', 'role' => 'button']) !!}
            {!! Form::submit('変更する', ['class' => 'btn btn-primary', 'role' => 'button']) !!}
            {!! Form::close() !!}
        </div>
    </div>


@endsection