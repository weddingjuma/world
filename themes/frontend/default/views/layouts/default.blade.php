@if(Config::get('theme-fade-effect', 1))
    <div class="fade-cover"></div>
@endif
{{Theme::section('theme/layouts.header')}}


            <div id="content-container">
                <?php $ads = app('App\\Repositories\\AdsRepository')->getHeader()?>

                @if(!empty($ads) and Request::segment(1) != '')
                    <div class="container" style="position: relative;top: 10px">
                        {{$ads}}
                    </div>
                @endif
                {{$content}}

            </div>


{{Theme::section('theme/layouts.footer')}}