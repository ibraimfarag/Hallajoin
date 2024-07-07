@php
    $terms_ids = $row->tour_term->pluck('term_id');
    $attributes = \Modules\Core\Models\Terms::getTermsById($terms_ids);
    // dd($terms_ids ); // Commented out the debug statement for production use
@endphp

@if(!empty($terms_ids) and !empty($attributes))
    @foreach($attributes as $attribute)
        @php 
            $translate_attribute = $attribute['parent']->translate();
            $parent_slug = $attribute['parent']->slug;
        @endphp

        @if($parent_slug === 'option-tour')
            <div class="g-attributes {{$parent_slug}} attr-{{$attribute['parent']->id}}">
                {{-- <h3>{{ $translate_attribute->name }}</h3> --}}
                @php $terms = $attribute['child'] @endphp
                <div class="list-attributes">
                    @foreach($terms as $term)
                        @php $translate_term = $term->translate() @endphp
                        <div class="item {{$term->slug}} term-{{$term->id}}">
                            @if(!empty($term->image_id))
                                @php $image_url = get_file_url($term->image_id, 'full'); @endphp
                                <img src="{{$image_url}}" class="img-responsive" alt="{{$translate_term->name}}">
                            @else
                                <i class="{{ $term->icon ?? 'icofont-check-circled icon-default' }}"></i>
                            @endif
                            {{$translate_term->name}}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
@endif
