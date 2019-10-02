@extends('layouts.app')

@section('content')

    <script>
        $(function () {
            console.log("bbb");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('update_to_read', ['is_read' => 1]) }}",
                type: 'POST',
                data: {'is_read': 1, '_method': 'POST'}
            })
            // Ajaxリクエストが成功した場合
                .done(function (data) {
                    console.log("is_read_success");
                })
                // Ajaxリクエストが失敗した場合
                .fail(function (data) {
                    alert(data.responseJSON);
                });

        })
    </script>


    <h5 class="mb-3">通知一覧</h5>

    <ul class="media-list">
        @foreach ($notice as $k => $v)

            <li class="media">
                <div class="media-left">
                    <a href="/profile/{{$v->user_id}}/timeline"><img class="media-object img-rounded mini-profile"
                                                                     src="{{$v->icon_url}}"/></a>
                </div>
                <div class="media-body">
                    @if($v->is_read == 0)
                        <span class="badge badge-danger">new</span>
                    @endif
                    <div>
                        {!! $v->disp_name !!}
                        {!! '('.$v->name.')' !!}
                        <span class="text-muted">posted at {{$v->tweet_created_at}}</span>
                    </div>
                    <div>
                        <p class="text-break">{!! $v->content !!}</p>
                    </div>
                </div>
            </li>
            <hr>
        @endforeach
    </ul>

    {{ $notice->links() }}


@endsection




{{--<ul class="media-list">--}}

{{--    @foreach ($timeline as $t)--}}
{{--        <li class="media">--}}
{{--            <div class="media-left">--}}
{{--                <a href="/profile/{{$t->user_id}}/timeline"><img class="media-object img-rounded mini-profile"--}}
{{--                                                                 src="{{$t->icon_url}}"/></a>--}}
{{--            </div>--}}
{{--            <div class="media-body">--}}
{{--                <div>--}}
{{--                    {!! $t->disp_name !!}--}}
{{--                    {!! '('.$t->name.')' !!}--}}
{{--                    <span class="text-muted">posted at {{$t->tweet_created_at}}</span>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <p class="text-break">{!! $t->content !!}</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </li>--}}

{{--        <hr>--}}
{{--    @endforeach--}}

{{--</ul>--}}