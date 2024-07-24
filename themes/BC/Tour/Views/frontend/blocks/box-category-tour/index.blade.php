@if ($list_item)
@php
// dd($model);
@endphp

@if ($title == "Dubai your way")


<div class="container mt-5">
    <div class="bravo-list-locations bravo-list-locations  style_4">
        @if ($title)
        <div class="title title-line">
            {{-- {{$title}} --}}
            {{ 'Dubai your way' }}
        </div>
        @endif
        @if (!empty($desc))
        <div class="desc">
            {{ $desc }}
        </div>
        @endif
<div class="list-item owl-carousel locationowl">
          
    @foreach ($Terms as $key => $row)
        <?php
        $size_col = 3;
        if (!empty($layout) and ($layout == 'style_2' or $layout == 'style_3' or $layout == 'style_4')) {
            $size_col = 12;
        } else {
            if ($key == 0) {
                $size_col = 12;
            }
        }
        ?>
@php
   
            $query = http_build_query(['terms' => [$row->id]]);
            $translate = $row->translate();
            $pagee_search = url('/tour') . '?' . $query; // Construct the URL with query parameters
            $image_url = get_file_url($row->image_id, 'full'); // Assuming each term has an image_id
            

@endphp
<div class="destination-item @if(!$image_url) no-image  @endif">
@if(!empty( $pagee_search)) <a href="{{ $pagee_search}}">  @endif
<div class="image" @if($image_url) style="background: url({{$image_url}})" @endif >
{{-- <div class="effect"></div> --}}
<div class="content">
<h6 class="title">{{$translate->name}}</h6>

</div>
</div>
@if(!empty( $pagee_search)) </a> @endif
</div>

    @endforeach
</div>
</div>
</div>




@else



<div class="container mt-5">
    <div class="bravo-list-locations bravo-list-locations  style_4">
        @if ($title)
        <div class="title title-line">
            {{ $title }}
        </div>
        @endif
        @if (!empty($desc))
        <div class="desc">
            {{ $desc }}
        </div>
        @endif
<div class="list-item owl-carousel locationowl">
          
    @foreach ($list_item as $key => $row)
        <?php
        $size_col = 3;
        if (!empty($layout) and ($layout == 'style_2' or $layout == 'style_3' or $layout == 'style_4')) {
            $size_col = 12;
        } else {
            if ($key == 0) {
                $size_col = 12;
            }
        }
        ?>
@php


$item_cat = $categories->firstWhere('id', $row['category_id']);
$translate = $item_cat->translate();
$link_location = false;
$image_url = get_file_url($row['image_id'], 'full');
$page_search = $item_cat->getLinkForPageSearch(false, ['cat_id[]' => $item_cat->id]);

@endphp
<div class="destination-item @if(!$image_url) no-image  @endif">
@if(!empty($page_search)) <a href="{{$page_search}}">  @endif
<div class="image" @if($image_url) style="background: url({{$image_url}})" @endif >
{{-- <div class="effect"></div> --}}
<div class="content">
<h6 class="title">{{$translate->name}}</h6>

</div>
</div>
@if(!empty($page_search)) </a> @endif
</div>

    @endforeach
</div>
</div>
</div>


@endif




@endif