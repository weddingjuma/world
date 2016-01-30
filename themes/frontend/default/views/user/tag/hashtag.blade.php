@foreach($hashtags as $hashtag)
    <a href="" data-tag="{{$hashtag->hash}}"  class="media">

        <div class="media-body">
            <h4 class="media-heading"><strong>{{$hashtag->hash}}</strong> </h4>

        </div>
    </a>

@endforeach