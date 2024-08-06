@if(count($tour_related) > 0)


<style>
    /* Add this to your CSS file */
.blur-card {
    position: relative;
    background: #f4f4f4; /* Adjust background color if needed */
    display: flex;
    align-items: center;
    justify-content: center;
}

.blur-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 260px;
    height: 310px;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background for overlay */
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    padding: 20px;
    border-radius: 8px;
}

.btn-view-all {
    background-color: #007bff; /* Adjust button color */
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.btn-view-all:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

</style>
    <div class="bravo-list-tour-related">
        <h2>{{ __("You might also like") }}</h2>
        <div class="row">
            @php
                // dd($tour_related);
            @endphp
            @foreach($tour_related as $k => $item)
                @if ($k < 3) <!-- Display first 3 cards -->
                    <div class="col-md-3">
                        @include('Tour::frontend.layouts.search.loop-grid', ['row' => $item, 'include_param' => 0])
                    </div>
                @elseif ($k == 3) <!-- Display the fourth card with blur effect and "View All" button -->
                @php
                $category_id = $item->category_id; // Assuming the last item has the correct category ID
            @endphp

                    <div class="col-md-3 blur-card">
                        <div class="blur-overlay">
                            <a href="{{ url('/tour?cat_id%5B%5D=' . $category_id) }}" class="btn-view-all">
                                {{ __("View All") }}
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
