<?php

namespace controller;

class Sab_Page
{
    /**
     * Create page if not exists
     */
    public function create_page($title,$page_slug, $content = '')
    {
        if (get_page_by_path($page_slug)) {
            return 0; // Page exists
        }

        $page_data = [
            'post_title'   => wp_strip_all_tags($title),
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_name'    => $page_slug,
        ];

        $page_id = wp_insert_post($page_data);
        return $page_id;
    }

    /**
     * Get page ID by slug
     */
    public function get_page_id($page_slug)
    {
        $page = get_page_by_path($page_slug);
        return $page ? $page->ID : 0;
    }
}
