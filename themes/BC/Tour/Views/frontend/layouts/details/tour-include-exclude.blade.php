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

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<!-- Include Bootstrap CSS (if not already included) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
.dash-list {
    list-style-type: none;
    padding-left: 20px; /* Adjust the padding as needed */
}

.dash-list li::before {
    content: "-"; /* Insert dash before each list item */
    display: inline-block;
    width: 1em; /* Adjust spacing between dash and text if needed */
    margin-left: -20px; /* Negative margin to align the dash with the text */
}

.accordion-button {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background: none;
    border: none;
    padding: 0;
    font-size: 1rem;
    text-align: left;
}

.accordion-button:focus {
    outline: none;
    box-shadow: none;
}

.accordion-button .arrow-icon {
    transition: transform 0.2s;
}

.accordion-button.collapsed .arrow-icon {
    transform: rotate(180deg);
}

.card {
    border: none;
    margin-bottom: 1rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;s
    padding: 0.75rem 1.25rem;
}

.card-body {
    padding: 1.25rem;
}

/* .item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
} */

.icon-include {
    color: green;
    margin-right: 10px;
}

.icon-exclude {
    color: red;
    margin-right: 10px;
}

.card h2 {
    font-size: 1.25rem;
    font-weight: 500;
}

.card-header .btn {
    font-size: 20px;
    font-weight: bold;
    text-decoration: none;
}

.card-header .btn:hover {
    /* color: #0056b3; */
}

.g-include-exclude h3 {
    margin-bottom: 1rem;
    font-size: 2rem; /* Updated font size */
    font-weight: 600;
    color: #007bff; /* Updated color */
}
</style>

@if(!empty($title))
    <div class="g-include-exclude">
        {{-- <h3>{{ $title }}</h3> --}}
        <div class="accordion" id="accordionExample">
            @if($translation->include)
                <div class="card">
                    <div class="card-header" id="headingInclude">
                        <h2 class="mb-0">
                            <button class="btn accordion-button" type="button" data-toggle="collapse" data-target="#collapseInclude" aria-expanded="true" aria-controls="collapseInclude">
                                {{ __("Included") }}
                                <i class="fas fa-chevron-up arrow-icon"></i>
                            </button>
                        </h2>
                    </div>

                    <div id="collapseInclude" class="collapse show" aria-labelledby="headingInclude" data-parent="#accordionExample">
                        <div class="card-body">
                            @foreach($translation->include as $item)
                                <div class="item">
                                    <i class="icofont-check-alt icon-include"></i>
                                    {{ $item['title'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($translation->exclude)
                <div class="card">
                    <div class="card-header" id="headingExclude">
                        <h2 class="mb-0">
                            <button class="btn accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseExclude" aria-expanded="false" aria-controls="collapseExclude">
                                {{ __("Excluded") }}
                                <i class="fas fa-chevron-up arrow-icon"></i>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseExclude" class="collapse" aria-labelledby="headingExclude" data-parent="#accordionExample">
                        <div class="card-body">
                            @foreach($translation->exclude as $item)
                                <div class="item">
                                    <i class="icofont-close-line icon-exclude"></i>
                                    {{ $item['title'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($sections))
                @foreach($sections as $index => $section)
                    <div class="card">
                        <div class="card-header" id="headingSection{{ $index }}">
                            <h2 class="mb-0">
                                <button class="btn accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseSection{{ $index }}" aria-expanded="false" aria-controls="collapseSection{{ $index }}">
                                    {{ $section['title'] }}
                                    <i class="fas fa-chevron-up arrow-icon"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseSection{{ $index }}" class="collapse" aria-labelledby="headingSection{{ $index }}" data-parent="#accordionExample">
                            <div class="card-body">
                                @if(!empty($section['items']))
                                    <ul class="dash-list">
                                        @foreach($section['items'] as $item)
                                            <li style="margin-bottom: 15px;">{{ $item['title'] }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endif