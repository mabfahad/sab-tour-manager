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

    public function sab_custom_pagination($paged, $max_pages)
    {
        $total_pages  = $max_pages;
        $current_page = $paged;

        if ($total_pages <= 1) {
            return ''; // no pagination needed
        }

        $html = '<div class="all-travel-types-pagination-inner">';

        // Previous button
        if ($current_page > 1) {
            $html .= '<a href="' . get_pagenum_link($current_page - 1) . '" class="all-travel-pagination-prev-btn">Previous</a>';
        } else {
            $html .= '<span class="all-travel-pagination-prev-btn disabled">Previous</span>';
        }

        // Page numbers
        $html .= '<div class="all-travel-types-pagination-numbers">';
        $dots_shown = false;

        for ($i = 1; $i <= $total_pages; $i++) {
            // Show first 2, last 2, and 2 around current
            if ($i <= 2 || $i > $total_pages - 2 || ($i >= $current_page - 1 && $i <= $current_page + 1)) {
                $active = ($i == $current_page) ? ' active' : '';
                $html  .= '<a href="' . get_pagenum_link($i) . '" class="all-travel-pagination-number' . $active . '">' . $i . '</a>';
                $dots_shown = false;
            } else {
                if (!$dots_shown) {
                    $html .= '<span class="all-travel-pagination-dots">..</span>';
                    $dots_shown = true;
                }
            }
        }

        $html .= '</div>'; // close numbers

        // Next button
        if ($current_page < $total_pages) {
            $html .= '<a href="' . get_pagenum_link($current_page + 1) . '" class="all-travel-pagination-next-btn">Next</a>';
        } else {
            $html .= '<span class="all-travel-pagination-next-btn disabled">Next</span>';
        }

        $html .= '</div>'; // close inner

        return $html;
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