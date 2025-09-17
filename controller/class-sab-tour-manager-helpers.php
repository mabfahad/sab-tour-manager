<?php

namespace controller;

class Sab_Helpers
{
    //Get all destinations
    public function get_all_destinations()
    {
        $destinations = get_terms([
            'taxonomy' => 'destinations',
            'hide_empty' => true
        ]);
        return $destinations;
    }

    public function get_all_available_trips()
    {

    }

    public function sab_custom_pagination($query = null)
    {
        if (!$query) {
            global $wp_query;
            $query = $wp_query;
        }

        $total_pages = $query->max_num_pages;
        $current_page = max(1, get_query_var('paged'));

        if ($total_pages <= 1) {
            return; // no pagination needed
        }

        echo '<div class="all-travel-types-pagination">';
        echo '<div class="all-travel-types-pagination-inner">';

        // Previous button
        if ($current_page > 1) {
            echo '<a href="' . get_pagenum_link($current_page - 1) . '" class="all-travel-pagination-prev-btn">Previous</a>';
        } else {
            echo '<span class="all-travel-pagination-prev-btn disabled">Previous</span>';
        }

        // Page numbers
        echo '<div class="all-travel-types-pagination-numbers">';
        $dots_shown = false;

        for ($i = 1; $i <= $total_pages; $i++) {

            // Show first 2, last 2, and 2 around current
            if ($i <= 2 || $i > $total_pages - 2 || ($i >= $current_page - 1 && $i <= $current_page + 1)) {
                $active = ($i == $current_page) ? ' active' : '';
                echo '<a href="' . get_pagenum_link($i) . '" class="all-travel-pagination-number' . $active . '">' . $i . '</a>';
                $dots_shown = false;
            } else {
                if (!$dots_shown) {
                    echo '<span class="all-travel-pagination-dots">..</span>';
                    $dots_shown = true;
                }
            }
        }

        echo '</div>'; // close numbers

        // Next button
        if ($current_page < $total_pages) {
            echo '<a href="' . get_pagenum_link($current_page + 1) . '" class="all-travel-pagination-next-btn">Next</a>';
        } else {
            echo '<span class="all-travel-pagination-next-btn disabled">Next</span>';
        }

        echo '</div>'; // close inner
        echo '</div>'; // close outer
    }

    public function sab_trip_duration($start_date, $end_date){
        $start_date = get_post_meta(get_the_ID(), '_trip_start_date', true);
        $end_date   = get_post_meta(get_the_ID(), '_trip_end_date', true);

        $days = 0;

        if ($start_date && $end_date) {
            $start = new \DateTime($start_date);
            $end   = new \DateTime($end_date);
            $diff  = $start->diff($end);
            $days  = $diff->days + 1; // +1 if you want to include both start and end day
        }

        return $days . ' Days';


    }
}