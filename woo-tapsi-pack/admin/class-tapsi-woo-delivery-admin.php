<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tapsi.com
 * @since      1.0.0
 *
 * @package    Tapsi_Woo_Delivery
 * @subpackage Tapsi_Woo_Delivery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tapsi_Woo_Delivery
 * @subpackage Tapsi_Woo_Delivery/admin
 * @author     CodeRockz <admin@tapsi.com>
 */
class Tapsi_Woo_Delivery_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $helper;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->helper = new Tapsi_Woo_Delivery_Helper();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tapsi_Woo_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tapsi_Woo_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'select2mincss', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "flatpickr_css", TAPSI_WOO_DELIVERY_URL . 'public/css/flatpickr.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tapsi-woo-delivery-admin.css', array(), $this->version, 'all' );
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tapsi_Woo_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tapsi_Woo_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'jquery-effects-slide' );
		wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		wp_enqueue_script( "animejs", plugin_dir_url( __FILE__ ) . 'js/anime.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( "flatpickr_js", TAPSI_WOO_DELIVERY_URL . 'public/js/flatpickr.min.js', [], $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tapsi-woo-delivery-admin.js', array( 'jquery', 'animejs', 'selectWoo', 'flatpickr_js' ), $this->version, true );
		$tapsi_woo_delivery_nonce = wp_create_nonce('tapsi_woo_delivery_nonce');
	        wp_localize_script($this->plugin_name, 'tapsi_woo_delivery_ajax_obj', array(
	            'tapsi_woo_delivery_ajax_url' => admin_url('admin-ajax.php'),
	            'nonce' => $tapsi_woo_delivery_nonce,
	        ));

	}

	public function tapsi_woo_delivery_menus_sections() {

        add_menu_page(
			__('Woo Delivery', 'woo-delivery'),
            __('Woo Delivery', 'woo-delivery'),
			'manage_options',
			'tapsi-woo-delivery-settings',
			array($this, "tapsi_woo_delivery_main_layout"),
			"dashicons-cart",
			null
		);

    }

	public function tapsi_woo_delivery_settings_link( $links ) {
    	if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace( '<a', '<a class="tapsi-woo-delivery-deactivate-link"', $links['deactivate'] );
		}
        $links[] = '<a href="admin.php?page=tapsi-woo-delivery-settings">Settings</a>';
        return $links;
    }

    public function tapsi_woo_delivery_process_delivery_timezone_form() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$timezone_form_settings = [];

		$store_location_timezone = sanitize_text_field($date_form_data['tapsi_delivery_time_timezone']);

		$timezone_form_settings['store_location_timezone'] = $store_location_timezone;


		if(get_option('tapsi_woo_delivery_time_settings') == false) {
			update_option('tapsi_woo_delivery_time_settings', $timezone_form_settings);
		} else {
			$timezone_form_settings = array_merge(get_option('tapsi_woo_delivery_time_settings'),$timezone_form_settings);
			update_option('tapsi_woo_delivery_time_settings', $timezone_form_settings);
		}

		wp_send_json_success();
	}

	public function tapsi_woo_delivery_process_delivery_option_settings() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
		
		$delivery_option_settings_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );

		$tapsi_enable_option_time_pickup = !isset($form_data['tapsi_enable_option_time_pickup']) ? false : true;

		$delivery_option_field_label = sanitize_text_field($form_data['tapsi_woo_delivery_delivery_option_label']);

		$delivery_field_label = sanitize_text_field($form_data['tapsi_woo_delivery_option_delivery_label']);

		$pickup_field_label = sanitize_text_field($form_data['tapsi_woo_delivery_option_pickup_label']);

		$delivery_option_settings_form_settings['enable_option_time_pickup'] = $tapsi_enable_option_time_pickup;
		$delivery_option_settings_form_settings['delivery_option_label'] = $delivery_option_field_label;
		$delivery_option_settings_form_settings['delivery_label'] = $delivery_field_label;
		$delivery_option_settings_form_settings['pickup_label'] = $pickup_field_label;

		
		if(get_option('tapsi_woo_delivery_option_delivery_settings') == false) {
			update_option('tapsi_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		} else {
			$delivery_option_settings_form_settings = array_merge(get_option('tapsi_woo_delivery_option_delivery_settings'),$delivery_option_settings_form_settings);
			update_option('tapsi_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		}
		wp_send_json_success();
		
    }
    
    public function tapsi_woo_delivery_process_delivery_date_form() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
		
		$date_form_settings = [];

		parse_str( $_POST[ 'dateFormData' ], $date_form_data );

		$enable_delivery_date = !isset($date_form_data['tapsi_enable_delivery_date']) ? false : true;
		
		$delivery_date_mandatory = !isset($date_form_data['tapsi_delivery_date_mandatory']) ? false : true;
		
		$delivery_date_field_label = sanitize_text_field($date_form_data['tapsi_delivery_date_field_label']);
		
		$delivery_date_selectable_date = sanitize_text_field($date_form_data['tapsi_delivery_date_selectable_date']);
		
		$delivery_date_format = sanitize_text_field($date_form_data['tapsi_delivery_date_format']);

		$auto_select_first_date = !isset($date_form_data['tapsi_auto_select_first_date']) ? false : true;
		
		$delivery_week_starts_from = sanitize_text_field($date_form_data['tapsi_delivery_date_week_starts_from']);
		
		$delivery_date_delivery_days="";
		if(isset($date_form_data['tapsi_delivery_date_delivery_days'])) {
			$delivery_days = $this->helper->tapsi_woo_delivery_array_sanitize($date_form_data['tapsi_delivery_date_delivery_days']);
			$delivery_date_delivery_days = implode(',', $delivery_days);
		}
		

		$date_form_settings['enable_delivery_date'] = $enable_delivery_date;
		$date_form_settings['delivery_date_mandatory'] = $delivery_date_mandatory;
		$date_form_settings['field_label'] = $delivery_date_field_label;
		$date_form_settings['selectable_date'] = $delivery_date_selectable_date;
		$date_form_settings['date_format'] = $delivery_date_format;
		$date_form_settings['auto_select_first_date'] = $auto_select_first_date;
		$date_form_settings['delivery_days'] = $delivery_date_delivery_days;
		$date_form_settings['week_starts_from'] = $delivery_week_starts_from;
		
		if(get_option('tapsi_woo_delivery_date_settings') == false) {
			update_option('tapsi_woo_delivery_date_settings', $date_form_settings);
		} else {
			$date_form_settings = array_merge(get_option('tapsi_woo_delivery_date_settings'),$date_form_settings);
			update_option('tapsi_woo_delivery_date_settings', $date_form_settings);
		}
		wp_send_json_success();
		
    }

    public function tapsi_woo_delivery_process_pickup_date_form() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
		
		$date_form_settings = [];

		parse_str( $_POST[ 'dateFormData' ], $date_form_data );

		$enable_pickup_date = !isset($date_form_data['tapsi_enable_pickup_date']) ? false : true;
		
		$pickup_date_mandatory = !isset($date_form_data['tapsi_pickup_date_mandatory']) ? false : true;

		$pickup_date_field_label = sanitize_text_field($date_form_data['tapsi_pickup_date_field_label']);
		
		$pickup_date_selectable_date = sanitize_text_field($date_form_data['tapsi_pickup_date_selectable_date']);
		
		$pickup_date_format = sanitize_text_field($date_form_data['tapsi_pickup_date_format']);


		$pickup_week_starts_from = sanitize_text_field($date_form_data['tapsi_pickup_date_week_starts_from']);
		
		$pickup_date_delivery_days="";
		if(isset($date_form_data['tapsi_pickup_date_delivery_days'])) {
			$delivery_days = $this->helper->tapsi_woo_delivery_array_sanitize($date_form_data['tapsi_pickup_date_delivery_days']);
			$pickup_date_delivery_days = implode(',', $delivery_days);
		}

		$auto_select_first_pickup_date = !isset($date_form_data['tapsi_auto_select_first_pickup_date']) ? false : true;
		

		$date_form_settings['enable_pickup_date'] = $enable_pickup_date;
		$date_form_settings['pickup_date_mandatory'] = $pickup_date_mandatory;
		$date_form_settings['pickup_field_label'] = $pickup_date_field_label;
		$date_form_settings['selectable_date'] = $pickup_date_selectable_date;
		$date_form_settings['date_format'] = $pickup_date_format;
		$date_form_settings['pickup_days'] = $pickup_date_delivery_days;
		$date_form_settings['week_starts_from'] = $pickup_week_starts_from;
		$date_form_settings['auto_select_first_pickup_date'] = $auto_select_first_pickup_date;
		
		if(get_option('tapsi_woo_delivery_pickup_date_settings') == false) {
			update_option('tapsi_woo_delivery_pickup_date_settings', $date_form_settings);
		} else {
			$date_form_settings = array_merge(get_option('tapsi_woo_delivery_pickup_date_settings'),$date_form_settings);
			update_option('tapsi_woo_delivery_pickup_date_settings', $date_form_settings);
		}

		wp_send_json_success();
		
    }

    public function tapsi_woo_delivery_process_delivery_date_offdays_form() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
    	$year_array = [];
    	$offdays_array = [];
    	parse_str( $_POST[ 'dateFormData' ], $date_form_data );
    	foreach($date_form_data as $key => $value) {
		    if (strpos($key, 'tapsi_woo_delivery_offdays_year_') === 0) {
		        array_push($year_array,sanitize_text_field($value));
		    }
		}
		foreach($year_array as $year) {
			$offdays_months = $this->helper->tapsi_woo_delivery_array_sanitize($date_form_data["tapsi_woo_delivery_offdays_month_".$year]);
			if(!empty($offdays_months)){
				foreach($offdays_months as $offdays_month) {
					if($offdays_month != "") {
						$offdays_days = sanitize_text_field($date_form_data["tapsi_woo_delivery_offdays_dates_".$offdays_month."_".$year]);
						if(isset($offdays_days) && $offdays_days != "") {
							$formated_offdays = [];
							$offdays_days = explode(',', $offdays_days);
							foreach($offdays_days as $offdays_day) {
								$formated_offdays[] = sprintf("%02d", $offdays_day);
							}
							$formated_offdays = implode(',', $formated_offdays);
							$offdays_array[$year][$offdays_month] = $formated_offdays;
						}	
					}
				}
			}
			
		}
		$date_form_settings['off_days'] = $offdays_array;
		if(get_option('tapsi_woo_delivery_date_settings') == false) {
			update_option('tapsi_woo_delivery_date_settings', $date_form_settings);
		} else {
			$date_form_settings = array_merge(get_option('tapsi_woo_delivery_date_settings'),$date_form_settings);
			update_option('tapsi_woo_delivery_date_settings', $date_form_settings);
		}
		wp_send_json_success();
		
    }

    public function tapsi_woo_delivery_process_delivery_time_form() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$time_form_settings = [];
		$enable_delivery_time = !isset($date_form_data['tapsi_enable_delivery_time']) ? false : true;
		$delivery_time_mandatory = !isset($date_form_data['tapsi_delivery_time_mandatory']) ? false : true;
		$delivery_time_field_label = sanitize_text_field($date_form_data['tapsi_delivery_time_field_label']);
		$disable_current_time_slot = !isset($date_form_data['tapsi_delivery_time_disable_current_time_slot']) ? false : true;
		$delivery_time_format = sanitize_text_field($date_form_data['tapsi_delivery_time_format']);
		$delivery_time_maximum_order = sanitize_text_field($date_form_data['tapsi_delivery_time_maximum_order']);
		$auto_select_first_time = !isset($date_form_data['tapsi_auto_select_first_time']) ? false : true;
		$delivery_time_slot_starts_hour = (isset($date_form_data['tapsi_delivery_time_slot_starts_hour']) && $date_form_data['tapsi_delivery_time_slot_starts_hour'] !="") ? sanitize_text_field($date_form_data['tapsi_delivery_time_slot_starts_hour']) : "0";
		
		$delivery_time_slot_starts_min = (isset($date_form_data['tapsi_delivery_time_slot_starts_min']) && $date_form_data['tapsi_delivery_time_slot_starts_min'] !="") ? sanitize_text_field($date_form_data['tapsi_delivery_time_slot_starts_min']) : "0"; 

		$delivery_time_slot_starts_format = sanitize_text_field($date_form_data['tapsi_delivery_time_slot_starts_format']);
		if($delivery_time_slot_starts_format == "am") {
			$delivery_time_slot_starts_hour = ($delivery_time_slot_starts_hour == "12") ? "0" : $delivery_time_slot_starts_hour;
			$delivery_time_slot_starts = ((int)$delivery_time_slot_starts_hour * 60) + (int)$delivery_time_slot_starts_min;
		} else {
			$delivery_time_slot_starts_hour = ($delivery_time_slot_starts_hour == "12") ? "0" : $delivery_time_slot_starts_hour;
			$delivery_time_slot_starts = (((int)$delivery_time_slot_starts_hour + 12)*60) + (int)$delivery_time_slot_starts_min;
		}

		$delivery_time_slot_ends_hour = (isset($date_form_data['tapsi_delivery_time_slot_ends_hour']) && $date_form_data['tapsi_delivery_time_slot_ends_hour'] !="") ? sanitize_text_field($date_form_data['tapsi_delivery_time_slot_ends_hour']) : "0";
		
		$delivery_time_slot_ends_min = (isset($date_form_data['tapsi_delivery_time_slot_ends_min']) && $date_form_data['tapsi_delivery_time_slot_ends_min'] !="") ? sanitize_text_field($date_form_data['tapsi_delivery_time_slot_ends_min']) : "0"; 

		$delivery_time_slot_ends_format = sanitize_text_field($date_form_data['tapsi_delivery_time_slot_ends_format']);

		if($delivery_time_slot_ends_format == "am") {
			$delivery_time_slot_ends_hour_12 = ($delivery_time_slot_ends_hour == "12") ? "0" : $delivery_time_slot_ends_hour;
			$delivery_time_slot_ends = ((int)$delivery_time_slot_ends_hour_12 * 60) + (int)$delivery_time_slot_ends_min;
		} else {
			$delivery_time_slot_ends_hour = ($delivery_time_slot_ends_hour == "12") ? "0" : $delivery_time_slot_ends_hour;
			$delivery_time_slot_ends = (((int)$delivery_time_slot_ends_hour + 12)*60) + (int)$delivery_time_slot_ends_min;
		}

		if($delivery_time_slot_ends_format == "am" && $delivery_time_slot_ends_hour == "12" && ($delivery_time_slot_ends_min =="0"||$delivery_time_slot_ends_min =="00")) {
				$delivery_time_slot_ends = 1440;
		}

		$delivery_time_slot_duration_time = (isset($date_form_data['tapsi_delivery_time_slot_duration_time']) && $date_form_data['tapsi_delivery_time_slot_duration_time'] !="") ? sanitize_text_field($date_form_data['tapsi_delivery_time_slot_duration_time']) : "0";
		$delivery_time_slot_duration_format = sanitize_text_field($date_form_data['tapsi_delivery_time_slot_duration_format']);

		if($delivery_time_slot_duration_format == "hour") {
			$each_time_slot = (int)$delivery_time_slot_duration_time * 60;
			$each_time_slot = $each_time_slot != 0 ? $each_time_slot : "";
		} else {
			$each_time_slot = (int)$delivery_time_slot_duration_time;
			$each_time_slot = $each_time_slot != 0 ? $each_time_slot : "";
		}

		$time_form_settings['enable_delivery_time'] = $enable_delivery_time;
		$time_form_settings['delivery_time_mandatory'] = $delivery_time_mandatory;
		$time_form_settings['field_label'] = $delivery_time_field_label;
		$time_form_settings['time_format'] = $delivery_time_format;
		$time_form_settings['delivery_time_starts'] = (string)$delivery_time_slot_starts;
		$time_form_settings['delivery_time_ends'] = (string)$delivery_time_slot_ends;
		$time_form_settings['each_time_slot'] = (string)$each_time_slot;
		$time_form_settings['max_order_per_slot'] = $delivery_time_maximum_order;
		$time_form_settings['disabled_current_time_slot'] = $disable_current_time_slot;
		$time_form_settings['auto_select_first_time'] = $auto_select_first_time;

		if(get_option('tapsi_woo_delivery_time_settings') == false) {
			update_option('tapsi_woo_delivery_time_settings', $time_form_settings);
		} else {
			$time_form_settings = array_merge(get_option('tapsi_woo_delivery_time_settings'),$time_form_settings);
			update_option('tapsi_woo_delivery_time_settings', $time_form_settings);
		}

		wp_send_json_success();
	}

	public function tapsi_woo_delivery_process_pickup_time_form() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $pickup_form_data );
		$pickup_time_form_settings = [];
		$enable_pickup_time = !isset($pickup_form_data['tapsi_enable_pickup_time']) ? false : true;
		$pickup_time_mandatory = !isset($pickup_form_data['tapsi_pickup_time_mandatory']) ? false : true;
		$pickup_time_field_label = sanitize_text_field($pickup_form_data['tapsi_pickup_time_field_label']);
		$disable_current_time_slot = !isset($pickup_form_data['tapsi_pickup_time_disable_current_time_slot']) ? false : true;
		$pickup_time_format = sanitize_text_field($pickup_form_data['tapsi_pickup_time_format']);
		$pickup_time_maximum_order = sanitize_text_field($pickup_form_data['tapsi_pickup_time_maximum_order']);
		$auto_select_first_time = !isset($pickup_form_data['tapsi_auto_select_first_pickup_time']) ? false : true;
		$pickup_time_slot_starts_hour = (isset($pickup_form_data['tapsi_pickup_time_slot_starts_hour']) && $pickup_form_data['tapsi_pickup_time_slot_starts_hour'] !="") ? sanitize_text_field($pickup_form_data['tapsi_pickup_time_slot_starts_hour']) : "0";
		
		$pickup_time_slot_starts_min = (isset($pickup_form_data['tapsi_pickup_time_slot_starts_min']) && $pickup_form_data['tapsi_pickup_time_slot_starts_min'] !="") ? sanitize_text_field($pickup_form_data['tapsi_pickup_time_slot_starts_min']) : "0"; 

		$pickup_time_slot_starts_format = sanitize_text_field($pickup_form_data['tapsi_pickup_time_slot_starts_format']);
		if($pickup_time_slot_starts_format == "am") {
			$pickup_time_slot_starts_hour = ($pickup_time_slot_starts_hour == "12") ? "0" : $pickup_time_slot_starts_hour;
			$pickup_time_slot_starts = ((int)$pickup_time_slot_starts_hour * 60) + (int)$pickup_time_slot_starts_min;
		} else {
			$pickup_time_slot_starts_hour = ($pickup_time_slot_starts_hour == "12") ? "0" : $pickup_time_slot_starts_hour;
			$pickup_time_slot_starts = (((int)$pickup_time_slot_starts_hour + 12)*60) + (int)$pickup_time_slot_starts_min;
		}

		$pickup_time_slot_ends_hour = (isset($pickup_form_data['tapsi_pickup_time_slot_ends_hour']) && $pickup_form_data['tapsi_pickup_time_slot_ends_hour'] !="") ? sanitize_text_field($pickup_form_data['tapsi_pickup_time_slot_ends_hour']) : "0";
		
		$pickup_time_slot_ends_min = (isset($pickup_form_data['tapsi_pickup_time_slot_ends_min']) && $pickup_form_data['tapsi_pickup_time_slot_ends_min'] !="") ? sanitize_text_field($pickup_form_data['tapsi_pickup_time_slot_ends_min']) : "0"; 

		$pickup_time_slot_ends_format = sanitize_text_field($pickup_form_data['tapsi_pickup_time_slot_ends_format']);

		if($pickup_time_slot_ends_format == "am") {
			$pickup_time_slot_ends_hour_12 = ($pickup_time_slot_ends_hour == "12") ? "0" : $pickup_time_slot_ends_hour;
			$pickup_time_slot_ends = ((int)$pickup_time_slot_ends_hour_12 * 60) + (int)$pickup_time_slot_ends_min;
		} else {
			$pickup_time_slot_ends_hour = ($pickup_time_slot_ends_hour == "12") ? "0" : $pickup_time_slot_ends_hour;
			$pickup_time_slot_ends = (((int)$pickup_time_slot_ends_hour + 12)*60) + (int)$pickup_time_slot_ends_min;
		}

		if($pickup_time_slot_ends_format == "am" && $pickup_time_slot_ends_hour == "12" && ($pickup_time_slot_ends_min =="0"||$pickup_time_slot_ends_min =="00")) {
				$pickup_time_slot_ends = 1440;
		}

		$pickup_time_slot_duration_time = (isset($pickup_form_data['tapsi_pickup_time_slot_duration_time']) && $pickup_form_data['tapsi_pickup_time_slot_duration_time'] !="") ? sanitize_text_field($pickup_form_data['tapsi_pickup_time_slot_duration_time']) : "0";
		$pickup_time_slot_duration_format = sanitize_text_field($pickup_form_data['tapsi_pickup_time_slot_duration_format']);

		if($pickup_time_slot_duration_format == "hour") {
			$each_time_slot = (int)$pickup_time_slot_duration_time * 60;
			$each_time_slot = $each_time_slot != 0 ? $each_time_slot : "";
		} else {
			$each_time_slot = (int)$pickup_time_slot_duration_time;
			$each_time_slot = $each_time_slot != 0 ? $each_time_slot : "";
		}

		$pickup_time_form_settings['enable_pickup_time'] = $enable_pickup_time;
		$pickup_time_form_settings['pickup_time_mandatory'] = $pickup_time_mandatory;
		$pickup_time_form_settings['field_label'] = $pickup_time_field_label;
		$pickup_time_form_settings['time_format'] = $pickup_time_format;
		$pickup_time_form_settings['pickup_time_starts'] = (string)$pickup_time_slot_starts;
		$pickup_time_form_settings['pickup_time_ends'] = (string)$pickup_time_slot_ends;
		$pickup_time_form_settings['each_time_slot'] = (string)$each_time_slot;
		$pickup_time_form_settings['max_pickup_per_slot'] = $pickup_time_maximum_order;
		$pickup_time_form_settings['disabled_current_pickup_time_slot'] = $disable_current_time_slot;
		$pickup_time_form_settings['auto_select_first_time'] = $auto_select_first_time;

		if(get_option('tapsi_woo_delivery_pickup_settings') == false) {
			update_option('tapsi_woo_delivery_pickup_settings', $pickup_time_form_settings);
		} else {
			$pickup_time_form_settings = array_merge(get_option('tapsi_woo_delivery_pickup_settings'),$pickup_time_form_settings);
			update_option('tapsi_woo_delivery_pickup_settings', $pickup_time_form_settings);
		}

		wp_send_json_success();
	}

	public function tapsi_woo_delivery_process_localization_settings() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
		
		$localization_settings_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );
		
		$order_limit_notice = sanitize_text_field($form_data['tapsi_woo_delivery_order_limit_notice']);
		$pickup_limit_notice = sanitize_text_field($form_data['tapsi_woo_delivery_pickup_limit_notice']);
		$delivery_details_text = sanitize_text_field($form_data['tapsi_woo_delivery_delivery_details_text']);
		$order_metabox_heading = sanitize_text_field($form_data['tapsi_woo_delivery_order_metabox_heading']);

		$checkout_date_notice = sanitize_text_field($form_data['tapsi_woo_delivery_checkout_date_notice']);
		$checkout_time_notice = sanitize_text_field($form_data['tapsi_woo_delivery_checkout_time_notice']);

		$checkout_pickup_date_notice = sanitize_text_field($form_data['tapsi_woo_delivery_checkout_pickup_date_notice']);
		$checkout_pickup_time_notice = sanitize_text_field($form_data['tapsi_woo_delivery_checkout_pickup_time_notice']);

		$checkout_delivery_option_notice = sanitize_text_field($form_data['tapsi_woo_delivery_checkout_delivery_option_notice']);

		$localization_settings_form_settings['order_limit_notice'] = $order_limit_notice;
		$localization_settings_form_settings['pickup_limit_notice'] = $pickup_limit_notice;
		$localization_settings_form_settings['delivery_details_text'] = $delivery_details_text;
		$localization_settings_form_settings['order_metabox_heading'] = $order_metabox_heading;
		$localization_settings_form_settings['checkout_delivery_option_notice'] = $checkout_delivery_option_notice;
		$localization_settings_form_settings['checkout_date_notice'] = $checkout_date_notice;
		$localization_settings_form_settings['checkout_time_notice'] = $checkout_time_notice;
		$localization_settings_form_settings['checkout_pickup_date_notice'] = $checkout_pickup_date_notice;
		$localization_settings_form_settings['checkout_pickup_time_notice'] = $checkout_pickup_time_notice;

		
		if(get_option('tapsi_woo_delivery_localization_settings') == false) {
			update_option('tapsi_woo_delivery_localization_settings', $localization_settings_form_settings);
		} else {
			$localization_settings_form_settings = array_merge(get_option('tapsi_woo_delivery_localization_settings'),$localization_settings_form_settings);
			update_option('tapsi_woo_delivery_localization_settings', $localization_settings_form_settings);
		}
		wp_send_json_success();
		
    }


    public function tapsi_woo_delivery_process_other_settings() { 
    	check_ajax_referer('tapsi_woo_delivery_nonce');
		
		$other_settings_form_settings = [];

		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		
		$field_position = sanitize_text_field($date_form_data['tapsi_woo_delivery_field_position']);
		$other_settings_form_settings['field_position'] = $field_position;

		$tapsi_disable_fields_for_downloadable_products = !isset($date_form_data['tapsi_disable_fields_for_downloadable_products']) ? false : true;

		$other_settings_form_settings['disable_fields_for_downloadable_products'] = $tapsi_disable_fields_for_downloadable_products;

		$custom_css = isset($date_form_data['tapsi_woo_delivery_code_editor_css']) ? sanitize_textarea_field(htmlentities($date_form_data['tapsi_woo_delivery_code_editor_css'])) : "";
		$other_settings_form_settings['custom_css'] = $custom_css;

		$delivery_heading_checkout = sanitize_text_field($date_form_data['tapsi_woo_delivery_delivery_heading_checkout']);

		$other_settings_form_settings['delivery_heading_checkout'] = $delivery_heading_checkout;
		
		if(get_option('tapsi_woo_delivery_other_settings') == false) {
			update_option('tapsi_woo_delivery_other_settings', $other_settings_form_settings);
		} else {
			$other_settings_form_settings = array_merge(get_option('tapsi_woo_delivery_other_settings'),$other_settings_form_settings);
			update_option('tapsi_woo_delivery_other_settings', $other_settings_form_settings);
		}
		wp_send_json_success();
		
    }

    /**
	 * Add custom column in orders page in admin panel
	*/
	public function tapsi_woo_delivery_add_custom_fields_orders_list($columns) {
		
		$delivery_details_text = (isset(get_option('tapsi_woo_delivery_localization_settings')['delivery_details_text']) && !empty(get_option('tapsi_woo_delivery_localization_settings')['delivery_details_text'])) ? get_option('tapsi_woo_delivery_localization_settings')['delivery_details_text'] : __("Delivery Details", "woo-delivery");

		$new_columns = [];

		foreach($columns as $name => $value)
		{
			$new_columns[$name] = $value;

			if($name == 'order_status') {
				$new_columns['order_delivery_details'] = __($delivery_details_text, "woo-delivery");
			}
		}
		return $new_columns;
	}

	public function tapsi_woo_delivery_show_custom_fields_data_orders_list($column) {
		
		global $post;

		$delivery_date_settings = get_option('tapsi_woo_delivery_date_settings');			
		$pickup_date_settings = get_option('tapsi_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('tapsi_woo_delivery_time_settings');
		$pickup_time_settings = get_option('tapsi_woo_delivery_pickup_settings');

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : __("Delivery Date", "woo-delivery");
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : __("Pickup Date", "woo-delivery");
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : __("Delivery Time", "woo-delivery");
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : __("Pickup Time", "woo-delivery");

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";

		$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";

		$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
		if($time_format == 12) {
			$time_format = "h:i A";
		} elseif ($time_format == 24) {
			$time_format = "H:i";
		}

		$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
		if($pickup_time_format == 12) {
			$pickup_time_format = "h:i A";
		} elseif ($pickup_time_format == 24) {
			$pickup_time_format = "H:i";
		}

		if($column == 'order_delivery_details')
		{
			if(metadata_exists('post', $post->ID, 'delivery_date') && get_post_meta($post->ID, 'delivery_date', true) !="")
			{
				$delivery_date = date($delivery_date_format, strtotime(get_post_meta( $post->ID, 'delivery_date', true )));
		    	
		    	echo __($delivery_date_field_label, "woo-delivery").": " . $delivery_date;	
			}

			if(metadata_exists('post', $post->ID, 'pickup_date') && get_post_meta($post->ID, 'pickup_date', true) !="")
			{
				
		    	$pickup_date = date($pickup_date_format, strtotime(get_post_meta( $post->ID, 'pickup_date', true )));

		    	echo __($pickup_date_field_label, "woo-delivery").": " . $pickup_date; 	
			}

			if(metadata_exists('post', $post->ID, 'delivery_time') && get_post_meta($post->ID, 'delivery_time', true) !="")
			{
				echo " <br > ";
				$times = get_post_meta($post->ID,"delivery_time",true);
				$minutes = explode(' - ', $times);

	    		$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));

				echo __($delivery_time_field_label, "woo-delivery").": " . $time_value;

			}


			if(metadata_exists('post', $post->ID, 'pickup_time') && get_post_meta($post->ID, 'pickup_time', true) !="")
			{
				echo " <br > ";
				$pickup_times = get_post_meta($post->ID,"pickup_time",true);
				$pickup_minutes = explode(' - ', $pickup_times);

	    		$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));
				echo __($pickup_time_field_label, "woo-delivery").": " . $pickup_time_value;

			}
		}

	}

	public function tapsi_woo_delivery_information_after_shipping_address($order){
	    
	    $order_items = $order->get_items();

	    $delivery_date_settings = get_option('tapsi_woo_delivery_date_settings');			
	    $pickup_date_settings = get_option('tapsi_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('tapsi_woo_delivery_time_settings');
		$pickup_time_settings = get_option('tapsi_woo_delivery_pickup_settings');


		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : __("Delivery Date", "woo-delivery");
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : __("Pickup Date", "woo-delivery");
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : __("Delivery Time", "woo-delivery");
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : __("Pickup Time", "woo-delivery");

		$delivery_option_settings = get_option('tapsi_woo_delivery_option_delivery_settings');
	    $enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;
	    /*$order_type_field_label = (isset($delivery_option_settings['delivery_option_label']) && !empty($delivery_option_settings['delivery_option_label'])) ? stripslashes($delivery_option_settings['delivery_option_label']) : __("Order Type", "woo-delivery");
	    $delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : __("Delivery", "woo-delivery");
		$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : __("Pickup", "woo-delivery");*/

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
	        $order_id = $order->get_id();
	    } else {
	        $order_id = $order->id;
	    }

	    /*if($enable_delivery_option && metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="") {
	    	

	    	if(get_post_meta($order_id, 'delivery_type', true) == "delivery") {

	    		echo '<p><strong>'.__($order_type_field_label, "woo-delivery").':</strong> ' . $delivery_field_label . '</p>';

			} elseif(get_post_meta($order_id, 'delivery_type', true) == "pickup") {
				
				echo '<p><strong>'.__($order_type_field_label, "woo-delivery").':</strong> ' . $pickup_field_label . '</p>';
			}

	    }*/

	    if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id, 'delivery_date', true) !="") {

	    	$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";

	    	$delivery_date = date($delivery_date_format, strtotime(get_post_meta( $order_id, 'delivery_date', true )));

	    	echo '<p><strong>'.__($delivery_date_field_label, "woo-delivery").':</strong> ' . $delivery_date . '</p>';
	    	
	    }

	    if(metadata_exists('post', $order_id, 'pickup_date') && get_post_meta($order_id, 'pickup_date', true) !="") {

			$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";

	    	$pickup_date = date($pickup_date_format, strtotime(get_post_meta( $order_id, 'pickup_date', true )));
	    	echo '<p><strong>'.__($pickup_date_field_label, "woo-delivery").':</strong> ' . $pickup_date . '</p>'; 
	    	
	    }

	    if(metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id, 'delivery_time', true) !="") {

	    	$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
			if($time_format == 12) {
				$time_format = "h:i A";
			} elseif ($time_format == 24) {
				$time_format = "H:i";
			}

	    	$minutes = get_post_meta($order_id,"delivery_time",true);
	    	$minutes = explode(' - ', $minutes);

    		echo '<p><strong>'.__($delivery_time_field_label, "woo-delivery").':</strong> ' . date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1])) . '</p>';    			
	    }

	    if(metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id, 'pickup_time', true) !="") {

	    	$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
			if($pickup_time_format == 12) {
				$pickup_time_format = "h:i A";
			} elseif ($pickup_time_format == 24) {
				$pickup_time_format = "H:i";
			}
			
	    	$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
	    	$pickup_minutes = explode(' - ', $pickup_minutes);

    		echo '<p><strong>'.__($pickup_time_field_label, "woo-delivery").':</strong> ' . date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1])) . '</p>';			

	    	
	    }
	    
	}

	public function tapsi_woo_delivery_review_notice() {
	    $options = get_option('tapsi_woo_delivery_review_notice');

	    $activation_time = get_option('tapsi-woo-delivery-activation-time');

	    $notice = '<div class="tapsi-woo-delivery-review-notice notice notice-success is-dismissible">';
        $notice .= '<img class="tapsi-woo-delivery-review-notice-left" src="'.TAPSI_WOO_DELIVERY_URL.'admin/images/woo-delivery-logo.png" alt="tapsi-woo-delivery">';
        $notice .= '<div class="tapsi-woo-delivery-review-notice-right">';
        $notice .= '<p><b>'.__("We have worked relentlessly to develop the plugin and it would really appreciate us if you dropped a short review about the plugin. Your review means a lot to us and we are working to make the plugin more awesome. Thanks for using WooCommerce Delivery & Pickup Date Time.","woo-delivery").'</b></p>';
        $notice .= '<ul>';
        $notice .= '<li><a val="later" href="#">'.__("Remind me later","woo-delivery").'</a></li>';
        $notice .= '<li><a style="font-weight:bold;" val="given" href="#" target="_blank">'.__("Review Here","woo-delivery").'</a></li>';
		$notice .= '<li><a val="never" href="#">'.__("I would not","woo-delivery").'</a></li>';	        
        $notice .= '</ul>';
        $notice .= '</div>';
        $notice .= '</div>';
        
	    if(!$options && time()>= $activation_time + (60*60*24*15)){
	        echo $notice;
	    } else if(is_array($options)) {
	        if((!array_key_exists('review_notice',$options)) || ($options['review_notice'] =='later' && time()>=($options['updated_at'] + (60*60*24*30) ))){
	            echo $notice;
	        }
	    }
	}

	public function tapsi_woo_delivery_save_review_notice() {
	    $notice = sanitize_text_field($_POST['notice']);
	    $value = array();
	    $value['review_notice'] = $notice;
	    $value['updated_at'] = time();

	    update_option('tapsi_woo_delivery_review_notice',$value);
	    wp_send_json_success($value);
	}


	public function tapsi_woo_delivery_custom_meta_box() {
		$order_metabox_heading = (isset(get_option('tapsi_woo_delivery_localization_settings')['order_metabox_heading']) && !empty(get_option('tapsi_woo_delivery_localization_settings')['order_metabox_heading'])) ? get_option('tapsi_woo_delivery_localization_settings')['order_metabox_heading'] : __("Delivery/Pickup Date & Time", "woo-delivery");
		add_meta_box( 'tapsi_woo_delivery_meta_box', __($order_metabox_heading,'woo-delivery'), array($this,"tapsi_woo_delivery_meta_box_markup"), 'shop_order', 'normal', 'default', null );
	}

	public function tapsi_woo_delivery_meta_box_markup() {
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		global $post;

		$order = wc_get_order( $post->ID );
		$order_items = $order->get_items();

		$today = date('Y-m-d', time());

		$delivery_date_settings = get_option('tapsi_woo_delivery_date_settings');
		$pickup_date_settings = get_option('tapsi_woo_delivery_pickup_date_settings');
		$delivery_time_settings = get_option('tapsi_woo_delivery_time_settings');
		$pickup_time_settings = get_option('tapsi_woo_delivery_pickup_settings');

		$delivery_option_settings = get_option('tapsi_woo_delivery_option_delivery_settings');

	    $enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

	    $order_type_field_label = (isset($delivery_option_settings['delivery_option_label']) && !empty($delivery_option_settings['delivery_option_label'])) ? stripslashes($delivery_option_settings['delivery_option_label']) : __("Select Order Type", "woo-delivery");

		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
		
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

		$disable_dates = [];
		$pickup_disable_dates = [];

		$current_time = (date("G")*60)+date("i");


		if($enable_delivery_option && metadata_exists('post', $post->ID, 'delivery_type')) {
	    	$delivery_type = get_post_meta(  $post->ID, 'delivery_type', true );
	    } else {
	    	if(($enable_delivery_date || $enable_delivery_time) && !$enable_pickup_date && !$enable_pickup_time) {
	    		$delivery_type="delivery";
	    	} elseif(($enable_pickup_date || $enable_pickup_time) && !$enable_delivery_date && !$enable_delivery_time) {
	    		$delivery_type="pickup";
	    	} else {
	    		$delivery_type="";
	    	}
	    	
	    }

	    $delivery_date_format = (isset($delivery_date_settings['date_format']) && $delivery_date_settings['date_format'] != "") ? $delivery_date_settings['date_format'] : "F j, Y";

	    if(metadata_exists('post', $post->ID, 'delivery_date')) {
	    	$delivery_date = date($delivery_date_format, strtotime(get_post_meta( $post->ID, 'delivery_date', true )));
	    } else {
	    	$delivery_date="";
	    }

	    $pickup_date_format = (isset($pickup_date_settings['date_format']) && $pickup_date_settings['date_format'] != "") ? $pickup_date_settings['date_format'] : "F j, Y";

	    if(metadata_exists('post', $post->ID, 'pickup_date')) {
	    	$pickup_date = date($pickup_date_format, strtotime(get_post_meta( $post->ID, 'pickup_date', true )));
	    } else {
	    	$pickup_date="";
	    }

	    $time_options = Tapsi_Woo_Delivery_Time_Option::delivery_time_option($delivery_time_settings,"meta_box");
	    $pickup_options = Tapsi_Woo_Delivery_Pickup_Option::pickup_time_option($pickup_time_settings,"meta_box");

	    $delivery_options = Tapsi_Woo_Delivery_Delivery_Option::delivery_option($delivery_option_settings,"meta_box");


	    if(metadata_exists('post', $post->ID, 'delivery_time')) {
	    	$time = get_post_meta($post->ID,"delivery_time",true);
	    } else {
	    	$time="";
	    }

	    if(metadata_exists('post', $post->ID, 'pickup_time')) {
	    	$pickup_time = get_post_meta($post->ID,"pickup_time",true);
	    } else {
	    	$pickup_time="";
	    }


		$localization_settings = get_option('tapsi_woo_delivery_localization_settings');
		$order_limit_notice = (isset($localization_settings['order_limit_notice']) && !empty($localization_settings['order_limit_notice'])) ? "(".stripslashes($localization_settings['order_limit_notice']).")" : __("(Maximum Delivery Limit Exceed)", "woo-delivery");
		$pickup_limit_notice = (isset($localization_settings['pickup_limit_notice']) && !empty($localization_settings['pickup_limit_notice'])) ? "(".stripslashes($localization_settings['pickup_limit_notice']).")" : __("(Maximum Pickup Limit Exceed)", "woo-delivery");

		$delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? $delivery_option_settings['delivery_label'] : __("Delivery", "woo-delivery");
		$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? $delivery_option_settings['pickup_label'] : __("Pickup", "woo-delivery");

		$off_days = (isset($delivery_date_settings['off_days']) && !empty($delivery_date_settings['off_days'])) ? $delivery_date_settings['off_days'] : array();
			$selectable_start_date = date('Y-m-d H:i:s', time());
			$start_date = new DateTime($selectable_start_date);
			if(count($off_days)) {
				$date = $start_date;
				foreach ($off_days as $year => $months) {
					foreach($months as $month =>$days){
						$month_num = date_parse($month)['month'];
						if(strlen($month_num) == 1) {
							$month_num_final = "0".$month_num;
						} else {
							$month_num_final = $month_num;
						}
						$days = explode(',', $days);
						foreach($days as $day){
							$disable_dates[] = $year . "-" . $month_num_final . "-" .$day;
							$pickup_disable_dates[] = $year . "-" . $month_num_final . "-" .$day;
						}
					}
				}
			}

        $meta_box = '<div data-today_date="'.$today.'" id="tapsi_woo_delivery_admin_setting_wrapper">';
        $meta_box .= '<input type="hidden" id="tapsi_woo_delivery_meta_box_order_id" value="' . $post->ID . '" readonly>';
        if(!$enable_delivery_option) {
        	$meta_box .= '<div style="display:none">';
        }
	    $meta_box .= '<select style="width:100%;margin:5px auto;" name ="tapsi_woo_delivery_meta_box_delivery_selection_field" id="tapsi_woo_delivery_meta_box_delivery_selection_field">';
	    		if($delivery_type == '') {
	    			$meta_box .= '<option value="" selected>'.$order_type_field_label.'</option>';
	    		}
	    		
	    		foreach($delivery_options as $key => $value) {
	    			$selected = ($key == $delivery_type) ? "selected" : "";
	    			$meta_box .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
	    		}
	    $meta_box .= '</select>';

	    if(!$enable_delivery_option) {
        	$meta_box .= '</div>';
        }

        
        if($enable_delivery_date) {

        	$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : __("Delivery Date", "woo-delivery");			

        	$delivery_days = isset($delivery_date_settings['delivery_days']) && $delivery_date_settings['delivery_days'] != "" ? $delivery_date_settings['delivery_days'] : "6,0,1,2,3,4,5";

			$week_starts_from = (isset($delivery_date_settings['week_starts_from']) && !empty($delivery_date_settings['week_starts_from']))?$delivery_date_settings['week_starts_from']:"0";
		
			$selectable_date = (isset($delivery_date_settings['selectable_date']) && !empty($delivery_date_settings['selectable_date']))?$delivery_date_settings['selectable_date']:365;


			$delivery_days = explode(',', $delivery_days);

			$week_days = ['0', '1', '2', '3', '4', '5', '6'];
			$disable_week_days = array_values(array_diff($week_days, $delivery_days));

			$disable_dates = array_unique($disable_dates, false);
			$disable_dates = array_values($disable_dates);

			$disable_week_days = implode(",",$disable_week_days);
		    $disable_dates = implode("::",$disable_dates);

	        $meta_box .= '<input style="width:100%;margin:5px auto;display:none" type="text" id="tapsi_woo_delivery_meta_box_datepicker" placeholder="'.$delivery_date_field_label.'" name="tapsi_woo_delivery_meta_box_datepicker" data-disable_dates="'.$disable_dates.'" data-selectable_dates="'.$selectable_date.'" data-disable_week_days="'.$disable_week_days.'" data-week_starts_from="'.$week_starts_from.'" data-date_format="'.$delivery_date_format.'" value="' . $delivery_date . '">';
    	}

    	if($enable_pickup_date) {

    		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : __("Pickup Date", "woo-delivery");

    		$pickup_days = isset($pickup_date_settings['pickup_days']) && $pickup_date_settings['pickup_days'] != "" ? $pickup_date_settings['pickup_days'] : "6,0,1,2,3,4,5";

			$pickup_week_starts_from = (isset($pickup_date_settings['week_starts_from']) && !empty($pickup_date_settings['week_starts_from']))?$pickup_date_settings['week_starts_from']:"0";
		
			$pickup_selectable_date = (isset($pickup_date_settings['selectable_date']) && !empty($pickup_date_settings['selectable_date']))?$pickup_date_settings['selectable_date']:365;

			$pickup_days = explode(',', $pickup_days);

			$week_days = ['0', '1', '2', '3', '4', '5', '6'];
			$pickup_disable_week_days = array_values(array_diff($week_days, $pickup_days));

			$pickup_disable_dates = array_unique($pickup_disable_dates, false);
			$pickup_disable_dates = array_values($pickup_disable_dates);

			$pickup_disable_week_days = implode(",",$pickup_disable_week_days);
		    $pickup_disable_dates = implode("::",$pickup_disable_dates);

	        $meta_box .= '<input style="width:100%;margin:5px auto;display:none" type="text" id="tapsi_woo_delivery_meta_box_pickup_datepicker" placeholder="'.$pickup_date_field_label.'" name="tapsi_woo_delivery_meta_box_pickup_datepicker" data-pickup_disable_dates="'.$pickup_disable_dates.'" data-pickup_selectable_dates="'.$pickup_selectable_date.'" data-pickup_disable_week_days="'.$pickup_disable_week_days.'" data-pickup_week_starts_from="'.$pickup_week_starts_from.'" data-pickup_date_format="'.$pickup_date_format.'" value="' . $pickup_date . '">';
    	}


    	if($enable_delivery_time) {
    		$meta_box .= '<select style="width:100%;margin:5px auto;display:none" name ="tapsi_woo_delivery_meta_box_time_field" id="tapsi_woo_delivery_meta_box_time_field" data-order_limit_notice="'.$order_limit_notice.'">';
    		$meta_box .= '<option value="" disabled="disabled" selected>'.__("Select Time Slot", "woo-delivery").'</option>';
    		foreach($time_options as $key => $value) {
    			$selected = ($key == $time) ? "selected" : "";
    			$meta_box .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    		}
    		$meta_box .= '</select>';
    	}

    	if($enable_pickup_time) {
    		$meta_box .= '<select style="width:100%;margin:5px auto;display:none" name ="tapsi_woo_delivery_meta_box_pickup_field" id="tapsi_woo_delivery_meta_box_pickup_field" data-pickup_limit_notice="'.$pickup_limit_notice.'">';
    		$meta_box .= '<option value="" disabled="disabled" selected>'.__("Select Pickup Slot", "woo-delivery").'</option>';
    		foreach($pickup_options as $key => $value) {
    			$selected = ($key == $pickup_time) ? "selected" : "";
    			$meta_box .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    		}
    		$meta_box .= '</select>';
    	}

    	$meta_box .= '</div>';

    	$meta_box .= '<div class="tapsi-woo-delivery-metabox-update-section" data-plugin-url="'.TAPSI_WOO_DELIVERY_URL.'">';
        $meta_box .= '<a class="tapsi-woo-delivery-metabox-update-btn" href="#" style="margin-right:10px"><button type="button" class="button button-primary">'.__("Update", "woo-delivery").'</button></a>';
        
        $meta_box .= '</div>';
        echo $meta_box;
        
	}

	public function tapsi_woo_delivery_save_meta_box_information() {
		check_ajax_referer('tapsi_woo_delivery_nonce');
		$delivery_time_settings = get_option('tapsi_woo_delivery_time_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$order_id = sanitize_text_field($_POST['orderId']);

		if(isset($_POST['deliveryOption']) && $_POST['deliveryOption'] == "delivery") {

			delete_post_meta($order_id, 'pickup_date');
			delete_post_meta($order_id, 'pickup_time');
			update_post_meta( $order_id, 'delivery_type', 'delivery' );

			if(isset($_POST['date'])) {
				$en_date = sanitize_text_field($_POST['date']);
				update_post_meta( $order_id, 'delivery_date', date("Y-m-d", strtotime($en_date)) );
			} else {
				delete_post_meta($order_id, 'delivery_date');
			}

			if(isset($_POST['time'])) {
				$time = sanitize_text_field($_POST['time']);
				update_post_meta( $order_id, 'delivery_time', $time );
			} else {
				delete_post_meta($order_id, 'delivery_time');
			}
		} elseif(isset($_POST['deliveryOption']) && $_POST['deliveryOption'] == "pickup") {
			delete_post_meta($order_id, 'delivery_date');
			delete_post_meta($order_id, 'delivery_time');
			update_post_meta( $order_id, 'delivery_type', 'pickup' );
			if(isset($_POST['pickupDate'])) {
				$en_date = sanitize_text_field($_POST['pickupDate']);
				update_post_meta( $order_id, 'pickup_date', date("Y-m-d", strtotime($en_date)) );
			} else {
				delete_post_meta($order_id, 'pickup_date');
			}

			if(isset($_POST['pickupTime'])) {
				$pickup_time = sanitize_text_field($_POST['pickupTime']);
				update_post_meta( $order_id, 'pickup_time', $pickup_time );
			} else {
				delete_post_meta($order_id, 'pickup_time');
			}
		} else {
			if((isset($_POST['date']) || isset($_POST['time'])) && (!isset($_POST['pickupDate']) && !isset($_POST['pickupTime']))) {
				update_post_meta( $order_id, 'delivery_type', 'delivery' );
			} elseif((isset($_POST['pickupDate']) || isset($_POST['pickupTime'])) && (!isset($_POST['date']) && !isset($_POST['time']))) {
				update_post_meta( $order_id, 'delivery_type', 'pickup' );
			} else {
				update_post_meta( $order_id, 'delivery_type', 'delivery' );
			}
			if(isset($_POST['date']) && !isset($_POST['pickupDate'])) {
				$en_date = sanitize_text_field($_POST['date']);
				update_post_meta( $order_id, 'delivery_date', date("Y-m-d", strtotime($en_date)) );
			} elseif(isset($_POST['date']) && $_POST['date'] != "") {
				$en_date = sanitize_text_field($_POST['date']);
				update_post_meta( $order_id, 'delivery_date', date("Y-m-d", strtotime($en_date)) );

			} else {
				delete_post_meta($order_id, 'delivery_date');
			}

			if(isset($_POST['time']) && !isset($_POST['pickupTime'])) {
				$time = sanitize_text_field($_POST['time']);
				update_post_meta( $order_id, 'delivery_time', $time );
			} elseif(isset($_POST['time'])) {
				$time = sanitize_text_field($_POST['time']);
				update_post_meta( $order_id, 'delivery_time', $time );
			} else {
				delete_post_meta($order_id, 'delivery_time');
			}

			if(isset($_POST['pickupDate']) && !isset($_POST['date'])) {
				$en_date = sanitize_text_field($_POST['pickupDate']);
				update_post_meta( $order_id, 'pickup_date', date("Y-m-d", strtotime($en_date)) );
			} elseif(isset($_POST['pickupDate']) && $_POST['pickupDate'] != "") {
				$en_date = sanitize_text_field($_POST['pickupDate']);
				update_post_meta( $order_id, 'pickup_date', date("Y-m-d", strtotime($en_date)) );
			} else {
				delete_post_meta($order_id, 'pickup_date');
			}

			if(isset($_POST['pickupTime']) && !isset($_POST['time'])) {
				$pickup_time = sanitize_text_field($_POST['pickupTime']);
				update_post_meta( $order_id, 'pickup_time', $pickup_time );

			} elseif(isset($_POST['pickupTime'])) {
				$pickup_time = sanitize_text_field($_POST['pickupTime']);
				update_post_meta( $order_id, 'pickup_time', $pickup_time );
			} else {
				delete_post_meta($order_id, 'pickup_time');
			}
		}

		wp_send_json_success();
	}

	public function tapsi_woo_delivery_meta_box_get_orders() {

		check_ajax_referer('tapsi_woo_delivery_nonce');
		
		$delivery_time_settings = get_option('tapsi_woo_delivery_time_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$max_order_per_slot = (isset($delivery_time_settings['max_order_per_slot']) && !empty($delivery_time_settings['max_order_per_slot'])) ? $delivery_time_settings['max_order_per_slot'] : 0;
		
		$disabled_current_time_slot = (isset($delivery_time_settings['disabled_current_time_slot']) && !empty($delivery_time_settings['disabled_current_time_slot'])) ? $delivery_time_settings['disabled_current_time_slot'] : false;

		if(isset($_POST['onlyDeliveryTime']) && $_POST['onlyDeliveryTime']) {
			$order_date = date("Y-m-d", sanitize_text_field(strtotime($_POST['date']))); 
			$args = array(
		        'limit' => -1,
		        'date_created' => $order_date,
		        'delivery_type' => 'delivery',
		        'return' => 'ids'
		    );

		} else {
			$args = array(
		        'limit' => -1,
		        'delivery_date' => date("Y-m-d", strtotime(sanitize_text_field($_POST['date']))),
		        'return' => 'ids'
		    );
		}

	    $order_ids = wc_get_orders( $args );

		$delivery_times = [];

		foreach ($order_ids as $order) {
			$date = get_post_meta($order,"delivery_date",true);
			$time = get_post_meta($order,"delivery_time",true);

			if((isset($date) && isset($time)) || isset($time)) {
				$delivery_times[] = $time;
			}
		}

		$current_time = (date("G")*60)+date("i");

		$response = [
			"delivery_times" => $delivery_times,
			"max_order_per_slot" => $max_order_per_slot,
			'disabled_current_time_slot' => $disabled_current_time_slot,
			"current_time" => $current_time,
		];
		$response = json_encode($response);
		wp_send_json_success($response);
	}


	public function tapsi_woo_delivery_meta_box_get_orders_pickup() {

		check_ajax_referer('tapsi_woo_delivery_nonce');
		
		$delivery_pickup_settings = get_option('tapsi_woo_delivery_pickup_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$pickup_max_order_per_slot = (isset($delivery_pickup_settings['max_pickup_per_slot']) && !empty($delivery_pickup_settings['max_pickup_per_slot'])) ? $delivery_pickup_settings['max_pickup_per_slot'] : 0;

		
		$pickup_disabled_current_time_slot = (isset($delivery_pickup_settings['disabled_current_pickup_time_slot']) && !empty($delivery_pickup_settings['disabled_current_pickup_time_slot'])) ? $delivery_pickup_settings['disabled_current_pickup_time_slot'] : false;

		
		if(isset($_POST['onlyPickupTime']) && $_POST['onlyPickupTime']) {
			$order_date = date("Y-m-d", strtotime(sanitize_text_field($_POST['date']))); 
			$args = array(
		        'limit' => -1,
		        'date_created' => $order_date,
		        'delivery_type' => 'pickup',
		        'return' => 'ids'
		    );

		} else {
			$pickup_date = date("Y-m-d", strtotime(sanitize_text_field($_POST['date'])));
			$args = array(
		        'limit' => -1,
		        'pickup_date' => $pickup_date,
		        'return' => 'ids'
		    );		    
		}

		$order_ids = wc_get_orders( $args );

		$pickup_delivery_times = [];

	  	foreach ($order_ids as $order) {
			$date = get_post_meta($order,"pickup_date",true);
			$time = get_post_meta($order,"pickup_time",true);
			
			if((isset($date) && isset($time)) || isset($time)) {
				$pickup_delivery_times[] = $time;
			}

		}

		$current_time = (date("G")*60)+date("i");

		$response = [
			"pickup_delivery_times" => $pickup_delivery_times,
			"pickup_max_order_per_slot" => $pickup_max_order_per_slot,
			'pickup_disabled_current_time_slot' => $pickup_disabled_current_time_slot,
			"current_time" => $current_time,
		];
		$response = json_encode($response);
		wp_send_json_success($response);

	}

	public function tapsi_woo_delivery_admin_disable_max_delivery_pickup_date() {
    	$delivery_selection = isset($_POST['deliverySelector']) ? sanitize_text_field($_POST['deliverySelector']) : "delivery";

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$disable_delivery_date_passed_time = [];
		$disable_pickup_date_passed_time = [];

		$delivery_time_settings = get_option('tapsi_woo_delivery_time_settings');
		$pickup_time_settings = get_option('tapsi_woo_delivery_pickup_settings');

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
	  	
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		
		
		if($enable_delivery_time) {

			$time_slot_end = [0];

			$time_settings = get_option('tapsi_woo_delivery_time_settings');
			$time_slot_end[] = (int)$time_settings['delivery_time_ends'];												
			$highest_timeslot_end = max($time_slot_end);

			$current_time = (date("G")*60)+date("i");

			if($current_time>$highest_timeslot_end) {
				$disable_delivery_date_passed_time[] = date('Y-m-d', time());
			}

		}

		if($enable_pickup_time) {

			$pickup_slot_end = [0];
		    $pickup_settings = get_option('tapsi_woo_delivery_pickup_settings');
			$pickup_slot_end[] = (int)$pickup_settings['pickup_time_ends'];;
			$highest_pickupslot_end = max($pickup_slot_end);

			$current_time = (date("G")*60)+date("i");

			if($current_time>$highest_pickupslot_end) {
				$disable_pickup_date_passed_time[] = date('Y-m-d', time());
			}

		}

		$response=[
			"disable_delivery_date_passed_time" => $disable_delivery_date_passed_time,
			"disable_pickup_date_passed_time" => $disable_pickup_date_passed_time,
		];
		$response = json_encode($response);
		wp_send_json_success($response);
		
	}

	public function override_post_meta_box_order( $order ) {
	    return array(
	        'normal' => join( 
	            ",", 
	            array(
	                'order_data',
	                'tapsi_woo_delivery_meta_box',
	                'woocommerce-order-items',
	            )
	        ),
	    );
	}


    public function tapsi_woo_delivery_main_layout() {
        include_once TAPSI_WOO_DELIVERY_DIR . '/admin/partials/tapsi-woo-delivery-admin-display.php';
    }


    public function tapsi_woo_delivery_add_delivery_type() {
    	delete_option('tapsi_woo_delivery_date_time_change');
    }

}
