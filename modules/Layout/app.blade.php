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
            $file = new \Modules\Media\Models\MediaFile()->findById($favicon);
        @endphp
        @if (!empty($file))
            <link rel="icon" type="{{ $file['file_type'] }}" href="{{ asset('uploads/' . $file['file_path']) }}" />
        @else
            <link rel="icon" type="image/png" href="{{ url('images/favicon.png') }}" />
        @endif
    @endif

    @include('Layout::parts.seo-meta')
    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/notification.css') }}" rel="newest stylesheet">
    <link href="{{ asset('dist/frontend/css/app.css?_ver=' . config('app.asset_version')) }}" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.6.1/build/css/intlTelInput.css">


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

    <!-- Call Icon -->
    <div class="call-icon" onclick="openCall()">
        <i class="fas fa-phone fa-2x"></i>
    </div>


    <!-- Scripts -->
    <script>
        function openWhatsApp() {
            var phoneNumber = "+971555506597"; // Replace with your WhatsApp phone number
            var message = "Hello, I would like to inquire about..."; // Replace with your predefined message
            var url = "https://wa.me/" + phoneNumber + "?text=" + encodeURIComponent(message);
            window.open(url, "_blank");
        }

        function openCall() {
            var phoneNumber = "+971555506597"; // Replace with your phone number
            var url = "tel:" + phoneNumber;
            window.location.href = url;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var header = document.getElementById('bravo-header');
            if (header) {
                var headerOffset = header.offsetTop;
                var searchResults = document.querySelector('.search-results');

                window.addEventListener('scroll', function() {
                    if (window.scrollY > headerOffset) {
                        header.classList.add('fixed-header');
                        if (searchResults) searchResults.style.top = '8vh';
                    } else {
                        header.classList.remove('fixed-header');
                        if (searchResults) searchResults.style.top = '14vh';
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var inputEl = document.querySelector('.search-input');
            var resultsContainer = document.querySelector('.search-results');

            if (inputEl && resultsContainer) {
                inputEl.addEventListener('input', function() {
                    var query = this.value.trim();
                    resultsContainer.innerHTML = '';

                    if (query.length > 0) {
                        fetch('/searchTours', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    query: query
                                })
                            })
                            .then(response => response.json())
                            .then(response => {
                                if (response.tours && response.tours.length > 0) {
                                    response.tours.forEach(function(tour) {
                                        var resultEl = document.createElement('a');
                                        resultEl.className = 'result-item';
                                        resultEl.href = '/tour/' + tour.slug;
                                        resultEl.style.cssText =
                                            'display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #ccc; text-decoration: none; color: #000;';

                                        var imageColumnEl = document.createElement('div');
                                        imageColumnEl.className = 'image-column';
                                        imageColumnEl.style.marginRight = '10px';

                                        var imageEl = document.createElement('img');
                                        imageEl.className = 'tour-image';
                                        imageEl.src = tour.image_url;
                                        imageEl.style.cssText =
                                            'width: 70px; border-radius: 11px;';

                                        imageColumnEl.appendChild(imageEl);

                                        var textColumnEl = document.createElement('div');
                                        textColumnEl.className = 'text-column';

                                        var titleEl = document.createElement('div');
                                        titleEl.className = 'tour-title';
                                        titleEl.textContent = tour.title;
                                        titleEl.style.cssText =
                                            'font-size: 16px; font-weight: bold;';

                                        var locationEl = document.createElement('div');
                                        locationEl.className = 'tour-location';
                                        locationEl.innerHTML =
                                            '<i class="fas fa-flag"></i> United Emirates ' +
                                            tour.location;
                                        locationEl.style.cssText =
                                            'font-size: 14px; color: #555;';

                                        textColumnEl.appendChild(titleEl);
                                        textColumnEl.appendChild(locationEl);

                                        resultEl.appendChild(imageColumnEl);
                                        resultEl.appendChild(textColumnEl);

                                        resultsContainer.appendChild(resultEl);
                                    });
                                    resultsContainer.style.display = 'block';
                                } else {
                                    resultsContainer.style.display = 'none';
                                }
                            })
                            .catch(() => resultsContainer.style.display = 'none');
                    } else {
                        resultsContainer.style.display = 'none';
                    }
                });

                document.addEventListener('click', function(event) {
                    if (event.target !== resultsContainer && event.target !== inputEl && !resultsContainer
                        .contains(event.target)) {
                        resultsContainer.style.display = 'none';
                    }
                });
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var inputEl = document.querySelector('.search-input-mobile');
            var resultsContainer = document.querySelector('.search-results-mobile');
            var toggleBtn = document.getElementById('search-toggle-btn');

            if (inputEl && resultsContainer && toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    var searchInput = document.getElementById('typing-placeholder-mobile');
                    if (searchInput) {
                        if (searchInput.style.display === 'none') {
                            searchInput.style.display = 'block';
                        } else {
                            searchInput.style.display = 'none';
                            resultsContainer.style.display = 'none';
                        }
                    }
                });

                inputEl.addEventListener('input', function() {
                    var query = this.value.trim();
                    resultsContainer.innerHTML = '';

                    if (query.length > 0) {
                        fetch('/searchTours', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    query: query
                                })
                            })
                            .then(response => response.json())
                            .then(response => {
                                if (response.tours && response.tours.length > 0) {
                                    response.tours.forEach(function(tour) {
                                        var resultEl = document.createElement('a');
                                        resultEl.className = 'result-item';
                                        resultEl.href = '/tour/' + tour.slug;

                                        var imageColumnEl = document.createElement('div');
                                        imageColumnEl.className = 'image-column';

                                        var imageEl = document.createElement('img');
                                        imageEl.className = 'tour-image';
                                        imageEl.src = tour.image_url;

                                        imageColumnEl.appendChild(imageEl);

                                        var textColumnEl = document.createElement('div');
                                        textColumnEl.className = 'text-column';

                                        var titleEl = document.createElement('div');
                                        titleEl.className = 'tour-title';
                                        titleEl.textContent = tour.title;

                                        var locationEl = document.createElement('div');
                                        locationEl.className = 'tour-location';
                                        locationEl.innerHTML =
                                            '<i class="fas fa-flag"></i> United Emirates ' +
                                            tour.location;

                                        textColumnEl.appendChild(titleEl);
                                        textColumnEl.appendChild(locationEl);

                                        resultEl.appendChild(imageColumnEl);
                                        resultEl.appendChild(textColumnEl);

                                        resultsContainer.appendChild(resultEl);
                                    });
                                    resultsContainer.style.display = 'block';
                                } else {
                                    resultsContainer.style.display = 'none';
                                }
                            })
                            .catch(() => resultsContainer.style.display = 'none');
                    } else {
                        resultsContainer.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.6.1/build/js/intlTelInput.min.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.6.1/build/js/utils.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const phoneInput = document.querySelector("#phone");

            // Check if phone input exists before initializing
            if (phoneInput) {
                const iti = window.intlTelInput(phoneInput, {
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.6.1/build/js/utils.js",
                });

                // Set the default country to United Arab Emirates (AE)
                iti.setCountry("AE");

                // Function to update the input value with the country code and phone number
                function updatePhoneNumber() {
                    const countryData = iti.getSelectedCountryData();
                    const countryCode = countryData.dialCode;
                    const phoneNumber = phoneInput.value.replace(/^\+\d+\s*/,
                    ''); // Remove any existing country code
                    phoneInput.value =
                    `+${countryCode} ${phoneNumber}`; // Update the input value with the new country code
                }

                // Initialize phone number display
                updatePhoneNumber();

                // Add event listener for input changes
                phoneInput.addEventListener("input", function() {
                    updatePhoneNumber();
                });

                // Polling to detect country changes
                let previousCountryCode = iti.getSelectedCountryData().dialCode;
                setInterval(function() {
                    const currentCountryCode = iti.getSelectedCountryData().dialCode;
                    if (currentCountryCode !== previousCountryCode) {
                        previousCountryCode = currentCountryCode;
                        updatePhoneNumber();
                    }
                }, 500); // Check every 500 milliseconds
            } // End of if (phoneInput) check
        });
    </script>

    <script>
        // JavaScript to toggle password visibility
        const login_passwordField = document.getElementById('login_passwordField');
        const login_togglePassword = document.getElementById('login_togglePassword');


        const register_passwordField = document.getElementById('register_passwordField');
        const register_togglePassword = document.getElementById('register_togglePassword');

        // Check if login elements exist before adding event listeners
        if (login_passwordField && login_togglePassword) {
            login_togglePassword.addEventListener('click', function() {
                const type = login_passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                login_passwordField.setAttribute('type', type);
                // Toggle eye icon
                if (type === 'password') {
                    login_togglePassword.classList.add('icofont-eye');
                    login_togglePassword.classList.remove('icofont-eye-blocked');
                } else {
                    login_togglePassword.classList.remove('icofont-eye');
                    login_togglePassword.classList.add('icofont-eye-blocked');
                }
            });
        }

        // Check if register elements exist before adding event listeners
        if (register_passwordField && register_togglePassword) {
            register_togglePassword.addEventListener('click', function() {
                const type = register_passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                register_passwordField.setAttribute('type', type);
                // Toggle eye icon
                if (type === 'password') {
                    register_togglePassword.classList.add('icofont-eye');
                    register_togglePassword.classList.remove('icofont-eye-blocked');
                } else {
                    register_togglePassword.classList.remove('icofont-eye');
                    register_togglePassword.classList.add('icofont-eye-blocked');
                }
            });
        }
    </script>

</body>

</html>
