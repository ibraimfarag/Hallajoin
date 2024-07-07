<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $html_class ?? '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php event(new \Modules\Layout\Events\LayoutBeginHead()); @endphp
    @php
        $favicon = setting_item('site_favicon');
    @endphp

    @if ($favicon)
        @php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        @endphp
        @if (!empty($file))
            <link rel="icon" type="{{ $file['file_type'] }}" href="{{ asset('uploads/' . $file['file_path']) }}" />
        @else
            <link rel="icon" type="image/png" href="{{ url('images/favicon.png') }}" />
        @endif
    @endif

    @include('Layout::parts.seo-meta')
    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" />
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/notification.css') }}" rel="newest stylesheet">
    <link href="{{ asset('dist/frontend/css/app.css?_ver=' . config('app.asset_version')) }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('libs/daterange/daterangepicker.css') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel='stylesheet' id='google-font-css-css'
        href='https://fonts.googleapis.com/css?family=Poppins%3A300%2C400%2C500%2C600&display=swap' type='text/css'
        media='all' />
    {!! \App\Helpers\Assets::css() !!}
    {!! \App\Helpers\Assets::js() !!}
    @include('Layout::parts.global-script')
    <!-- Styles -->
    @stack('css')
    {{-- Custom Style --}}
    <link href="{{ route('core.style.customCss') }}" rel="stylesheet">
    <link href="{{ asset('libs/carousel-2/owl.carousel.css') }}" rel="stylesheet">
    @if (setting_item_with_lang('enable_rtl'))
        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">
    @endif
    @if (!is_demo_mode())
        {!! setting_item('head_scripts') !!}
        {!! setting_item_with_lang_raw('head_scripts') !!}
    @endif

</head>

<body
    class="frontend-page {{ !empty($row->header_style) ? 'header-' . $row->header_style : 'header-normal' }} {{ $body_class ?? '' }} @if (setting_item_with_lang('enable_rtl')) is-rtl @endif @if (is_api()) is_api @endif">
    @if (!is_demo_mode())
        {!! setting_item('body_scripts') !!}
        {!! setting_item_with_lang_raw('body_scripts') !!}
    @endif
    <div class="bravo_wrap">
        @if (!is_api())
            {{-- @include('Layout::parts.topbar') --}}
            @include('Layout::parts.header')
        @endif

        @yield('content')

        @include('Layout::parts.footer')
    </div>
    @if (!is_demo_mode())
        {!! setting_item('footer_scripts') !!}
        {!! setting_item_with_lang_raw('footer_scripts') !!}
    @endif

    <!-- WhatsApp Icon -->
    <div class="whatsapp-icon" onclick="openWhatsApp()">
        <i class="fab fa-whatsapp fa-2x"></i>
    </div>

    <!-- Scripts -->
    <script>
        function openWhatsApp() {
            var phoneNumber = "+971555506597"; // Replace with your WhatsApp phone number
            var message = "Hello, I would like to inquire about..."; // Replace with your predefined message
            var url = "https://wa.me/" + phoneNumber + "?text=" + encodeURIComponent(message);
            window.open(url, "_blank");
        }
    </script>

    <script>
        $(document).ready(function() {
            var header = $("#bravo-header");
            var headerOffset = header.offset().top;
            var searchResults = $(".search-results");

            $(window).scroll(function() {
                if ($(window).scrollTop() > headerOffset) {
                    header.addClass("fixed-header");
                    searchResults.css("top", "8vh");
                } else {
                    header.removeClass("fixed-header");
                    searchResults.css("top", "14vh");
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var inputEl = $('.search-input');
            var resultsContainer = $('.search-results');

            inputEl.on('input', function() {
                var query = $(this).val().trim();

                // Clear previous results
                resultsContainer.html('');

                if (query.length > 0) {
                    $.ajax({
                        url: '/searchTours',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            query: query
                        }),
                        success: function(response) {
                            if (response.tours.length > 0) {
                                response.tours.forEach(function(tour) {
                                    var resultEl = $('<a>')
                                        .addClass('result-item')
                                        .attr('href', '/tour/' + tour.slug)
                                        .css({
                                            display: 'flex', // Use flexbox for layout
                                            alignItems: 'center', // Align items vertically
                                            padding: '10px',
                                            borderBottom: '1px solid #ccc',
                                            textDecoration: 'none',
                                            color: '#000'
                                        });

                                    // Create a div for the image column
                                    var imageColumnEl = $('<div>')
                                        .addClass('image-column')
                                        .css({
                                            marginRight: '10px' // Add spacing between image column and text column
                                        });

                                    // Create an image element and set its source to the tour's image URL
                                    var imageEl = $('<img>')
                                        .addClass('tour-image')
                                        .attr('src', tour
                                            .image_url
                                        ) // Set the source attribute to the tour's image URL
                                        .css({
                                            width: '70px', // Set image width to 70px
                                            borderRadius: '11px' // Set border radius to 11px
                                            // Adjust image width as needed
                                        });

                                    imageColumnEl.append(
                                        imageEl
                                    ); // Append the image element to the image column

                                    // Create a div for the text column
                                    var textColumnEl = $('<div>')
                                        .addClass('text-column');

                                    var titleEl = $('<div>')
                                        .addClass('tour-title')
                                        .text(tour.title)
                                        .css({
                                            fontSize: '16px',
                                            fontWeight: 'bold'
                                        });

                                    var locationEl = $('<div>')
                                        .addClass('tour-location')
                                        .html(
                                            '<i class="fas fa-flag"></i> United Emirates' +
                                            tour.location
                                        ) // Add UAE flag icon before location
                                        .css({
                                            fontSize: '14px',
                                            color: '#555'
                                        });

                                    textColumnEl.append(titleEl);
                                    textColumnEl.append(locationEl);

                                    resultEl.append(
                                        imageColumnEl
                                    ); // Append the image column to the result element
                                    resultEl.append(
                                        textColumnEl
                                    ); // Append the text column to the result element

                                    resultsContainer.append(resultEl);

                                });
                                resultsContainer.show();
                            } else {
                                resultsContainer.hide();
                            }
                        },
                        error: function() {
                            resultsContainer.hide();
                        }
                    });
                } else {
                    resultsContainer.hide();
                }
            });

            // Hide results container when clicking outside of it
            $(document).on('click', function(event) {
                if (!resultsContainer.is(event.target) && !inputEl.is(event.target) && resultsContainer.has(
                        event.target).length === 0) {
                    resultsContainer.hide();
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.locationowl').owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    600: {
                        items: 3,
                    },
                    1000: {
                        items: 5,
                    }
                }
            });
        });
    </script>




    <script>
    $(document).ready(function() {
    $(".bravo-box-category-tour ").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 5
            },
            600: {
                items: 9
            },
            1000: {
                items: 10
            }
        }
    });
});

    </script>

    <script>
        $(document).ready(function() {
            var inputEl = $('.search-input-mobile');
            var resultsContainer = $('.search-results-mobile');

            $('#search-toggle-btn').on('click', function() {
                var searchInput = $('#typing-placeholder-mobile');
                if (searchInput.css('display') === 'none') {
                    searchInput.css('display', 'block');
                } else {
                    searchInput.css('display', 'none');
                    resultsContainer.hide();
                }
            });

            inputEl.on('input', function() {
                var query = $(this).val().trim();

                // Clear previous results
                resultsContainer.html('');

                if (query.length > 0) {
                    $.ajax({
                        url: '/searchTours',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            query: query
                        }),
                        success: function(response) {
                            if (response.tours.length > 0) {
                                response.tours.forEach(function(tour) {
                                    var resultEl = $('<a>')
                                        .addClass('result-item')
                                        .attr('href', '/tour/' + tour.slug);

                                    var imageColumnEl = $('<div>')
                                        .addClass('image-column');

                                    var imageEl = $('<img>')
                                        .addClass('tour-image')
                                        .attr('src', tour.image_url);

                                    imageColumnEl.append(imageEl);

                                    var textColumnEl = $('<div>')
                                        .addClass('text-column');

                                    var titleEl = $('<div>')
                                        .addClass('tour-title')
                                        .text(tour.title);

                                    var locationEl = $('<div>')
                                        .addClass('tour-location')
                                        .html(
                                            '<i class="fas fa-flag"></i> United Emirates' +
                                            tour.location
                                        ) // Add UAE flag icon before location
                                    textColumnEl.append(titleEl);
                                    textColumnEl.append(locationEl);

                                    resultEl.append(imageColumnEl);
                                    resultEl.append(textColumnEl);

                                    resultsContainer.append(resultEl);
                                });
                                resultsContainer.show();
                            } else {
                                resultsContainer.hide();
                            }
                        },
                        error: function() {
                            resultsContainer.hide();
                        }
                    });
                } else {
                    resultsContainer.hide();
                }
            });

            $(document).on('click', function(event) {
                if (!resultsContainer.is(event.target) && !inputEl.is(event.target) && resultsContainer.has(
                        event.target).length === 0) {
                    resultsContainer.hide();
                }
            });
        });
    </script>


</body>

</html>
