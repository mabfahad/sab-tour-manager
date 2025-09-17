<?php

namespace controller;

class Sab_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_filter_trips', [$this, 'filter_trips_callback']);
        add_action('wp_ajax_nopriv_filter_trips', [$this, 'filter_trips_callback']);
    }

    public function filter_trips_callback()
    {
        $destination = isset($_POST['destination']) ? sanitize_text_field($_POST['destination']) : '';
        $paged       = isset($_POST['paged']) ? absint($_POST['paged']) : 1;

        $args = [
            'post_type'      => 'trips',
            'posts_per_page' => 9,
            'paged'          => $paged,
        ];

        // Only add tax_query if a specific destination is selected (not "all")
        if (!empty($destination) && $destination !== 'all') {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'destinations',
                    'field'    => 'slug',
                    'terms'    => $destination,
                ],
            ];
        }

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            ob_start();
            while ($query->have_posts()) {
                $query->the_post();
                ?>
                <div class="all-travel-types-list-item">

                    <div class="travel-tag">Ny</div>

                    <div class="trip-duration-price">
                        <p class="trip-duration">16 Days</p>
                        <p class="trip-price">From SEK 16,990</p>
                    </div>

                    <div class="trip-featured-image">
                        <?php if (has_post_thumbnail()) {
                            the_post_thumbnail('medium_large', ['alt' => get_the_title()]);
                        } ?>
                    </div>

                    <div class="trip-content">
                        <h3 class="trip-title"><?php the_title(); ?></h3>
                        <p class="trip-description"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                        <div class="readmore-wrapper">
                            <a href="<?php the_permalink(); ?>" class="trip-read-more"><?php _e('Read more', 'sab-tour-manager'); ?></a>
                        </div>
                    </div>

                </div>
                <?php
            }
            wp_reset_postdata();
            $html = ob_get_clean();

            wp_send_json_success([
                'html'      => $html,
                'paged'     => $paged,
                'max_pages' => $query->max_num_pages
            ]);
        } else {
            wp_send_json_error([
                'message' => 'No trips found.'
            ]);
        }
        wp_die();
    }
}
new Sab_Ajax();