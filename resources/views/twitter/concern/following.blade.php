<ul class="media-list">

    @foreach($following_list as $f)

        <li class="media">
            <div class="media-left">
                <a href="/profile/{{$f->id}}/timeline">
                    <img class="media-object img-rounded mini-profile" src="{{$f->icon_url}}" /></a>
            </div>
            <div class="media-body">
                <div>
                    <span>{!! $f->disp_name !!}</span>
                    <span>{!! '(@'.$f->name.')' !!}</span>
                </div>
                <div>
                    <a href="/profile/{{$f->id}}/profile/">view profile</a>
                </div>

            </div>
        </li>

        <hr>

    @endforeach

</ul>
