<h6>自己紹介</h6>
<p>{{$profile->profile}}</p>

@if(Session::has('user_id'))
    @if($profile->id === session('user_id'))
        {!! link_to_route('edit_profile', 'プロフィール編集', [], ['class' => 'btn btn-default','role' => 'button']) !!}
        {!! link_to_route('edit_account', 'アカウント編集', [], ['class' => 'btn btn-default','role' => 'button']) !!}
    @endif
@endif
