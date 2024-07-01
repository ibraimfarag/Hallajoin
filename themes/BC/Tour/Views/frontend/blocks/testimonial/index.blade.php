@if (!empty($reviews))
    <div id="testimonial-carousel" class="bravo-testimonial carousel slide" data-ride="carousel" data-interval="3000">
        <div class="container">
            <h3>{{ $title }}</h3>
            <div class="carousel-inner">
                @foreach ($reviews as $index => $review)
                    <div class="carousel-item{{ $index == 0 ? ' active' : '' }}">
                        <div class="row justify-content-center">
                            <div class="col-md-4 col-sm-12">
                                <div class="testimonial-container">
                                    <?php
                                    $avatar_url = get_file_url($review['reviewer']['avatar_id'], 'full') ?? 'https://img.freepik.com/premium-vector/young-smiling-man-avatar-man-with-brown-beard-mustache-hair-wearing-yellow-sweater-sweatshirt-3d-vector-people-character-illustration-cartoon-minimal-style_365941-860.jpg';
                                    $tour_image_url = get_file_url($review['tour']['banner_image_id'], 'full') ?? 'https://img.freepik.com/premium-vector/young-smiling-man-avatar-man-with-brown-beard-mustache-hair-wearing-yellow-sweater-sweatshirt-3d-vector-people-character-illustration-cartoon-minimal-style_365941-860.jpg';
                                    $tour_link = '/tour/' . $review['tour']['slug'];
                                    ?>
                                    <div class="testimonial-header" style="background-image: url('{{ $tour_image_url }}'); position: relative; padding: 20px;">
                                        <h6>{{ $review['tour']['title'] }}</h6>
                                        @if (!empty($review['rate_number']) && $review['rate_number'] >= 1)
                                            <div class="rate-number">
                                                <span> <i class="fa fa-star orange"></i>
                                                    {{ $review['rate_number'] }}
                                                    ({{ $review['tour']['reviews_count'] }})
                                                </span>
                                            </div>
                                        @endif
                                        <a href="{{ $tour_link }}" class="tour-link">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    <div class="item has-matchHeight text-center justify-content-center">
                                        <div class="authoor justify-content-center">
                                            <div class="author justify-content-center">
                                                <div class="col">
                                                    <div class="row justify-content-center">
                                                        <img src="{{ $avatar_url }}" alt="{{ $review['reviewer']['name'] }}" class="rounded-circle">
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        <h4>{{ $review['reviewer']['name'] }}</h4>
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        <p>{{ $review['date'] }}</p>
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        @if (!empty($review['rate_number']) && $review['rate_number'] >= 1)
                                                            <div class="star">
                                                                @for ($i = 0; $i < $review['rate_number']; $i++)
                                                                    <i class="fa fa-star orange"></i>
                                                                @endfor
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <i class="fa fa-quote-left" style="font-size: 25px; color: #8080807d;"></i>
                                            <p>{{ $review['content'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#testimonial-carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#testimonial-carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            <ol class="carousel-indicators">
                @foreach ($reviews as $index => $indicator)
                    <li data-target="#testimonial-carousel" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ol>
        </div>
    </div>

    <style>
        .authoor {
            top: 140px;
            position: absolute;
            left: 0;
            right: 0;
        }

        .testimonial-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .testimonial-header {
            position: relative;
            height: 200px;
            /* Adjust as needed */
            background-size: cover;
            background-position: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            color: #fff;
            padding: 20px;
        }

        .testimonial-header h6 {
            margin-bottom: 0;
            position: absolute;
            top: 20px;
            left: 15px;
            font-size: 14px;
            font-weight: normal;
            background: #0006;
            padding: 5px 10px;
            border-radius: 13px;
        }

        .rate-number {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: rgb(255 247 247);
            padding: 5px 13px;
            border-radius: 15px;
            color: #000;
        }

        .tour-link {
            position: absolute;
            bottom: 10px;
            right: 10px;
            padding: 5px;
            border-radius: 5px;
            color: white;
            font-size: 18px;
            text-decoration: none;
            z-index: 99999;
        }

        @media (min-width: 768px) {
            .carousel-inner .carousel-item {
                /* display: flex; */
                flex-wrap: wrap;
                justify-content: center;
            }
            .carousel-inner .carousel-item .col-md-4 {
                display: block;
            }
        }

        @media (max-width: 767.98px) {
            .carousel-inner .carousel-item .col-md-4 {
                display: none;
            }
            .carousel-inner .carousel-item .col-sm-12 {
                display: block;
            }
        }
    </style>
@endif
