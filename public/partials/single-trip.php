<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();

        // Custom fields
        $start_date   = get_post_meta(get_the_ID(), '_trip_start_date', true);
        $end_date     = get_post_meta(get_the_ID(), '_trip_end_date', true);
        $price        = get_post_meta(get_the_ID(), '_trip_price', true);
        $type         = get_post_meta(get_the_ID(), '_trip_type', true);

        // Destinations taxonomy
        $destinations = wp_get_post_terms(get_the_ID(), 'destinations');

        // Featured image
        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');

        // Breadcrumbs (basic example; you might enhance with Yoast breadcrumbs or custom)
        $home_url = esc_url(home_url('/'));
        $destination_name = !empty($destinations) ? esc_html($destinations[0]->name) : '';

        $trip_helpers = new \controller\Sab_Helpers();
        $trip_days = $trip_helpers->sab_trip_duration($start_date, $end_date);
        $trip_nights = $trip_days -1;
        ?>

        <div class="trip-details-single-main-wrapper">

            <div class="trip-details-breadcrumbs-title-social-wrapper">
                <div class="trip-details-breadcrumbs">
                    <a href="<?php echo $home_url; ?>">Home</a>
                    <?php if ($destination_name): ?>
                        &gt; <a href="#"><?php echo $destination_name; ?></a>
                    <?php endif; ?>
                    &gt; <span><?php the_title(); ?></span>
                </div>

                <div class="trip-details-title-social">
                    <h1><?php the_title(); ?></h1>
                    <div class="trip-details-social-icons">
                        <p><?php esc_html_e('Share on', 'text-domain'); ?></p>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
                            <img src="<?php echo SAB_URL; ?>/img/fb.svg" alt="Facebook">
                        </a>
                        <a href="https://www.instagram.com/?url=<?php the_permalink(); ?>" target="_blank">
                            <img src="<?php echo SAB_URL; ?>/img/instragram.svg" alt="Instagram">
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank">
                            <img src="<?php echo SAB_URL; ?>/img/twitter.svg" alt="Twitter">
                        </a>
                    </div>
                </div>
            </div>

            <div class="trip-details-image-pricing-wrapper">
                <div class="trip-details-featured-image">
                    <?php if ($featured_image): ?>
                        <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                </div>

                <div class="trip-details-pricing-wrapper">
                    <div class="trip-details-pricing-inner">
                        <?php if ($start_date && $end_date): ?>
                            <p class="trip-details-duration"><?php echo esc_html($trip_days.'/'.$trip_nights.' Nights incl travel days')?></p>
                        <?php endif; ?>

                        <?php if ($price): ?>
                            <p class="trip-details-price"><?php esc_html_e('From', 'text-domain'); ?> <span><?php echo esc_html($price); ?></span> SEK <?php esc_html_e('per person', 'text-domain'); ?></p>
                        <?php endif; ?>

                        <?php if ($type): ?>
                            <p class="trip-details-type"><?php echo esc_html($type); ?></p>
                        <?php endif; ?>

                        <button><?php esc_html_e('Contact us for travel suggestions', 'text-domain'); ?></button>
                    </div>
                </div>
            </div>

            <div class="trip-details-overview">
                <p><?php esc_html_e('Overview', 'text-domain'); ?></p>
                <button><?php esc_html_e('Contact us for travel suggestions', 'text-domain'); ?></button>
            </div>

            <div class="trip-details-content">
                <?php the_content(); ?>
            </div>

        </div>

    <?php endwhile;
endif;

get_footer();
