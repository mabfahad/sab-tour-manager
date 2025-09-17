(function ($) {
    'use strict';

    $(document).ready(function () {

        /**
         * Open/close filter modal
         */
        const toggleFilterModal = (show) => {
            $('.filter-modal').fadeToggle(show);
        };

        $('.all-travels-filter-button button').on('click', e => {
            e.preventDefault();
            toggleFilterModal(true);
        });

        $('.filter-close-button, .filter-modal-overlay').on('click', () => toggleFilterModal(false));


        /**
         * Sync slider with number inputs
         */
        const syncSlider = (sliderSelector, minInputSelector, maxInputSelector, defaultMin, defaultMax) => {
            const $slider = $(sliderSelector),
                  $min = $(minInputSelector),
                  $max = $(maxInputSelector);

            $slider.on('input', () => {
                const value = parseInt($slider.val()) || defaultMax;
                $min.val(defaultMin);
                $max.val(value);
            });

            $min.add($max).on('input', () => {
                let min = parseInt($min.val()) || defaultMin;
                let max = parseInt($max.val()) || defaultMax;

                if (min > max) min = max;
                if (max < min) max = min;

                $slider.val(max);
                $min.val(min);
                $max.val(max);
            });
        };

        // Example: duration and price sliders
        syncSlider('#duration', '#duration-min', '#duration-max', 3, 25);
        syncSlider('#taken', '#taken-min', '#taken-max', 16000, 95000);


        /**
         * AJAX filter function
         */
        const applyFilter = (filterData) => {
            $.ajax({
                url: tripsData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'filter_trips',
                    filterData
                },
                beforeSend: () => $('.all-travel-types-list-items').css('opacity', '0.5'),
                success: response => {
                    const html = response.success ? response.data.html : '<p>No trips found.</p>';
                    $('.all-travel-types-list-items').html(html).css('opacity', '1');
                }
            });
        };


        /**
         * Handle dropdown selection
         */
        $('#destination-locations').on('change', function () {
            const selected = $(this).val();
            $('.destination-items input[type="checkbox"]').prop('checked', false);
            selected === 'all' ? $('#all').prop('checked', true) : $('#' + selected).prop('checked', true);
        });

        // "All" checkbox logic
        $(document).on('change', '#all', function () {
            if ($(this).is(':checked')) $('.destination-items input[name="destination[]"]').prop('checked', false);
        });

        // Other destinations logic
        $(document).on('change', '.destination-items input[name="destination[]"]', function () {
            $('#all').prop('checked', $('.destination-items input[name="destination[]"]:checked').length === 0);
        });


        /**
         * Build filter data from modal inputs
         */
        const getFilterData = () => {
            let destinations = [];
            $('.destination-items input[name="destination[]"]:checked').each(function () {
                destinations.push($(this).val());
            });
            if ($('#all').is(':checked')) destinations = ['all'];

            return {
                destinations,
                duration: {
                    min: $('#duration-min').val(),
                    max: $('#duration-max').val()
                },
                price: {
                    min: $('#taken-min').val(),
                    max: $('#taken-max').val()
                }
            };
        };


        /**
         * Apply filters on button click
         */
        $('.filter-apply-button button, .view_available_trips').on('click', function (e) {
            e.preventDefault();
            const filterData = getFilterData();
            applyFilter(filterData);
            $('.filter-modal').fadeOut(); 

        });

    });

})(jQuery);
