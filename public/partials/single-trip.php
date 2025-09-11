<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();

        $start_date = get_post_meta(get_the_ID(), '_trip_start_date', true);
        $end_date   = get_post_meta(get_the_ID(), '_trip_end_date', true);
        $price      = get_post_meta(get_the_ID(), '_trip_price', true);
        $type       = get_post_meta(get_the_ID(), '_trip_type', true);
        $destinations = wp_get_post_terms(get_the_ID(), 'destinations', ['fields' => 'names']);
        ?>

        <div class="single-trip">
            <h1><?php the_title(); ?></h1>

            <p><strong>Start Date:</strong> <?php echo esc_html($start_date); ?></p>
            <p><strong>End Date:</strong> <?php echo esc_html($end_date); ?></p>
            <p><strong>Price:</strong> <?php echo esc_html($price); ?></p>
            <p><strong>Type:</strong> <?php echo esc_html($type); ?></p>
            <p><strong>Destinations:</strong> <?php echo esc_html(implode(', ', $destinations)); ?></p>

            <div class="trip-content">
                <?php the_content(); ?>
            </div>
        </div>

    <?php endwhile;
endif;

get_footer();
