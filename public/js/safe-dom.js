/**
 * Safe DOM Manipulation Library
 * Ensures all DOM operations are performed safely and jQuery is loaded
 */
(function (window, document) {
    'use strict';

    // Wait for both DOM and jQuery to be ready
    function whenReady(callback) {
        if (typeof callback !== 'function') {
            console.error('whenReady: callback must be a function');
            return;
        }

        function checkReady() {
            if (document.readyState === 'complete' && typeof $ !== 'undefined') {
                callback();
            } else {
                setTimeout(checkReady, 50);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', checkReady);
        } else {
            checkReady();
        }
    }

    // Safe element selector with null checks
    function safeSelect(selector) {
        try {
            return document.querySelector(selector);
        } catch (e) {
            console.warn('Invalid selector:', selector, e);
            return null;
        }
    }

    // Safe event listener addition
    function safeAddEventListener(selector, event, handler) {
        const element = typeof selector === 'string' ? safeSelect(selector) : selector;

        if (!element) {
            console.warn('Element not found for selector:', selector);
            return false;
        }

        if (typeof handler !== 'function') {
            console.error('Event handler must be a function');
            return false;
        }

        try {
            element.addEventListener(event, handler);
            return true;
        } catch (e) {
            console.error('Failed to add event listener:', e);
            return false;
        }
    }

    // Safe jQuery operations - can be used as a callback or getter
    function safeJQuery(callback) {
        if (typeof callback === 'function') {
            // Used as callback: SafeDOM.jQuery(function($) { ... })
            whenReady(function () {
                if (typeof $ === 'undefined') {
                    console.error('jQuery is not available');
                    return;
                }
                callback($);
            });
        } else {
            // Used as getter: var $ = SafeDOM.jQuery();
            if (typeof $ === 'undefined') {
                console.error('jQuery is not available');
                return undefined;
            }
            return $;
        }
    }

    // Export to global scope
    window.SafeDOM = {
        whenReady: whenReady,
        select: safeSelect,
        addEvent: safeAddEventListener,
        jQuery: safeJQuery
    };

    // Legacy support
    window.safeReady = whenReady;

})(window, document);