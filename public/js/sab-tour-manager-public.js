(function ($) {
    'use strict';

    $(document).ready(function () {
        // Open filter modal
        $('.all-travels-filter-button button').on('click', function (e) {
            e.preventDefault();
            alert('ok');
            $('.filter-modal').fadeIn();
        });

        // Close filter modal
        $('.filter-close-button, .filter-modal-overlay').on('click', function () {
            $('.filter-modal').fadeOut();
        });

        // Duration slider -> number inputs
        $('#duration').on('input', function () {
            var value = $(this).val();
            $('#duration-min').val(3); // or your min logic
            $('#duration-max').val(value);
        });

        // Duration number inputs
        $('#duration-min, #duration-max').on('input', function () {
            var min = parseInt($('#duration-min').val()) || 3;
            var max = parseInt($('#duration-max').val()) || 25;

            // make sure min <= max
            if (min > max) min = max;
            if (max < min) max = min;

            $('#duration').val(max); // set slider to max
            $('#duration-min').val(min);
            $('#duration-max').val(max);
        });

        // Taken slider
        $('#taken').on('input', function () {
            var value = $(this).val();
            $('#taken-min').val(16000); // or your min logic
            $('#taken-max').val(value);
        });

        // Taken number inputs
        $('#taken-min, #taken-max').on('input', function () {
            var min = parseInt($('#taken-min').val()) || 16000;
            var max = parseInt($('#taken-max').val()) || 95000;

            if (min > max) min = max;
            if (max < min) max = min;

            $('#taken').val(max);
            $('#taken-min').val(min);
            $('#taken-max').val(max);
        });

        //view_available_trips
        $('.view_available_trips').on('click', function (e) {
            e.preventDefault();
            var selected = $('#destination-locations').val();
            $.ajax({
                url: tripsData.ajaxUrl, // localized in PHP
                type: 'POST',
                data: {
                    action: 'filter_trips',
                    destination: selected
                },
                beforeSend: function () {
                    $('.all-travel-types-list-items').css('opacity', '0.5');
                },
                success: function (response) {
                    if (response.success) {
                        $('.all-travel-types-list-items').html(response.data.html);
                    } else {
                        $('.all-travel-types-list-items').html('<p>No trips found.</p>');
                    }
                    $('.all-travel-types-list-items').css('opacity', '1');
                }
            });
        })
    });

})(jQuery);
