@php
    if(!empty($translation->include)){
        $title = __("Included");
    }
    if(!empty($translation->exclude)){
        $title = __("Excluded");
    }
    if(!empty($translation->exclude) && !empty($translation->include)){
        $title = __("Included/Excluded");
    }
    if(!empty($translation->sections)){
        $sections = json_decode($translation->sections, true);
    }
    // Determine the combined title based on all available data
    if((!empty($translation->exclude) || !empty($translation->include)) && !empty($sections)){
        $title = __("Included/Excluded");
    }
@endphp
<style>.dash-list {
    list-style-type: none;
    padding-left: 20px; /* Adjust the padding as needed */
}

.dash-list li::before {
    content: "-"; /* Insert dash before each list item */
    display: inline-block;
    width: 1em; /* Adjust spacing between dash and text if needed */
    margin-left: -20px; /* Negative margin to align the dash with the text */
}


</style>
@if(!empty($title))
    <div class="g-include-exclude">
        <h3>{{ $title }}</h3>
        <div class="row">
            @if($translation->include || $sections)
                <div class="col-lg-6 col-md-6">
                    @if($translation->include)
                        @foreach($translation->include as $item)
                            <div class="item">
                                <i class="icofont-check-alt icon-include"></i>
                                {{ $item['title'] }}
                            </div>
                        @endforeach
                    @endif
                    @if(!empty($sections))
                    <div class="sections-container" style="margin-top: 50px;"> <!-- Added a margin top here -->
                        @foreach($sections as $section)
                            <h3>{{ $section['title'] }}</h3>
                            <div class="item">
                                @if(!empty($section['items']))
                                <ul class="dash-list">
                                    @foreach($section['items'] as $item)
                                        <li style="margin-bottom: 15px;">{{ $item['title'] }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                
                </div>
            @endif
            @if($translation->exclude)
                <div class="col-lg-6 col-md-6">
                    @foreach($translation->exclude as $item)
                        <div class="item">
                            <i class="icofont-close-line icon-exclude"></i>
                            {{ $item['title'] }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
