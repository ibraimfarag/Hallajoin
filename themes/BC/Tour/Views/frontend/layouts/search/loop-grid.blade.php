@php
    $translation = $row->translate();

@endphp


<div class="item-tour {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image">
        @if($row->discount_percent)
            <div class="sale_info">{{$row->discount_percent}}</div>
        @endif
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            @if($row->image_url)
                @if(!empty($disable_lazyload))
                @php
               
                $url_check = $row->image_url;
                // dd($url_check);
            @endphp
                    <img src="{{$url_check}}" class="img-responsive" alt="{{$location->name ?? ''}}">
        
                @else
                @php
               
                $url_check = $row->image_url;
                // dd($url_check);
            @endphp
                    {{-- {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive','alt'=>$row->title]) !!} --}}
                    <img src="{{$url_check}}" class="img-responsive" alt="{{$location->name ?? ''}}">
                @endif
            @endif
        </a>
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    <div class="location-review-container">

    <div class="location">
        @if(!empty($row->location->name))
            @php $location =  $row->location->translate() ;
               $terms_ids = $row->tour_term->pluck('term_id');
    $attributes = \Modules\Core\Models\Terms::getTermsById($terms_ids);
    $hasId108 = false;
        $icon='';
        $name='';

    foreach ($attributes as $attribute) {
        if (isset($attribute['child'])) {
            foreach ($attribute['child'] as $child) {
                if ($child['id'] == 111) {
                    $hasId108 = true;
                    $name = $child['name'];
                    $icon =$child['icon'];

                    break 2; // Break both loops if found
                }
            }
        }
    }



    dd($hasId108 ); // Commented out the debug statement for production use
            // dd($row);
            
            @endphp
<i class="fas fa-location-arrow"></i>            {{$location->name ?? ''}}

        @endif
    </div>

    @if(setting_item('tour_enable_review'))
    <?php
    $reviewData = $row->getScoreReview();
    $score_total = $reviewData['score_total'];
    ?>
    <div class="service-review tour-review-{{$score_total}}">
        <div class="list-star">
            <i class="fa fa-star"></i>
            <span class="rating-text"> {{$score_total}}  </span>
        </div>
        <span class="review">
            @if($reviewData['total_review'] > 1)
                {{ __("(:number)",["number"=>$reviewData['total_review'] ]) }}
            @else
                {{ __("(:number)",["number"=>$reviewData['total_review'] ]) }}
            @endif
        </span>
    </div>
@endif
    </div>

    <div class="item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            {{$translation->title}}
        </a>
    </div>
  
    @if($hasId108)
    <div class="additional-attributes">
        <i class="{{$icon}}"></i>
        <span>{{$name}}</span>
    </div>
@endif

    <div class="info">
        <div class="duration">
            <i class="icofont-wall-clock"></i>
            {{duration_format($row->duration)}}
        </div>
        <div class="g-price">
            <div class="prefix">
                <i class="icofont-flash"></i>
                <span class="fr_text">{{__("from")}}</span>
            </div>
            <div class="price">
                <span class="onsale">{{ $row->display_sale_price }}</span>
                <span class="text-price">{{ $row->display_price }}</span>
            </div>
        </div>
    </div>
</div>
