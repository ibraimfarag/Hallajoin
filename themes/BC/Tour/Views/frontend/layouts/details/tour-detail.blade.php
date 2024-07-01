<div class="g-header">
    <div class="left">
        @if($translation->address)
            <p class="address"><i class="fas fa-location-arrow orange" style="font-size: 14px;"></i>

                {{$translation->address}}
            </p>
        @endif
        <h1>{{$translation->title}}</h1>
        <span class="text-rating"><i class="fa fa-star orange"></i>&nbsp;{{$review_score['score_total']}}&nbsp;({{__(" :number ",['number'=>$review_score['total_review']])}})</span>

    </div>
    <div class="right">
        @if(setting_item('tour_enable_review') and $review_score)
            <div class="review-score">
                <div class="head">
                    <div class="left">
                        <span class="head-rating">{{$review_score['score_text']}}</span>
                        <span class="text-rating">{{__("from :number reviews",['number'=>$review_score['total_review']])}}</span>
                    </div>
                    <div class="score">
                        {{$review_score['score_total']}}<span>/5</span>
                    </div>
                </div>
                <div class="foot">
                    {{__(":number% of guests recommend",['number'=>$row->recommend_percent])}}
                </div>
            </div>
        @endif
    </div>
</div>
@if(!empty($row->duration) or !empty($row->category_tour->name) or !empty($row->max_people) or !empty($row->location->name))
    <div class="g-tour-feature">
    <div class="row">
        @if($row->duration)
            <div class="col-xs-6 col-lg-3 col-md-6">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-wall-clock "></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{__("Duration")}}</h4>
                        <p class="value">
                            {{duration_format($row->duration,true)}}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if(!empty($row->category_tour->name))
            @php $cat =  $row->category_tour->translate() @endphp
            <div class="col-xs-6 col-lg-3 col-md-6">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-beach"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{__("Tour Type")}}</h4>
                        <p class="value">
                            {{$cat->name ?? ''}}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if($row->max_people)
            <div class="col-xs-6 col-lg-3 col-md-6">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-travelling"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{__("Group Size")}}</h4>
                        <p class="value">
                            @if($row->max_people > 1)
                                {{ __(":number persons",array('number'=>$row->max_people)) }}
                            @else
                                {{ __(":number person",array('number'=>$row->max_people)) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if(!empty($row->location->name))
                @php $location =  $row->location->translate() @endphp
            <div class="col-xs-6 col-lg-3 col-md-6">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-island-alt"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{__("Location")}}</h4>
                        <p class="value">
                            {{$location->name ?? ''}}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endif
@if($row->getGallery())
    <div class="g-gallery">
        <div class="fotorama" data-width="100%" data-thumbwidth="135" data-thumbheight="135" data-thumbmargin="15" data-nav="thumbs" data-allowfullscreen="true">
            @foreach($row->getGallery() as $key=>$item)
                <a href="{{$item['large']}}" data-thumb="{{$item['thumb']}}" data-alt="{{ __("Gallery") }}"></a>
            @endforeach
        </div>
        <div class="social">
            <div class="social-share">
                <span class="social-icon">
                    <i class="icofont-share"></i>
                </span>
                <ul class="share-wrapper">
                    {{-- <li>
                        <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{$row->getDetailUrl()}}&amp;title={{$translation->title}}" target="_blank" rel="noopener" original-title="{{__("Facebook")}}">
                            <i class="fa fa-facebook fa-lg"></i>
                        </a>
                    </li>
                    <li>
                        <a class="twitter" href="https://twitter.com/share?url={{$row->getDetailUrl()}}&amp;title={{$translation->title}}" target="_blank" rel="noopener" original-title="{{__("Twitter")}}">
                            <i class="fa fa-twitter fa-lg"></i>
                        </a>
                    </li> --}}

                    <li>
                        <a class="link" href="whatsapp://send?text={{$translation->title}}: {{$row->getDetailUrl()}}" target="_blank" rel="noopener" original-title="{{__("WhatsApp")}}">
                            
 
                            <i class="fab fa-whatsapp-square"></i>
                        
                        </a>
                    </li>
                    <li>
                        <a class="link" href="{{$row->getDetailUrl()}}" target="_blank" rel="noopener" original-title="{{__("Link")}}" onclick="copyToClipboard()">
                            <i class="fa fa-link fa-lg"></i>
                        </a>
                    </li>
                    
                </ul>
            </div>
            <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
                <i class="fa fa-heart"></i>
            </div>
        </div>
    </div>
@endif

@include('Tour::frontend.layouts.details.tour-option')

@if($translation->content)
                <div class="card">
                    <div class="card-header" id="headingOverview">
                        <h2 class="mb-0">
                            <button class="btn accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseOverview" aria-expanded="false" aria-controls="collapseOverview">
                                {{ __("Overview") }}
                                <i class="fas fa-chevron-up arrow-icon"></i>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOverview" class="collapse" aria-labelledby="headingOverview" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="description">
                                <?php echo $translation->content ?>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
@include('Tour::frontend.layouts.details.tour-include-exclude')
@include('Tour::frontend.layouts.details.tour-itinerary')
{{-- @include('Tour::frontend.layouts.details.tour-attributes') --}}
@include('Tour::frontend.layouts.details.tour-faqs')
@includeIf("Hotel::frontend.layouts.details.hotel-surrounding")
@if($row->map_lat && $row->map_lng)
<div class="card">
    <div class="card-header" id="headingLocation">
        <h2 class="mb-0">
            <button class="btn accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseLocation" aria-expanded="false" aria-controls="collapseLocation">
                {{ __("Tour Location") }}
                <i class="fas fa-chevron-up arrow-icon"></i>
            </button>
        </h2>
    </div>
    <div id="collapseLocation" class="collapse" aria-labelledby="headingLocation" data-parent="#accordionExample">
        <div class="card-body">
            <div class="coordinates">
                <p>
                    <a href="https://www.google.com/maps/search/?api=1&query={{$row->map_lat}},{{$row->map_lng}}" target="_blank">View on Google Maps</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endif
<script>
    function copyToClipboard() {
        var link = "{{$row->getDetailUrl()}}";
        var tempInput = document.createElement("input");
        tempInput.value = link;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        alert("Link copied to clipboard: " + link);
    }
</script>
