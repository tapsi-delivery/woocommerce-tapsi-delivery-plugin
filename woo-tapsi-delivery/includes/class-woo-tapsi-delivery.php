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
            $this->version = '0.1.0';
        }
        $this->plugin_name = 'woo-tapsi-delivery';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-logger.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-i18n.php';

        /**
         * The class responsible for defining the Tapsi Delivery object
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-delivery.php';

        /**
         * The class responsible for defining the Tapsi Location object
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-pickup-location.php';

        /**
         * The class responsible for encryption functionality
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-encryption.php';

        /**
         * The class responsible for location hours functionality
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-hours.php';

        /**
         * The class responsible for Tapsi API operations
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-api.php';

        /**
         * The class responsible for Tapsi API operations
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-tapsi-shipping-method.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woocommerce-tapsi-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woocommerce-tapsi-public.php';

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

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

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

	    // Show the Location Selector
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

		// TODO: PRUNE
        // Handle tips
//        $this->loader->add_action('woocommerce_cart_calculate_fees', $plugin_public, 'maybe_add_tip', 10);

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

}
