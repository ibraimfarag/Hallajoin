@if($list_item)


<div class="bravo-box-category-tour">
        <div class="container">
            @if($title)
                <div class="title title-line">
                    {{$title}}
                </div>
            @endif
            @if(!empty($desc))
                <div class="desc">
                    {{$desc}}
                </div>
            @endif
            <div class="list-item owl-carousel">
                @foreach($list_item as $k=>$item)
                    @php $image_url = get_file_url($item['image_id'], 'full'); @endphp
                        @if( !empty( $item_cat =  $categories->firstWhere('id',$item['category_id']) ))
                            @php
                                $translate = $item_cat->translate();
                                $page_search = $item_cat->getLinkForPageSearch(false , [ 'cat_id[]' =>  $item_cat->id] );
                            @endphp
                            {{-- <div class="item"> --}}
                                {{-- <a href="{{ $page_search }}">
                                    <img src="{{$image_url}}" alt="{{ $translate->name }}">
                                    <span class="text-title">{{ $translate->name }}</span>
                                </a> --}}
                            {{-- </div> --}}

                                

                        <div class="destination-item @if(!$image_url) no-image  @endif">
                            @if(!empty($page_search)) <a href="{{$page_search}}">  @endif
                                <div class="image" @if($image_url) style="background: url('{{ url($image_url) }}')"
                                @endif >
                                    <div class="effect"></div>
                                    <div class="content">
                                        <h6 class="title">{{$translate->name}}</h6>
                                       
                                    </div>
                                </div>
                            @if(!empty($page_search)) </a> @endif
                        </div>
                        


                        @endif
                @endforeach
            </div>
        </div>
    </div>
@endif
