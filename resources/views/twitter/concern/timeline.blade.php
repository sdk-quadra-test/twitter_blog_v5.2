@if (count($timeline) > 0)

    <ul class="media-list">

        @foreach ($timeline as $t)
            <li class="media">
                <div class="media-left">
                    <a href="/profile/{{$t->user_id}}/timeline"><img class="media-object img-rounded mini-profile" src="{{$t->icon_url}}" /></a>
                </div>
                <div class="media-body">
                    <div>
                        {!! $t->disp_name !!}
                        {!! '('.$t->name.')' !!}
                        <span class="text-muted">posted at {{$t->tweet_created_at}}</span>
                    </div>
                    <div>
                        <p class="text-break">{!! $t->content !!}</p>
                    </div>
                </div>
            </li>

            <hr>
        @endforeach

    </ul>

@endif

{{ $timeline->links() }}