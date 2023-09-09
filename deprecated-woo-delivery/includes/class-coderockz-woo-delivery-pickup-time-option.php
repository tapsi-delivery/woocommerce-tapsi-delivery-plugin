<?php

require_once CODEROCKZ_WOO_DELIVERY_DIR . 'includes/class-coderockz-woo-delivery-helper.php';

if( !class_exists( 'Coderockz_Woo_Delivery_Pickup_Option' ) ) {
	
	class Coderockz_Woo_Delivery_Pickup_Option {
		
		public static function pickup_time_option($pickup_time_settings,$meta_box=null) {
			
			
			$helper = new Coderockz_Woo_Delivery_Helper();
			$timezone = $helper->get_the_timezone();
			date_default_timezone_set($timezone);

			$currency_symbol = get_woocommerce_currency_symbol();
			
			$start = (isset($pickup_time_settings['pickup_time_starts']) && !empty($pickup_time_settings['pickup_time_starts'])) ? $pickup_time_settings['pickup_time_starts'] : "0";
			$end = (isset($pickup_time_settings['pickup_time_ends']) && !empty($pickup_time_settings['pickup_time_ends'])) ? $pickup_time_settings['pickup_time_ends'] : "1440";
			$time_slot = (isset($pickup_time_settings['each_time_slot']) && !empty($pickup_time_settings['each_time_slot'])) ? $pickup_time_settings['each_time_slot'] : "180";

			$time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format'])) ? $pickup_time_settings['time_format'] : "12";
			if($time_format == 12) {
				$time_format = "h:i A";
			}
			elseif ($time_format == 24) {
				$time_format = "H:i";
			}

			$result = [];


			$it = $end;
			if(($end-$start)%$time_slot !=0){
				$remaining_time = ($end-$start)%$time_slot;
				$it = $end-$remaining_time;
				$fractional_from_hour = date($time_format, mktime(0, (int)$it));
				if($time_format == "H:i" && $end == 1440){
					$fractional_to_hour = "24:00";
				} else {
					$fractional_to_hour = date($time_format, mktime(0, (int)$end));
				}
				$result[date("H:i", mktime(0, (int)$it)) . ' - ' . date("H:i", mktime(0, (int)$end))] = $fractional_from_hour . ' - ' . $fractional_to_hour;
							
			}
			while($it > $start) {
				$to = $it;
				$from = $it - $time_slot;
				$from_hour = date($time_format, mktime(0, (int)$from));
				if($time_format == "H:i" && $to == 1440){
					$to_hour = "24:00";
				} else {
					$to_hour = date($time_format, mktime(0, (int)$to));
				}
				$result[date("H:i", mktime(0, (int)$from)) . ' - ' . date("H:i", mktime(0, (int)$to))] = $from_hour . ' - ' . $to_hour;
				
				$it = $from;
			}

			if(is_null($meta_box)){
				$result[''] = '';
			}
			
			$result = array_reverse($result);

			return $result;
		}
	
	}
}