<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://fahad-assignment.online
 * @since      1.0.0
 *
 * @package    Sab_Tour_Manager
 * @subpackage Sab_Tour_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sab_Tour_Manager
 * @subpackage Sab_Tour_Manager/public
 * @author     Md Abdullah Al Fahad <mabf.fahad@gmail.com>
 */
class Sab_Tour_Manager_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sab_Tour_Manager_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sab_Tour_Manager_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/sab-tour-manager-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sab_Tour_Manager_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sab_Tour_Manager_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sab-tour-manager-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script('jquery');
        wp_localize_script($this->plugin_name, 'tripsData', [
            'ajaxUrl'    => admin_url('admin-ajax.php'),
        ]);

    }

    public function include_template_for_tour_manager($template)
    {
        $page = new \controller\Sab_Page(); // page slug
        if (is_page($page->get_page_id('sab-tour-manager'))) {
            $plugin_template = SAB_PATH . 'public/partials/sab-tour-manager-public-display.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        return $template;
    }

    /**
     * Load plugin template for Trips CPT archive
     */
    public function load_trips_archive_template($template)
    {
        if (is_post_type_archive('trips')) {
            $plugin_template = SAB_PATH . 'public/partials/trips-archive.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        return $template; // fallback to theme
    }

    public function include_trip_single_template( $template ) {
        // only run on front-end
        if ( is_admin() ) {
            return $template;
        }

        // Ensure the main query is available and we have a singular post
        if ( ! is_singular() ) {
            return $template;
        }

        // safer check for post type
        $post_type = get_post_type();
        if ( 'trips' !== $post_type ) {
            return $template;
        }

        // Build plugin template path
        $plugin_template = SAB_PATH . 'public/partials/single-trip.php';

        // debug: remove or comment out when confirmed
         error_log( __METHOD__ . " called. post_type={$post_type}, path={$plugin_template}, exists=" . ( file_exists( $plugin_template ) ? 'yes' : 'no' ) );

        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }

        return $template;
    }

}
