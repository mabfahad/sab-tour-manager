<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();

        // Custom fields
        $start_date = get_post_meta(get_the_ID(), '_trip_start_date', true);
        $end_date = get_post_meta(get_the_ID(), '_trip_end_date', true);
        $price = get_post_meta(get_the_ID(), '_trip_price', true);
        $type = get_post_meta(get_the_ID(), '_trip_type', true);

        // Destinations taxonomy
        $destinations = wp_get_post_terms(get_the_ID(), 'destinations');

        // Featured image
        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');

        // Breadcrumbs (basic example; you might enhance with Yoast breadcrumbs or custom)
        $home_url = esc_url(home_url('/'));
        $destination_name = !empty($destinations) ? esc_html($destinations[0]->name) : '';

        $trip_helpers = new \controller\Sab_Helpers();
        $trip_days = $trip_helpers->sab_trip_duration($start_date, $end_date);
        $trip_nights = $trip_days - 1;
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
                    <h1 data-id="<?php echo esc_attr(get_the_ID()); ?>"><?php the_title(); ?></h1>
                    <div class="trip-details-social-icons">
                        <p><?php esc_html_e('Share on', 'text-domain'); ?></p>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"
                           target="_blank">
                            <img src="<?php echo SAB_URL; ?>/img/fb.svg" alt="Facebook">
                        </a>
                        <a href="https://www.instagram.com/?url=<?php the_permalink(); ?>" target="_blank">
                            <img src="<?php echo SAB_URL; ?>/img/instragram.svg" alt="Instagram">
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>"
                           target="_blank">
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
                            <p class="trip-details-duration"><?php echo esc_html($trip_days . '/' . $trip_nights . ' Nights incl travel days') ?></p>
                        <?php endif; ?>

                        <?php if ($price): ?>
                            <p class="trip-details-price"><?php esc_html_e('From', 'text-domain'); ?>
                                <span><?php echo esc_html($price); ?></span>
                                SEK <?php esc_html_e('per person', 'text-domain'); ?></p>
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

        <!-- Contact Modal -->
        <div class="contact-suggestion-modal" style="display:none;">
            <div class="contact-modal-overlay"></div>
            <div class="contact-modal-content">
                <button class="contact-modal-close">&times;</button>
                <div class="contact-form-main-wrapper">
                    <div class="title-close-button">
                        <h3>Send request</h3>
                        <span class="close-button"><img src="./img/close-buttom.svg" alt=""></span>
                    </div>
                    <div class="contact-form-wrapper">
                        <form action="" class="trip-contact-form">
                            <?php wp_nonce_field('trip_contact_nonce', 'trip_contact_nonce_field'); ?>
                            <div class="contact-form-main-elements">
                                <div class="contact-form-title">
                                    <h3>Contact information</h3>
                                    <p><span>*</span>marked files are required.</p>
                                </div>
                                <div class="contact-form-fields-wrapper">
                                    <div class="contact-form-field-item">
                                        <label for="first-name">First name<span>*</span></label>
                                        <input type="text" id="first-name" name="first-name"
                                               placeholder="Enter your first name" required>
                                    </div>
                                    <div class="contact-form-field-item">
                                        <label for="surname">Surname<span>*</span></label>
                                        <input type="text" id="surname" name="surname" placeholder="Enter your surname"
                                               required>
                                    </div>
                                    <div class="contact-form-field-item">
                                        <label for="phone-number">Phone (Day)</label>
                                        <input type="tel" id="phone-number" name="phone-number"
                                               placeholder="Enter your phone number">
                                    </div>
                                    <div class="contact-form-field-item">
                                        <label for="email">Email address<span>*</span></label>
                                        <input type="email" id="email" name="email"
                                               placeholder="Enter your email address" required>
                                    </div>
                                </div>

                                <div class="form-selected-trip">
                                    <h4>Selected Trip</h4>
                                    <h3 data-id="<?php echo esc_attr(get_the_ID());?>"><?php echo esc_html( get_the_title() )?></h3>
                                </div>
                            </div>

                            <div class="contact-form-submit-wrapper">
                                <div class="contact-privacy-policy">
                                    <input type="checkbox" id="privacy-policy" name="privacy-policy" required>
                                    <label for="privacy-policy">I accept privacy policy</label>
                                </div>
                                <div class="contact-form-submit-btn">
                                    <button type="submit">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="contact-form-response" style="display:none;"></div>
            </div>
        </div>

    <?php endwhile;
endif;
get_footer();


