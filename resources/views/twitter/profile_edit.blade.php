@extends('layouts.app')

@section('content')

    <h5 class="mb-3">プロフィール編集</h5>

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
            {!! Form::model($user, ['route' => 'confirm_profile', 'files' => 'true']) !!}
            {{ method_field('patch') }}

            {!! csrf_field() !!}

            <div class="form-group max-width484">
                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('profile', '自己紹介',['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">
                        {!! Form::textarea('profile', null, ['class' => 'form-control','rows' => 5, 'cols' => 50]) !!}
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <span>{!! Form::label('icon_url', 'アイコン（正方形、300KB以下）', ['class' => 'control-label mb-0']) !!}</span>
                    </div>
                    <div class="card-body">

                        <?php $disk = Storage::disk('s3');
                        $file_name = explode('/', $user->icon_url);
                        $file_name = end($file_name); ?>
                        {{--default icon(S3 anonymousフォルダ)も見に行く--}}

                        @if($tmp_icon_url != "")
                            @foreach($tmp_icon_data as $k => $v)
                                {!! Form::hidden("icon_url[{$k}]", htmlspecialchars($v),['class' => '']) !!}
                            @endforeach

                            <img src="{{$tmp_icon_url}}" id="preview" class="mb-3"/><br/>

                        @elseif($disk->exists($file_name) || $disk->exists('anonymous/'.$file_name))

                            <img src="{{$user->icon_url}}" id="preview" class="mb-3"/><br/>
                        @endif

                        {!! Form::file('icon_url',['accept' => 'image/*']) !!}

                    </div>
                </div>
            </div>

            {{--{{link_to_route('profile','キャンセル',[$user->id.'/profile'],['class' => 'btn btn-default', 'role' => 'button'] )}}--}}
            {!! Form::submit('キャンセル', ['class' => 'btn btn-default', 'name' => 'cancel', 'role' => 'button']) !!}
            {!! Form::submit('確認画面へ', ['class' => 'btn btn-primary', 'role' => 'button']) !!}
            {!! Form::close() !!}

        </div>
    </div>


    <script>
        document.getElementById("icon_url").onchange = function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("preview").src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        };
    </script>



@endsection

