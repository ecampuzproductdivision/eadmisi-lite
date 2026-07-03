(function ($) {
    'use strict';

    $(document).ready(function() {
        const locationDropdown = $('.select2-location-search');

        // Failsafe: Destroy any pre-existing instance to prevent double-init conflict
        if (locationDropdown.hasClass("select2-hidden-accessible")) {
            locationDropdown.select2('destroy');
        }

        locationDropdown.select2({
            placeholder: "-- Ketik nama Kabupaten atau Kota --",
            allowClear: true,
            tags: true, // Ensures custom text entry is captured if city is missing
            width: '100%'
        });
    });
})(jQuery);