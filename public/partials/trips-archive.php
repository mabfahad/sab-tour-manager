<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

get_header();

// Current page number
$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

// Current date
$today = date('Y-m-d');

$args = [
    'post_type' => 'trips',
    'posts_per_page' => 12,
    'paged' => $paged,
    'meta_key' => '_trip_start_date',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_query' => [
        [
            'key' => '_trip_start_date',
            'value' => $today,
            'compare' => '>=',
            'type' => 'DATE',
        ],
    ],
];

$trips_query = new WP_Query($args);

if ($trips_query->have_posts()) : ?>

    <div class="trips-archive">
        <h1>Upcoming Trips</h1>

        <?php while ($trips_query->have_posts()) : $trips_query->the_post();
            $start_date = get_post_meta(get_the_ID(), '_trip_start_date', true);
            $end_date = get_post_meta(get_the_ID(), '_trip_end_date', true);
            $price = get_post_meta(get_the_ID(), '_trip_price', true);
            $type = get_post_meta(get_the_ID(), '_trip_type', true);
            ?>
            <div class="trip-item">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p>Start: <?php echo esc_html($start_date); ?></p>
                <p>End: <?php echo esc_html($end_date); ?></p>
                <p>Price: <?php echo esc_html($price); ?></p>
                <p>Type: <?php echo esc_html($type); ?></p>
            </div>
        <?php endwhile; ?>

        <div class="pagination">
            <?php
            echo paginate_links([
                'total' => $trips_query->max_num_pages,
                'current' => $paged,
                'mid_size' => 2,
                'prev_text' => __('« Prev'),
                'next_text' => __('Next »'),
            ]);
            ?>
        </div>
    </div>

<?php else : ?>
    <p>No upcoming trips found.</p>
<?php endif;

wp_reset_postdata();

get_footer();
