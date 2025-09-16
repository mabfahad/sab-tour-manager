<?php

namespace controller;

class Sab_Helpers
{
    //Get all destinations
    public function get_all_destinations() {
        $destinations = get_terms([
            'taxonomy' => 'destinations',
            'hide_empty' => true
        ]);
        return $destinations;
    }

    public function get_all_available_trips() {

    }
}