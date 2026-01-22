/**
 * Bootstrap Dropdown Fix
 * This script fixes common dropdown issues by ensuring proper IDs and attributes
 */
SafeDOM.jQuery(function ($) {
    if (!$) {
        console.warn('Bootstrap dropdown fix: jQuery not available');
        return;
    }
    // Fix missing IDs for dropdown toggles
    $('[data-toggle="dropdown"]').each(function (index) {
        var $toggle = $(this);
        var $menu = $toggle.closest('.dropdown, .dropdown-cart').find('.dropdown-menu, .cart-dropdown-menu');

        if (!$toggle.attr('id')) {
            var uniqueId = 'dropdownToggle' + Date.now() + index;
            $toggle.attr('id', uniqueId);

            if ($menu.length) {
                $menu.attr('aria-labelledby', uniqueId);
            }
        }
    });

    // Additional safety check for null elements
    $(document).on('click', '[data-toggle="dropdown"]', function (e) {
        var $this = $(this);
        var $menu = $this.closest('.dropdown, .dropdown-cart').find('.dropdown-menu, .cart-dropdown-menu');

        if (!$menu.length) {
            console.warn('Bootstrap dropdown: No dropdown menu found for toggle', $this);
            e.preventDefault();
            return false;
        }

        // Ensure the toggle has proper attributes
        if (!$this.attr('aria-haspopup')) {
            $this.attr('aria-haspopup', 'true');
        }
        if (!$this.attr('aria-expanded')) {
            $this.attr('aria-expanded', 'false');
        }
    });

    // Handle dropdown state changes
    $('.dropdown, .dropdown-cart').on('show.bs.dropdown', function () {
        $(this).find('[data-toggle="dropdown"]').attr('aria-expanded', 'true');
    });

    $('.dropdown, .dropdown-cart').on('hide.bs.dropdown', function () {
        $(this).find('[data-toggle="dropdown"]').attr('aria-expanded', 'false');
    });

    // Special handling for cart dropdown to prevent Popper.js errors
    $('.dropdown-cart [data-toggle="dropdown"]').on('click', function (e) {
        var $this = $(this);
        var $parent = $this.closest('.dropdown-cart');
        var $menu = $parent.find('.cart-dropdown-menu');

        if (!$menu.length) {
            e.preventDefault();
            return false;
        }

        // Manual toggle for cart dropdown
        if ($parent.hasClass('show') || $parent.hasClass('open')) {
            $parent.removeClass('show open');
            $menu.removeClass('show').hide();
            $this.attr('aria-expanded', 'false');
        } else {
            // Close all other dropdowns first
            $('.dropdown.show, .dropdown-cart.show, .dropdown.open, .dropdown-cart.open').removeClass('show open');
            $('.dropdown-menu, .cart-dropdown-menu').removeClass('show').hide();

            $parent.addClass('show open');
            $menu.addClass('show').show();
            $this.attr('aria-expanded', 'true');
        }

        e.preventDefault();
        e.stopPropagation();
    });

    // Close cart dropdown when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown-cart').length) {
            $('.dropdown-cart').removeClass('show open');
            $('.cart-dropdown-menu').removeClass('show').hide();
            $('.dropdown-cart [data-toggle="dropdown"]').attr('aria-expanded', 'false');
        }
    });
});
