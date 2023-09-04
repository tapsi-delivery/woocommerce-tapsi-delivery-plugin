<?php
require_once CODEROCKZ_WOO_DELIVERY_DIR . 'includes/class-coderockz-woo-delivery-helper.php';
if( !class_exists( 'Coderockz_Woo_Delivery_Delivery_Option' ) ) {

	class Coderockz_Woo_Delivery_Delivery_Option {

		public static function delivery_option($delivery_option_settings,$meta_box=null) {
			
			$helper = new Coderockz_Woo_Delivery_Helper();
			$timezone = $helper->get_the_timezone();
			date_default_timezone_set($timezone);
			
			$delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : __("Delivery", "woo-delivery");
			$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : __("Pickup", "woo-delivery");

			$delivery_option = [];

			if(is_null($meta_box)){
				$delivery_option[''] = '';
			}
				
			$delivery_option['delivery'] = __( $delivery_field_label, 'woo-delivery' );

			$delivery_option['pickup'] = __( $pickup_field_label, 'woo-delivery' );
			
			return $delivery_option;
		}
	}
}