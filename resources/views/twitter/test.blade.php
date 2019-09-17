<h4>{{$profile->name}}のtweet一覧</h4>
@if (count($timeline) > 0)
    @foreach ($timeline as $t)
        <p><img src="{{$t->icon_url}}" class="user_icon"/>
            {{$t->disp_name}}
            {{$t->name}}
        </p>
        <p style="word-break: break-all;">{{$t->content}}<br>
            {{$t->tweet_created_at}}</p>
        <hr>
    @endforeach
    {{ $timeline->links() }}
@endif