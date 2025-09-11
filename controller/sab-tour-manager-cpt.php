<?php

namespace controller;

class Sab_CPT
{
    public function __construct()
    {
        // Register hooks for CPT and taxonomy
        add_action('init', [$this, 'register_trips_cpt']);
        add_action('init', [$this, 'register_destinations_taxonomy']);
    }

    /**
     * Register Trips Custom Post Type
     */
    public function register_trips_cpt()
    {
        $labels = [
            'name'               => 'Trips',
            'singular_name'      => 'Trip',
            'menu_name'          => 'Trips',
            'name_admin_bar'     => 'Trip',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Trip',
            'new_item'           => 'New Trip',
            'edit_item'          => 'Edit Trip',
            'view_item'          => 'View Trip',
            'all_items'          => 'All Trips',
            'search_items'       => 'Search Trips',
            'not_found'          => 'No trips found',
            'not_found_in_trash' => 'No trips found in Trash',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'trips'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest'       => true, // Enable Gutenberg
        ];

        register_post_type('trips', $args);
    }

    /**
     * Register Destinations taxonomy (hierarchical)
     */
    public function register_destinations_taxonomy()
    {
        $labels = [
            'name'              => 'Destinations',
            'singular_name'     => 'Destination',
            'search_items'      => 'Search Destinations',
            'all_items'         => 'All Destinations',
            'parent_item'       => 'Parent Destination',
            'parent_item_colon' => 'Parent Destination:',
            'edit_item'         => 'Edit Destination',
            'update_item'       => 'Update Destination',
            'add_new_item'      => 'Add New Destination',
            'new_item_name'     => 'New Destination Name',
            'menu_name'         => 'Destinations',
        ];

        $args = [
            'hierarchical'      => true, // Make it hierarchical like categories
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'destination'],
            'show_in_rest'      => true, // Gutenberg support
        ];

        register_taxonomy('destinations', ['trips'], $args);
    }
}

new Sab_CPT();