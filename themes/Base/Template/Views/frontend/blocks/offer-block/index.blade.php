@if (!empty($list_item))
    <div class="bravo-offer">
        <div class="container">
            <!-- Carousel -->
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="5000">
                <ol class="carousel-indicators">
                    @foreach ($list_item as $key => $item)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                            class="@if ($key == 0) active @endif"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($list_item as $key => $item)
                        <div class="carousel-item @if ($key == 0) active @endif">
                            <div class="item">
                                @if (!empty($item['link_more']))
                                    <a href="{{ $item['link_more'] }}">
                                        @if (!empty($item['background_image']))
                                            <img src="{{ get_file_url($item['background_image'], 'full') }}"
                                                alt="{{ $item['title'] }}" class="carousel-image">
                                        @endif
                                    </a>
                                @else
                                    @if (!empty($item['background_image']))
                                        <img src="{{ get_file_url($item['background_image'], 'full') }}"
                                            alt="{{ $item['title'] }}" class="carousel-image">
                                    @endif
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
@endif
