<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woocommerce_Tapsi_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * Allows access to the Tapsi API class
     *
     * @since    1.0.0
     * @access   public
     * @var      Woocommerce_Tapsi_API $api Handles Tapsi API operations
     */
    public $api;

    /**
     * Access the WooCommerce logger
     *
     * @since 1.0.0
     * @access public
     * @var Woocommerce_Tapsi_Logger
     */
    public $log;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('WOOCOMMERCE_TAPSI_VERSION')) {
            $this->version = WOOCOMMERCE_TAPSI_VERSION;
        } else {
            $this->version = '0.1.4';
        }
        $this->plugin_name = 'woo-tapsi-delivery';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->set_shipping_method_for_zone();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woocommerce_Tapsi_Loader. Orchestrates the hooks of the plugin.
     * - Woocommerce_Tapsi_i18n. Defines internationalization functionality.
     * - Woocommerce_Tapsi_Admin. Defines all hooks for the admin area.
     * - Woocommerce_Tapsi_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-logger.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-i18n.php';


        /**
         * The class responsible for adding plugin as a shipping method for desired shipping zone
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-shipping-zone.php';

        /**
         * The class responsible for defining the Tapsi Delivery object
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-delivery.php';

        /**
         * The class responsible for defining the Tapsi Location object
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-pickup-location.php';

        /**
         * The class responsible for encryption functionality
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-encryption.php';

        /**
         * The class responsible for location hours functionality
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-hours.php';

        /**
         * The class responsible for Tapsi API operations
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-api.php';

        /**
         * The class responsible for Tapsi API operations
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-tapsi-delivery-shipping-method.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woo-tapsi-delivery-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woo-tapsi-delivery-public.php';

        /**
         * The class responsible for defining the Tapsi Location object
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/jdatetime.class.php';

        $this->loader = new Woocommerce_Tapsi_Loader();
        $this->log = new Woocommerce_Tapsi_Logger();
        $this->api = new Woocommerce_Tapsi_API();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Woocommerce_Tapsi_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Woocommerce_Tapsi_i18n();

        $this->loader->add_action('init', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Woocommerce_Tapsi_Admin($this->get_plugin_name(), $this->get_version());
        $encryption = new Woocommerce_Tapsi_Encryption();

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Add plugin settings to WooCommerce
        $this->loader->add_filter('woocommerce_get_settings_pages', $plugin_admin, 'add_settings');

        // Signing secret encryption
        $this->loader->add_filter('pre_update_option_woocommerce_tapsi_sandbox_signing_secret', $encryption, 'encrypt_meta', 10, 3);
        $this->loader->add_filter('pre_update_option_woocommerce_tapsi_production_signing_secret', $encryption, 'encrypt_meta', 10, 3);
        $this->loader->add_filter('option_woocommerce_tapsi_sandbox_signing_secret', $encryption, 'decrypt_meta', 10, 2);
        $this->loader->add_filter('option_woocommerce_tapsi_production_signing_secret', $encryption, 'decrypt_meta', 10, 2);

        // Key ID encryption
        $this->loader->add_filter('pre_update_option_woocommerce_tapsi_sandbox_key_id', $encryption, 'encrypt_meta', 10, 3);
        $this->loader->add_filter('pre_update_option_woocommerce_tapsi_production_key_id', $encryption, 'encrypt_meta', 10, 3);
        $this->loader->add_filter('option_woocommerce_tapsi_sandbox_key_id', $encryption, 'decrypt_meta', 10, 2);
        $this->loader->add_filter('option_woocommerce_tapsi_production_key_id', $encryption, 'decrypt_meta', 10, 2);

        // Decrypt our options on the alloptions autoloader
        // $this->loader->add_filter( 'alloptions', $encryption, 'get_all_options', 9999, 1 );

        // Filter the default and location hours before save
        $this->loader->add_filter('pre_update_option', $plugin_admin, 'update_default_hours', 10, 3);

        // Show a notice in sandbox mode
        $this->loader->add_action('admin_notices', $plugin_admin, 'admin_sandbox_notice');

        // Add custom post type for the Pickup Locations
        $this->loader->add_action('init', $plugin_admin, 'register_pickup_location_cpt');

        // Register a shipping method
        $this->loader->add_filter('woocommerce_shipping_methods', $plugin_admin, 'register_shipping_method');

        // setup tapsi order statuses
        $this->loader->add_action('init', $plugin_admin, 'register_tapsi_order_statuses');
        $this->loader->add_filter('wc_order_statuses', $plugin_admin, 'add_tapsi_order_statuses');

        // register custom endpoint / route to update order statuses
        $this->loader->add_action('rest_api_init', $plugin_admin, 'wc_tapsi_register_rest_route');

        // Filter meta key and value display
        $this->loader->add_filter('woocommerce_order_item_display_meta_key', $plugin_admin, 'filter_order_item_displayed_meta_key', 20, 3);
        $this->loader->add_filter('woocommerce_order_item_display_meta_value', $plugin_admin, 'filter_order_item_displayed_meta_value', 20, 3);

        // Accept delivery quote when order is paid
        $this->loader->add_action('woocommerce_payment_complete', $plugin_admin, 'accept_delivery_quote', 10, 1);

        // Send email to selected location when order is placed
        $this->loader->add_action('woocommerce_email_recipient_new_order', $plugin_admin, 'new_order_email_recipient', 10, 3);

        // Adds custom tracking provider for Tapsi to the WooCommerce Shipment Tracking plugin
        $this->loader->add_action('wc_shipment_tracking_get_providers', $plugin_admin, 'wc_shipment_tracking_add_tapsi_provider', 10, 1);

        $this->merge_zones();
    }

    private function merge_zones()
    {
        // Make and merge optional rates
        add_filter('woocommerce_package_rates', function ($rates, $package) {

            $country = strtoupper(wc_clean($package['destination']['country']));
            $state = strtoupper(wc_clean($package['destination']['state']));
            $postcode = wc_normalize_postcode(wc_clean($package['destination']['postcode']));
            $cache_key = WC_Cache_Helper::get_cache_prefix('shipping_zones') . 'wc_shipping_zones_' . md5(sprintf('%s+%s+%s', $country, $state, $postcode));
            $matching_zone_ids = wp_cache_get($cache_key, 'shipping_zones_array'); // get ids from the datastore cache
            if (false === $matching_zone_ids) {
                $data_store = WC_Data_Store::load('shipping-zone');
                $data_store->get_zone_id_from_package($package);
                $matching_zone_ids = wp_cache_get($cache_key, 'shipping_zones_array');
            }
            if ($matching_zone_ids) : foreach ($matching_zone_ids as $matching_zone_id):
                $new_zone = new WC_Shipping_Zone($matching_zone_id);
                foreach ($new_zone->get_shipping_methods(true) as $new_method) {
                    $rates = $rates + $new_method->get_rates_for_package($package); // make and merge the optional rate
                }
            endforeach; endif;

            return $rates;

        }, 10, 2);


        add_filter('woocommerce_get_zone_criteria', function ($criteria, $package, $postcode_locations) { // set ids as array to the datastore cache

            global $wpdb;
            $matching_zone_ids = array();
            $matching_zones = $wpdb->get_results("SELECT zones.zone_id FROM {$wpdb->prefix}woocommerce_shipping_zones as zones LEFT OUTER JOIN {$wpdb->prefix}woocommerce_shipping_zone_locations as locations ON zones.zone_id = locations.zone_id AND location_type != 'postcode' WHERE " . implode(' ', $criteria) . ' ORDER BY zone_order ASC, zones.zone_id ASC LIMIT 10');
            if ($matching_zones) {
                foreach ($matching_zones as $zone) {
                    $matching_zone_ids[] = $zone->zone_id;
                }
            }
            $country = strtoupper(wc_clean($package['destination']['country']));
            $state = strtoupper(wc_clean($package['destination']['state']));
            $postcode = wc_normalize_postcode(wc_clean($package['destination']['postcode']));
            $cache_key = WC_Cache_Helper::get_cache_prefix('shipping_zones') . 'wc_shipping_zones_' . md5(sprintf('%s+%s+%s', $country, $state, $postcode));
            wp_cache_set($cache_key, $matching_zone_ids, 'shipping_zones_array');

            return $criteria;

        }, 10, 3);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Woocommerce_Tapsi_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        $this->loader->add_action('template_redirect', $plugin_public, 'render_checkout_map_modal', 10);

        $this->loader->add_action('woocommerce_after_shipping_rate', $plugin_public, 'show_available_locations_dropdown', 10, 2);

        // Update location in session on cart page
        $this->loader->add_action('wp_ajax_wcdd_update_pickup_location', $plugin_public, 'save_pickup_location_to_session', 10);
        $this->loader->add_action('wp_ajax_nopriv_wcdd_update_pickup_location', $plugin_public, 'save_pickup_location_to_session', 10);

        // Pickup store validation
        $this->loader->add_action('woocommerce_checkout_process', $plugin_public, 'validate_pickup_location');

        // Disable CoD gateway when DD is selected
        $this->loader->add_action('woocommerce_available_payment_gateways', $plugin_public, 'disable_cod', 10, 1);

        // Add shipping phone field
        $this->loader->add_filter('woocommerce_checkout_fields', $plugin_public, 'add_shipping_phone', 100, 1);

        // Update totals on phone number change
        $this->loader->add_filter('woocommerce_checkout_fields', $plugin_public, 'add_update_totals_to_phone', 10, 1);

        // Save the data to the session when updating the order review step
        $this->loader->add_action('woocommerce_checkout_update_order_review', $plugin_public, 'save_data_to_session', 10, 1);
        $this->loader->add_action('wp_ajax_nopriv_tapsi_save_data_to_session', $plugin_public, 'save_data_to_session', 10, 1);

        // Trigger shipping calculation on update_totals
        $this->loader->add_action('woocommerce_checkout_update_order_review', $plugin_public, 'trigger_shipping_calculation', 10, 1);

        // Save pickup location to order meta
        $this->loader->add_action('woocommerce_checkout_create_order', $plugin_public, 'save_pickup_location_to_order', 10, 2);

        // Save pickup location to shipping item meta
        $this->loader->add_action('woocommerce_checkout_create_order_shipping_item', $plugin_public, 'save_pickup_location_to_order_item_shipping', 10, 4);

        // Display pickup location on orders and email notifications
        $this->loader->add_filter('woocommerce_get_order_item_totals', $plugin_public, 'display_pickup_location_on_order_item_totals', 10, 3);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Woocommerce_Tapsi_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

    private function set_shipping_method_for_zone()
    {
        $plugin_shipping_zone = new Woocommerce_Tapsi_shipping_zone();
        $this->loader->add_action('wp_loaded', $plugin_shipping_zone, 'set_shipping_zone');
    }

}
