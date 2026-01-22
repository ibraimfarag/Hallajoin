/**
 * Landing Page Main JavaScript
 * Initializes landing page functionality
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        // Initialize Owl Carousel for locations
        if ($('.locationowl').length) {
            $('.locationowl').owlCarousel({
                items: 4,
                margin: 20,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    }
                },
                dots: false,
                nav: true,
                autoplay: false
            });
        }

        // Initialize Owl Carousel for demos
        if ($('.demo-carousel').length) {
            $('.demo-carousel').owlCarousel({
                items: 3,
                margin: 20,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }
                },
                dots: false,
                nav: true,
                autoplay: false
            });
        }

        // Parallax effect on scroll
        $(window).on('scroll', function () {
            var scrollTop = $(this).scrollTop();
            $('.parallax').css({
                'background-position': 'center ' + (scrollTop * 0.5) + 'px'
            });
        });

        // Smooth scroll for anchors
        $('a[href^="#"]').on('click', function (e) {
            e.preventDefault();
            var target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 1000);
            }
        });

        // Sticky menu on scroll
        var stickyMenu = $('#main-menu');
        if (stickyMenu.length) {
            var stickyOffset = stickyMenu.offset().top;
            $(window).on('scroll', function () {
                if ($(this).scrollTop() > stickyOffset) {
                    stickyMenu.addClass('sticky-on');
                } else {
                    stickyMenu.removeClass('sticky-on');
                }
            });
        }

        // Match height for items
        if ($.fn.matchHeight) {
            $('.item').matchHeight();
        }
    });

})(jQuery);
