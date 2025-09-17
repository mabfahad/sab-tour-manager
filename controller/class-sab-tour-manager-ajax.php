<?php

namespace controller;

class Sab_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_filter_trips', [$this, 'filter_trips_callback']);
        add_action('wp_ajax_nopriv_filter_trips', [$this, 'filter_trips_callback']);
    }

    /**
     * Sanitize and normalize filter input
     */
    private function sanitize_filter($filter_data)
    {
        $destinations = [];
        if (!empty($filter_data['destinations'])) {
            $destinations = is_array($filter_data['destinations'])
                ? array_map('sanitize_text_field', $filter_data['destinations'])
                : [sanitize_text_field($filter_data['destinations'])];
        }

        $duration = [
            'min' => isset($filter_data['duration']['min']) ? absint($filter_data['duration']['min']) : 3,
            'max' => isset($filter_data['duration']['max']) ? absint($filter_data['duration']['max']) : 25
        ];

        $price = [
            'min' => isset($filter_data['price']['min']) ? absint($filter_data['price']['min']) : 16000,
            'max' => isset($filter_data['price']['max']) ? absint($filter_data['price']['max']) : 95000
        ];

        $paged = isset($filter_data['paged']) ? absint($filter_data['paged']) : 1;

        return compact('destinations', 'duration', 'price', 'paged');
    }

    /**
     * Build WP_Query arguments dynamically
     */
    private function build_query_args($filters)
    {
        $args = [
            'post_type'      => 'trips',
            'posts_per_page' => 9,
            'paged'          => $filters['paged'],
            'meta_query'     => [],
        ];

        // Taxonomy filter
        if (!empty($filters['destinations']) && !in_array('all', $filters['destinations'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'destinations',
                    'field'    => 'slug',
                    'terms'    => $filters['destinations'],
                ],
            ];
        }

        // Price filter
        if (!empty($filters['price']['min']) && !empty($filters['price']['max'])) {
            $args['meta_query'][] = [
                'key'     => '_trip_price',
                'value'   => [$filters['price']['min'], $filters['price']['max']],
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC'
            ];
        }

        return $args;
    }

    /**
     * Render trips HTML with duration filter
     */
    private function render_trips_html($query, $duration_min = 3, $duration_max = 25)
    {
        $trip_helpers = new Sab_Helpers();
        ob_start();

        while ($query->have_posts()) {
            $query->the_post();

            $start_date = get_post_meta(get_the_ID(), '_trip_start_date', true);
            $end_date   = get_post_meta(get_the_ID(), '_trip_end_date', true);

            // Skip posts outside duration range
            if ($start_date && $end_date) {
                $start = new \DateTime($start_date);
                $end   = new \DateTime($end_date);
                $days  = $start->diff($end)->days + 1;

                if ($days < $duration_min || $days > $duration_max) {
                    continue;
                }
            }

            $price_val  = get_post_meta(get_the_ID(), '_trip_price', true);
            $type       = get_post_meta(get_the_ID(), '_trip_type', true);
            ?>
            <div class="all-travel-types-list-item">
                <?php if ($type != ""){echo '<div class="travel-tag">'.esc_html($type).'</div>';}?>
                <div class="trip-duration-price">
                    <p class="trip-duration"><?php echo esc_html($trip_helpers->sab_trip_duration($start_date, $end_date)); ?></p>
                    <p class="trip-price">From SEK <?php echo esc_html($price_val); ?></p>
                </div>
                <a href="<?php the_permalink(); ?>">
                    <div class="trip-featured-image">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium_large', ['alt' => get_the_title()]);
                        } else {
                            // Fallback image from plugin folder
                            $fallback_image = SAB_URL . '/img/fallbackimage.jpg';
                            echo '<img src="' . esc_url($fallback_image) . '" alt="' . esc_attr(get_the_title()) . '" />';
                        }
                        ?>
                    </div>
                </a>
                <div class="trip-content">
                    <a href="<?php the_permalink();?>"><h3 class="trip-title"><?php the_title(); ?></h3></a>
                    <p class="trip-description"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    <div class="readmore-wrapper">
                        <a href="<?php the_permalink(); ?>" class="trip-read-more"><?php _e('Read more', 'sab-tour-manager'); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }

        wp_reset_postdata();
        return ob_get_clean();
    }

    /**
     * AJAX callback
     */
    public function filter_trips_callback()
    {
        $helpers = new Sab_Helpers();
        $filter_data = isset($_POST['filterData']) ? $_POST['filterData'] : [];
        $filters     = $this->sanitize_filter($filter_data);
        $args        = $this->build_query_args($filters);
//        echo "<pre>";print_r($args);echo "</pre>";exit();

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            $html = $this->render_trips_html($query, $filters['duration']['min'], $filters['duration']['max']);

            wp_send_json_success([
                'html'      => $html,
                'paged'     => $filters['paged'],
                'max_pages' => $query->max_num_pages,
                'pagination' => $helpers->sab_custom_pagination($filters['paged'],$query->max_num_pages)
            ]);
        } else {
            wp_send_json_error(['message' => 'No trips found.']);
        }

        wp_die();
    }
}

new Sab_Ajax();
