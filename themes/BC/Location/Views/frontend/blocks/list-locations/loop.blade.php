@php
    /**
    * @var $row \Modules\Location\Models\Location
    * @var $to_location_detail bool
    * @var $service_type string
    */
    $translation = $row->translate();
    $link_location = false;
    if(is_string($service_type)){
        $link_location = $row->getLinkForPageSearch($service_type);
    }
    if(is_array($service_type) and count($service_type) == 1){
        $link_location = $row->getLinkForPageSearch($service_type[0] ?? "");
    }
    if($to_location_detail){
        $link_location = $row->getDetailUrl();
    }
@endphp
<div class="destination-item @if(!$row->image_id) no-image  @endif">
    @if(!empty($link_location)) <a href="{{$link_location}}">  @endif
        <div class="image" @if($row->image_id) style="background: url({{$row->getImageUrl()}})" @endif >
            {{-- <div class="effect"></div> --}}
            <div class="content">
                <h6 class="title">{{$translation->name}}</h6>
                
            </div>
        </div>
    @if(!empty($link_location)) </a> @endif
</div>
