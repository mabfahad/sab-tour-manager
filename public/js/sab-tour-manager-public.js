(function ($) {
    'use strict';

    $(document).ready(function () {

        // Store the last applied filter globally
        let currentFilterData = {};

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
                    min: parseInt($('#duration-min').val()) || 3,  // parse to int
                    max: parseInt($('#duration-max').val()) || 25  // parse to int
                },
                price: {
                    min: parseInt($('#taken-min').val()) || 16000,
                    max: parseInt($('#taken-max').val()) || 95000
                }
            };
        };


        /**
         * AJAX filter function
         */
        const applyFilter = (filterData) => {
            currentFilterData = filterData; // save last applied filter
            $.ajax({
                url: tripsData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'filter_trips',
                    filterData
                },
                beforeSend: () => {
                    $('.all-travel-types-list-items, .all-travel-types-pagination').css('opacity', '0.5');
                },
                success: response => {
                    const tripsHtml = response.success ? response.data.html : '<p>No trips found.</p>';
                    const paginationHtml = response.success && response.data.pagination ? response.data.pagination : '';

                    $('.all-travel-types-list-items').html(tripsHtml).css('opacity', '1');
                    $('.all-travel-types-pagination').html(paginationHtml).css('opacity', '1');
                }
            });
        };


        /**
         * Handle dropdown and checkboxes
         */
        $('#destination-locations').on('change', function () {
            const selected = $(this).val();
            $('.destination-items input[type="checkbox"]').prop('checked', false);
            selected === 'all' ? $('#all').prop('checked', true) : $('#' + selected).prop('checked', true);
        });

        $(document).on('change', '#all', function () {
            if ($(this).is(':checked')) $('.destination-items input[name="destination[]"]').prop('checked', false);
        });

        $(document).on('change', '.destination-items input[name="destination[]"]', function () {
            $('#all').prop('checked', $('.destination-items input[name="destination[]"]:checked').length === 0);
        });


        /**
         * Handle Apply Filter / View Available Trips clicks
         */
        $('.filter-apply-button button, .view_available_trips').on('click', function (e) {
            e.preventDefault();
            const filterData = getFilterData();
            applyFilter(filterData);
            $('.filter-modal').fadeOut();
        });


        /**
         * Extract paged number from href
         */
        function getPagedFromHref(href) {
            let paged = 1;
            try {
                const url = new URL(href, window.location.origin);
                if (url.searchParams.has('paged')) {
                    paged = parseInt(url.searchParams.get('paged'));
                } else {
                    const match = url.pathname.match(/\/page\/(\d+)\//);
                    if (match) paged = parseInt(match[1]);
                }
            } catch (e) {
                console.warn('Invalid URL in pagination', href);
            }
            return paged;
        }


        /**
         * Pagination click handling
         */
        $(document).on('click', '.all-travel-pagination-number, .all-travel-pagination-prev-btn, .all-travel-pagination-next-btn', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const paged = getPagedFromHref(href);
            if (!paged) return;

            let filterData = {...currentFilterData, paged}; // merge last filter with new page
            applyFilter(filterData);
        });

    });

})(jQuery);
