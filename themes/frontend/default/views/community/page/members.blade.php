<div class="box">
    <div class="box-title">{{$community->title}} Members</div>
    <div class="box-content">
        {{Theme::section('community.page.display-member', ['user' => $community->user])}}
        @foreach($members as $member)
            {{Theme::section('community.page.display-member', ['user' => $member->user])}}
        @endforeach

        {{$members->links()}}
    </div>
</div>