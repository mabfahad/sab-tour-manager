<?php

namespace controller;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Sab_Trip_Meta
{
    public function __construct()
    {
        add_action('carbon_fields_register_fields', [$this, 'register_trip_fields']);
    }

    /**
     * Register post meta fields for trips CPT
     */
    public function register_trip_fields()
    {
        Container::make('post_meta', __('Trip Details'))
            ->where('post_type', '=', 'trips') // only for trips CPT
            ->add_fields([
                Field::make('date', 'trip_start_date', __('Trip Start Date'))
                ->set_required(true),
                Field::make('date', 'trip_end_date', __('Trip End Date'))
                ->set_required(true),
                Field::make('text', 'trip_price', __('Trip Price'))
                ->set_required(true),
                //Tour Types like campaint or Ny
                Field::make('select', 'trip_type', __('Trip Type'))
                ->set_options([
                    'campaign' => 'Campaign',
                    'Ny' => 'Ny'
                ])->set_required(true),
            ]);
    }
}

new Sab_Trip_Meta();