<?php
get_header();

$trip_helpers = new \controller\Sab_Helpers();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$destination = get_query_var('destination');

$destinations = $trip_helpers->get_all_destinations();

$args = [
    'post_type' => 'trips',
    'posts_per_page' => 9,
    'paged' => $paged,
];

$query = new WP_Query($args);
?>
    <div class="destinations-wrapper">
            <div class="destination-form-inner">
                <div class="destination-locations">
                    <select name="destination-locations" id="destination-locations" class="destination-locations">
                        <option value="all">All</option>
                        <?php
                        if (!is_wp_error($destinations) && !empty($destinations)) {
                            foreach ($destinations as $destination) {
                                printf(
                                    '<option value="%s">%s</option>',
                                    esc_attr($destination->slug),
                                    esc_html($destination->name)
                                );
                            }
                        }
                        ?>

                    </select>
                </div>
                <div class="destination-available-btn">
                    <button type="submit" class="view_available_trips">View available trips</button>
                </div>
            </div>
    </div>
<div class="all-travel-types-main">

    <!-- Filter Button -->
    <div class="all-travel-types-filter-nav-main">
        <div class="all-travel-types-filter-nav-inner">
            <div class="all-travels-main-title">
                <h2><?php _e('All travel types', 'sab-tour-manager'); ?></h2>
            </div>
            <div class="all-travels-filter-button">
                <button>
                    <img src="<?php echo SAB_URL."/img/filter-icon.svg"?>">
                    <span><?php _e('Filter', 'sab-tour-manager'); ?></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Trips List -->
    <div class="all-travel-types-list-main">
        <div class="all-travel-types-list-items">
            <?php if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post();
                    $start_date = get_post_meta(get_the_ID(), '_trip_start_date', true);
                    $end_date   = get_post_meta(get_the_ID(), '_trip_end_date', true);
                    $price   = get_post_meta(get_the_ID(), '_trip_price', true);
                    $type       = get_post_meta(get_the_ID(), '_trip_type', true);
                    $destinations = wp_get_post_terms(get_the_ID(), 'destinations', ['fields' => 'names']);
                ?>
                    <div class="all-travel-types-list-item">

                        <div class="travel-tag"><?php echo esc_html($type)?></div>

                        <div class="trip-duration-price">
                            <p class="trip-duration"><?php echo esc_html($trip_helpers->sab_trip_duration($start_date,$end_date))?></p>
                            <p class="trip-price">From SEK <?php echo esc_html($price);?></p>
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
                <?php endwhile; ?>
            <?php else : ?>
                <p><?php _e('No trips found.', 'sab-tour-manager'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Custom Pagination -->
    <?php $trip_helpers->sab_custom_pagination($query); ?>

</div>

<!-- Filter Modal -->
<div class="filter-modal" style="display:none;">
    <div class="filter-modal-overlay"></div>
    <div class="filter-main-wrapper">
        <span class="filter-close-button">
            <img src="<?php echo plugin_dir_url(__FILE__); ?>../img/times-icon.svg" alt="">
        </span>

        <!-- Destinations -->
        <div class="destinations-wrapper">
            <h3>Destinations</h3>
            <div class="destination-items">
                <div class="destination-item">
                    <input type="checkbox" id="all" name="all" <?php echo empty($selected) ? 'checked' : ''; ?>>
                    <label for="all">All</label>
                </div>
                <?php if ( ! empty( $destinations ) && ! is_wp_error( $destinations ) ) : ?>
                    <?php foreach( $destinations as $dest ) :
                        $checked = (!empty($destination) && in_array($dest->slug, (array) $destination)) ? 'checked' : '';
                        ?>
                        <div class="destination-item">
                            <input type="checkbox" id="<?php echo esc_attr($dest->slug); ?>" name="destination[]" value="<?php echo esc_attr($dest->slug); ?>">
                            <label for="<?php echo esc_attr($dest->slug); ?>"><?php echo esc_html($dest->name); ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Duration -->
        <div class="duration-filer-wrapper">
            <h3>Duration</h3>
            <input type="range" name="duration" id="duration" min="3" max="25">
            <div class="duration-limit-boxs">
                <div class="duration-box-min">
                    <input type="number" name="duration-min" id="duration-min" value="3">
                    <label for="duration-min">Days</label>
                </div>
                <div class="duration-box-min">
                    <input type="number" name="duration-max" id="duration-max" value="25">
                    <label for="duration-max">Days</label>
                </div>
            </div>
        </div>

        <!-- Price -->
        <div class="taken-filer-wrapper">
            <h3>Price</h3>
            <input type="range" name="taken" id="taken" min="16000" max="95000">
            <div class="taken-limit-boxs">
                <div class="taken-box-min">
                    <input type="number" name="taken-min" id="taken-min" value="16000">
                    <label for="taken-min">SEK</label>
                </div>
                <div class="taken-box-min">
                    <input type="number" name="taken-max" id="taken-max" value="95000">
                    <label for="taken-max">SEK</label>
                </div>
            </div>
        </div>

        <div class="filter-apply-button">
            <button>Apply filter</button>
        </div>
    </div>
</div>

<?php
wp_reset_postdata();
get_footer();
