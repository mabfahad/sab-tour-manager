<?php

namespace controller;

class Sab_Page
{
    private $page_slug;

    public function __construct($slug = 'tour-booking')
    {
        $this->page_slug = $slug;
    }

    /**
     * Create page if not exists
     */
    public function create_page($title, $content = '')
    {
        if (get_page_by_path($this->page_slug)) {
            return 0; // Page exists
        }

        $page_data = [
            'post_title'   => wp_strip_all_tags($title),
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_name'    => $this->page_slug,
        ];

        $page_id = wp_insert_post($page_data);
        return $page_id;
    }

    /**
     * Get page ID by slug
     */
    public function get_page_id()
    {
        $page = get_page_by_path($this->page_slug);
        return $page ? $page->ID : 0;
    }
}
