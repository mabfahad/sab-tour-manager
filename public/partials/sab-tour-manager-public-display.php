<?php
get_header();

$helpers = new \controller\Sab_Helpers();
$destinations = $helpers->get_all_destinations();

?>
    <div class="destinations-wrapper">

        <div class="destination-form-inner">
            <div class="destination-locations">
                <select name="destination-locations" id="destination-locations" class="destination-locations">
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

<?php
get_footer();
