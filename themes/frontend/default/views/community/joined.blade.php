<div class="communities-container">
    @foreach($communities as $member)
        {{Theme::section('community.display', ['community' => $member->community])}}
    @endforeach
</div>

{{$communities->links()}}