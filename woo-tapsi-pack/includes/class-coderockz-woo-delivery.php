<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tapsi.com
 * @since      1.0.0
 *
 * @package    Tapsi_Woo_Delivery
 * @subpackage Tapsi_Woo_Delivery/includes
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
 * @package    Tapsi_Woo_Delivery
 * @subpackage Tapsi_Woo_Delivery/includes
 * @author     CodeRockz <admin@tapsi.com>
 */
class Tapsi_Woo_Delivery {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Tapsi_Woo_Delivery_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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
	public function __construct() {
		if ( defined( 'TAPSI_WOO_DELIVERY_VERSION' ) ) {
			$this->version = TAPSI_WOO_DELIVERY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'tapsi-woo-delivery';

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
	 * - Tapsi_Woo_Delivery_Loader. Orchestrates the hooks of the plugin.
	 * - Tapsi_Woo_Delivery_i18n. Defines internationalization functionality.
	 * - Tapsi_Woo_Delivery_Admin. Defines all hooks for the admin area.
	 * - Tapsi_Woo_Delivery_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tapsi-woo-delivery-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tapsi-woo-delivery-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tapsi-woo-delivery-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tapsi-woo-delivery-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tapsi-woo-delivery-helper.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tapsi-woo-delivery-time-option.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tapsi-woo-delivery-pickup-time-option.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tapsi-woo-delivery-delivery-option.php';

		$this->loader = new Tapsi_Woo_Delivery_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Tapsi_Woo_Delivery_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Tapsi_Woo_Delivery_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Tapsi_Woo_Delivery_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 9999999 );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'tapsi_woo_delivery_add_delivery_type' );


		$this->loader->add_action( 'admin_menu', $plugin_admin, 'tapsi_woo_delivery_menus_sections' );
		$this->loader->add_filter( 'plugin_action_links_' . TAPSI_WOO_DELIVERY , $plugin_admin, 'tapsi_woo_delivery_settings_link' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_delivery_timezone_form', $plugin_admin, 'tapsi_woo_delivery_process_delivery_timezone_form' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_delivery_option_settings', $plugin_admin, 'tapsi_woo_delivery_process_delivery_option_settings' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_delivery_date_form', $plugin_admin, 'tapsi_woo_delivery_process_delivery_date_form' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_pickup_date_form', $plugin_admin, 'tapsi_woo_delivery_process_pickup_date_form' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_offdays_form', $plugin_admin, 'tapsi_woo_delivery_process_delivery_date_offdays_form' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_delivery_time_form', $plugin_admin, 'tapsi_woo_delivery_process_delivery_time_form' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_pickup_time_form', $plugin_admin, 'tapsi_woo_delivery_process_pickup_time_form' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_localization_settings', $plugin_admin, 'tapsi_woo_delivery_process_localization_settings' );
		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_process_other_settings', $plugin_admin, 'tapsi_woo_delivery_process_other_settings' );
		$this->loader->add_filter('manage_edit-shop_order_columns', $plugin_admin, "tapsi_woo_delivery_add_custom_fields_orders_list");
		$this->loader->add_action('manage_shop_order_posts_custom_column', $plugin_admin, "tapsi_woo_delivery_show_custom_fields_data_orders_list");
		$this->loader->add_action( 'woocommerce_admin_order_data_after_shipping_address', $plugin_admin, 'tapsi_woo_delivery_information_after_shipping_address', 10, 1 );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'tapsi_woo_delivery_review_notice' );
        $this->loader->add_action('wp_ajax_tapsi_woo_delivery_save_review_notice', $plugin_admin, 'tapsi_woo_delivery_save_review_notice');
        $this->loader->add_action("add_meta_boxes", $plugin_admin, 'tapsi_woo_delivery_custom_meta_box');
        $this->loader->add_action('wp_ajax_tapsi_woo_delivery_meta_box_get_orders', $plugin_admin, 'tapsi_woo_delivery_meta_box_get_orders');
        $this->loader->add_action('wp_ajax_tapsi_woo_delivery_meta_box_get_orders_pickup', $plugin_admin, 'tapsi_woo_delivery_meta_box_get_orders_pickup');
        $this->loader->add_action('wp_ajax_tapsi_woo_delivery_save_meta_box_information', $plugin_admin, 'tapsi_woo_delivery_save_meta_box_information');

        $this->loader->add_filter( 'get_user_option_meta-box-order_shop_order', $plugin_admin, 'override_post_meta_box_order' );

        $this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_admin_disable_max_delivery_pickup_date', $plugin_admin, 'tapsi_woo_delivery_admin_disable_max_delivery_pickup_date' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Tapsi_Woo_Delivery_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 0 );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'dequeue_salient_theme_hoverintent_script', 99999 );

		$this->loader->add_action( 'init', $plugin_public, 'tapsi_woo_delivery_init_functionality' );

		$other_settings = get_option('tapsi_woo_delivery_other_settings');
		$position = isset($other_settings['field_position']) && $other_settings['field_position'] != "" ? $other_settings['field_position'] : "after_notes";
		
		if($position == "before_billing") {
			$this->loader->add_action( 'woocommerce_checkout_billing', $plugin_public, 'tapsi_woo_delivery_add_custom_field' );

		} elseif( $position == "after_billing" ) {
			$this->loader->add_action( 'woocommerce_after_checkout_billing_form', $plugin_public, 'tapsi_woo_delivery_add_custom_field' );
		} elseif($position == "before_shipping") {
			$this->loader->add_action( 'woocommerce_checkout_shipping', $plugin_public, 'tapsi_woo_delivery_add_custom_field' );

		} elseif( $position == "after_shipping" ) {
			$this->loader->add_action( 'woocommerce_after_checkout_shipping_form', $plugin_public, 'tapsi_woo_delivery_add_custom_field' );
		} elseif( $position == "before_notes" ) {
			$this->loader->add_action( 'woocommerce_before_order_notes', $plugin_public, 'tapsi_woo_delivery_add_custom_field' );
		} elseif( $position == "after_notes" ) {
			$this->loader->add_action( 'woocommerce_after_order_notes', $plugin_public, 'tapsi_woo_delivery_add_custom_field' );
		} elseif( $position == "before_payment" ) {
			$this->loader->add_action( 'woocommerce_review_order_before_payment', $plugin_public, 'tapsi_woo_delivery_add_custom_field');
		} elseif( $position == "before_your_order" ) {
			$this->loader->add_action( 'woocommerce_checkout_before_order_review_heading', $plugin_public, 'tapsi_woo_delivery_add_custom_field');
		}

		$this->loader->add_action('woocommerce_checkout_process', $plugin_public, 'tapsi_woo_delivery_customise_checkout_field_process');
		$this->loader->add_action('woocommerce_checkout_update_order_meta', $plugin_public, 'tapsi_woo_delivery_customise_checkout_field_update_order_meta');

		$this->loader->add_action('wp_ajax_tapsi_woo_delivery_get_time_in_format', $plugin_public, 'tapsi_woo_delivery_get_time_in_format');
		$this->loader->add_action('wp_ajax_nopriv_tapsi_woo_delivery_get_time_in_format', $plugin_public, 'tapsi_woo_delivery_get_time_in_format');
		$this->loader->add_action('wp_ajax_tapsi_woo_delivery_get_orders', $plugin_public, 'tapsi_woo_delivery_get_orders');
		$this->loader->add_action('wp_ajax_nopriv_tapsi_woo_delivery_get_orders', $plugin_public, 'tapsi_woo_delivery_get_orders');
		$this->loader->add_action('wp_ajax_tapsi_woo_delivery_get_orders_pickup', $plugin_public, 'tapsi_woo_delivery_get_orders_pickup');
		$this->loader->add_action('wp_ajax_nopriv_tapsi_woo_delivery_get_orders_pickup', $plugin_public, 'tapsi_woo_delivery_get_orders_pickup');
		$this->loader->add_filter( 'woocommerce_account_orders_columns', $plugin_public, 'tapsi_woo_delivery_add_account_orders_column', 10, 1 );
		$this->loader->add_action( "woocommerce_my_account_my_orders_column_order_delivery_details", $plugin_public, "tapsi_woo_delivery_show_delivery_details_my_account_tab");

		$this->loader->add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', $plugin_public, 'tapsi_woo_delivery_handle_custom_query_var', 10, 2 );

		$this->loader->add_filter( 'woocommerce_get_order_item_totals', $plugin_public, 'tapsi_woo_delivery_add_delivery_information_row', 10, 2 );

		$this->loader->add_action('wp_ajax_tapsi_woo_delivery_option_delivery_time_pickup', $plugin_public, 'tapsi_woo_delivery_option_delivery_time_pickup');
		$this->loader->add_action('wp_ajax_nopriv_tapsi_woo_delivery_option_delivery_time_pickup', $plugin_public, 'tapsi_woo_delivery_option_delivery_time_pickup');

		$this->loader->add_action( 'wp_ajax_tapsi_woo_delivery_disable_max_delivery_pickup_date', $plugin_public, 'tapsi_woo_delivery_disable_max_delivery_pickup_date' );
		$this->loader->add_action( 'wp_ajax_nopriv_tapsi_woo_delivery_disable_max_delivery_pickup_date', $plugin_public, 'tapsi_woo_delivery_disable_max_delivery_pickup_date' );

		$this->loader->add_action( 'wp_footer', $plugin_public,'tapsi_woo_delivery_load_custom_css', 50000);

		$this->loader->add_filter( 'woocommerce_form_field_select', $plugin_public, 'tapsi_woo_delivery_prevent_field_value_change', 20, 4 );

		$this->loader->add_action( 'wpi_after_formatted_shipping_address', $plugin_public, 'tapsi_woo_delivery_info_at_wpi_invoice', 10, 1 );

		$this->loader->add_action( 'woocommerce_cloudprint_internaloutput_footer', $plugin_public, 'tapsi_woo_delivery_cloud_print_fields' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Tapsi_Woo_Delivery_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
