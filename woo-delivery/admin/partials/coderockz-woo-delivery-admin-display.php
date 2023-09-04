<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Coderockz_Woo_Delivery
 * @subpackage Coderockz_Woo_Delivery/admin/partials
 */

$date_settings = get_option('coderockz_woo_delivery_date_settings');
$time_settings = get_option('coderockz_woo_delivery_time_settings');
if(isset($date_settings['delivery_days'])) {
	$selected_delivery_day = explode(',', get_option('coderockz_woo_delivery_date_settings')['delivery_days']);
} else {
	$selected_delivery_day = array();
}

$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
if($pickup_date_settings != false && isset($pickup_date_settings['pickup_days']) && $pickup_date_settings['pickup_days'] != "" ) {
	$selected_pickup_day = explode(',', $pickup_date_settings['pickup_days']);
} else {
	$selected_pickup_day = [];
}

$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
$other_settings = get_option('coderockz_woo_delivery_other_settings');
$localization_settings = get_option('coderockz_woo_delivery_localization_settings');

$currency_code = get_woocommerce_currency();
$store_location_timezone = isset($time_settings['store_location_timezone']) && $time_settings['store_location_timezone'] != ""? $time_settings['store_location_timezone'] : "";

?>
<div class="coderockz-woo-delivery-wrap">

<div class="coderockz-woo-delivery-container">
	<div class="coderockz-woo-delivery-container-header">
		<img style="max-width: 75px;float: left;display: block;padding-bottom: 2px;" src="<?php echo CODEROCKZ_WOO_DELIVERY_URL; ?>admin/images/woo-delivery-logo.png" alt="coderockz-woo-delivery">
		<div style="float:left;margin-left:15px;">
		<p style="margin: 0!important;text-transform:uppercase;border-bottom:2px solid #1F9E60;padding-bottom:3px;font-size: 20px;font-weight: 700;color: #654C29;">WooCommerce</p>
		<p style="margin: 0!important;text-transform:uppercase;padding-top:3px;font-size: 11px;color: #654C29;font-weight: 600;">Delivery & Pickup Date Time</p>
		</div>
		
		<!-- <a style="float: right;margin-top: 10px;" href="https://coderockz.com/woo-delivery/its-my-life/" target="_blank" class="coderockz-woo-delivery-buy-now-btn">Live Demo</a> -->
		<a style="float: right;margin-top: 10px;margin-right:10px;" href="https://coderockz.com/downloads/woocommerce-delivery-date-time-wordpress-plugin/" target="_blank" class="coderockz-woo-delivery-buy-now-btn">Get Pro</a>
	</div>
	<div class="coderockz-woo-delivery-free-vertical-tabs">
		<div class="coderockz-woo-delivery-free-tabs">
			<button data-tab="tab1"><i class="dashicons dashicons-location-alt" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Timezone Settings', 'woo-delivery'); ?></button>
			<button data-tab="tab2"><i class="dashicons dashicons-plugins-checked" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Order Settings', 'woo-delivery'); ?></button>
			<button data-tab="tab3"><i class="dashicons dashicons-calendar-alt" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Delivery Date', 'woo-delivery'); ?></button>
			<button data-tab="tab4"><i class="dashicons dashicons-calendar" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Pickup Date', 'woo-delivery'); ?></button>
			<button data-tab="tab5"><i class="dashicons dashicons-hidden" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Off Days', 'woo-delivery'); ?></button>
			<button data-tab="tab6"><i class="dashicons dashicons-clock" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Delivery Time', 'woo-delivery'); ?></button>
			<button data-tab="tab7"><i class="dashicons dashicons-cart" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Pickup Time', 'woo-delivery'); ?></button>
			<button data-tab="tab8"><i class="dashicons dashicons-translation" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Localization', 'woo-delivery'); ?></button>
			<button data-tab="tab9"><i class="dashicons dashicons-admin-settings" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Others', 'woo-delivery'); ?></button>
			<button data-tab="tab10"><i class="dashicons dashicons-clipboard" style="margin-bottom: 3px;margin-right: 10px;"></i><?php _e('Free VS Pro', 'woo-delivery'); ?></button>
		</div>
		<div class="coderockz-woo-delivery-maincontent">
			<div data-tab="tab1" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('TimeZone Settings', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-timezone-tab-notice"><span class="dashicons dashicons-yes"></span><?php _e(' Settings Changed Successfully', 'woo-delivery'); ?></p>
						<p class="coderockz-woo-delivery-timezone-tab-warning"><span class="dashicons dashicons-megaphone"></span><?php _e(' Before All the Settings, Please Set Your Timezone First, Otherwise We Are Using the WordPress Timezone.', 'woo-delivery'); ?></p>
	                    <form action="" method="post" id ="coderockz_delivery_timezone_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

	                    	<div class="coderockz-woo-delivery-form-group" id="coderockz_delivery_time_timezone">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_timezone"><?php _e('Store Location Timezone', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Delivery date and time of all orders will set according to the selected timezone"><span class="dashicons dashicons-editor-help"></span></p>
								<select class="coderockz-woo-delivery-select-field" name="coderockz_delivery_time_timezone">
									<option value="" <?php selected($store_location_timezone,"",true); ?>><?php _e('Select Timezone', 'woo-delivery'); ?></option>
								    <optgroup label="General">
								        <option value="GMT" <?php selected($store_location_timezone,"GMT",true); ?>>GMT timezone</option>
								        <option value="UTC" <?php selected($store_location_timezone,"UTC",true); ?>>UTC timezone</option>
								    </optgroup>
								    <optgroup label="Africa">
								        <option value="Africa/Abidjan" <?php selected($store_location_timezone,"Africa/Abidjan",true); ?>>(GMT/UTC + 00:00) Abidjan</option>
								        <option value="Africa/Accra" <?php selected($store_location_timezone,"Africa/Accra",true); ?>>(GMT/UTC + 00:00) Accra</option>
								        <option value="Africa/Addis_Ababa" <?php selected($store_location_timezone,"Africa/Addis_Ababa",true); ?>>(GMT/UTC + 03:00) Addis Ababa</option>
								        <option value="Africa/Algiers" <?php selected($store_location_timezone,"Africa/Algiers",true); ?>>(GMT/UTC + 01:00) Algiers</option>
								        <option value="Africa/Asmara" <?php selected($store_location_timezone,"Africa/Asmara",true); ?>>(GMT/UTC + 03:00) Asmara</option>
								        <option value="Africa/Bamako" <?php selected($store_location_timezone,"Africa/Bamako",true); ?>>(GMT/UTC + 00:00) Bamako</option>
								        <option value="Africa/Bangui" <?php selected($store_location_timezone,"Africa/Bangui",true); ?>>(GMT/UTC + 01:00) Bangui</option>
								        <option value="Africa/Banjul" <?php selected($store_location_timezone,"Africa/Banjul",true); ?>>(GMT/UTC + 00:00) Banjul</option>
								        <option value="Africa/Bissau" <?php selected($store_location_timezone,"Africa/Bissau",true); ?>>(GMT/UTC + 00:00) Bissau</option>
								        <option value="Africa/Blantyre" <?php selected($store_location_timezone,"Africa/Blantyre",true); ?>>(GMT/UTC + 02:00) Blantyre</option>
								        <option value="Africa/Brazzaville" <?php selected($store_location_timezone,"Africa/Brazzaville",true); ?>>(GMT/UTC + 01:00) Brazzaville</option>
								        <option value="Africa/Bujumbura" <?php selected($store_location_timezone,"Africa/Bujumbura",true); ?>>(GMT/UTC + 02:00) Bujumbura</option>
								        <option value="Africa/Cairo" <?php selected($store_location_timezone,"Africa/Cairo",true); ?>>(GMT/UTC + 02:00) Cairo</option>
								        <option value="Africa/Casablanca" <?php selected($store_location_timezone,"Africa/Casablanca",true); ?>>(GMT/UTC + 00:00) Casablanca</option>
								        <option value="Africa/Ceuta" <?php selected($store_location_timezone,"Africa/Ceuta",true); ?>>(GMT/UTC + 01:00) Ceuta</option>
								        <option value="Africa/Conakry" <?php selected($store_location_timezone,"Africa/Conakry",true); ?>>(GMT/UTC + 00:00) Conakry</option>
								        <option value="Africa/Dakar" <?php selected($store_location_timezone,"Africa/Dakar",true); ?>>(GMT/UTC + 00:00) Dakar</option>
								        <option value="Africa/Dar_es_Salaam" <?php selected($store_location_timezone,"Africa/Dar_es_Salaam",true); ?>>(GMT/UTC + 03:00) Dar es Salaam</option>
								        <option value="Africa/Djibouti" <?php selected($store_location_timezone,"Africa/Djibouti",true); ?>>(GMT/UTC + 03:00) Djibouti</option>
								        <option value="Africa/Douala" <?php selected($store_location_timezone,"Africa/Douala",true); ?>>(GMT/UTC + 01:00) Douala</option>
								        <option value="Africa/El_Aaiun" <?php selected($store_location_timezone,"Africa/El_Aaiun",true); ?>>(GMT/UTC + 00:00) El Aaiun</option>
								        <option value="Africa/Freetown" <?php selected($store_location_timezone,"Africa/Freetown",true); ?>>(GMT/UTC + 00:00) Freetown</option>
								        <option value="Africa/Gaborone" <?php selected($store_location_timezone,"Africa/Gaborone",true); ?>>(GMT/UTC + 02:00) Gaborone</option>
								        <option value="Africa/Harare" <?php selected($store_location_timezone,"Africa/Harare",true); ?>>(GMT/UTC + 02:00) Harare</option>
								        <option value="Africa/Johannesburg" <?php selected($store_location_timezone,"Africa/Johannesburg",true); ?>>(GMT/UTC + 02:00) Johannesburg</option>
								        <option value="Africa/Juba" <?php selected($store_location_timezone,"Africa/Juba",true); ?>>(GMT/UTC + 03:00) Juba</option>
								        <option value="Africa/Kampala" <?php selected($store_location_timezone,"Africa/Kampala",true); ?>>(GMT/UTC + 03:00) Kampala</option>
								        <option value="Africa/Khartoum" <?php selected($store_location_timezone,"Africa/Khartoum",true); ?>>(GMT/UTC + 03:00) Khartoum</option>
								        <option value="Africa/Kigali" <?php selected($store_location_timezone,"Africa/Kigali",true); ?>>(GMT/UTC + 02:00) Kigali</option>
								        <option value="Africa/Kinshasa" <?php selected($store_location_timezone,"Africa/Kinshasa",true); ?>>(GMT/UTC + 01:00) Kinshasa</option>
								        <option value="Africa/Lagos" <?php selected($store_location_timezone,"Africa/Lagos",true); ?>>(GMT/UTC + 01:00) Lagos</option>
								        <option value="Africa/Libreville" <?php selected($store_location_timezone,"Africa/Libreville",true); ?>>(GMT/UTC + 01:00) Libreville</option>
								        <option value="Africa/Lome" <?php selected($store_location_timezone,"Africa/Lome",true); ?>>(GMT/UTC + 00:00) Lome</option>
								        <option value="Africa/Luanda" <?php selected($store_location_timezone,"Africa/Luanda",true); ?>>(GMT/UTC + 01:00) Luanda</option>
								        <option value="Africa/Lubumbashi" <?php selected($store_location_timezone,"Africa/Lubumbashi",true); ?>>(GMT/UTC + 02:00) Lubumbashi</option>
								        <option value="Africa/Lusaka" <?php selected($store_location_timezone,"Africa/Lusaka",true); ?>>(GMT/UTC + 02:00) Lusaka</option>
								        <option value="Africa/Malabo" <?php selected($store_location_timezone,"Africa/Malabo",true); ?>>(GMT/UTC + 01:00) Malabo</option>
								        <option value="Africa/Maputo" <?php selected($store_location_timezone,"Africa/Maputo",true); ?>>(GMT/UTC + 02:00) Maputo</option>
								        <option value="Africa/Maseru" <?php selected($store_location_timezone,"Africa/Maseru",true); ?>>(GMT/UTC + 02:00) Maseru</option>
								        <option value="Africa/Mbabane" <?php selected($store_location_timezone,"Africa/Mbabane",true); ?>>(GMT/UTC + 02:00) Mbabane</option>
								        <option value="Africa/Mogadishu" <?php selected($store_location_timezone,"Africa/Mogadishu",true); ?>>(GMT/UTC + 03:00) Mogadishu</option>
								        <option value="Africa/Monrovia" <?php selected($store_location_timezone,"Africa/Monrovia",true); ?>>(GMT/UTC + 00:00) Monrovia</option>
								        <option value="Africa/Nairobi" <?php selected($store_location_timezone,"Africa/Nairobi",true); ?>>(GMT/UTC + 03:00) Nairobi</option>
								        <option value="Africa/Ndjamena" <?php selected($store_location_timezone,"Africa/Ndjamena",true); ?>>(GMT/UTC + 01:00) Ndjamena</option>
								        <option value="Africa/Niamey" <?php selected($store_location_timezone,"Africa/Niamey",true); ?>>(GMT/UTC + 01:00) Niamey</option>
								        <option value="Africa/Nouakchott" <?php selected($store_location_timezone,"Africa/Nouakchott",true); ?>>(GMT/UTC + 00:00) Nouakchott</option>
								        <option value="Africa/Ouagadougou" <?php selected($store_location_timezone,"Africa/Ouagadougou",true); ?>>(GMT/UTC + 00:00) Ouagadougou</option>
								        <option value="Africa/Porto-Novo" <?php selected($store_location_timezone,"Africa/Porto-Novo",true); ?>>(GMT/UTC + 01:00) Porto-Novo</option>
								        <option value="Africa/Sao_Tome" <?php selected($store_location_timezone,"Africa/Sao_Tome",true); ?>>(GMT/UTC + 00:00) Sao Tome</option>
								        <option value="Africa/Tripoli" <?php selected($store_location_timezone,"Africa/Tripoli",true); ?>>(GMT/UTC + 02:00) Tripoli</option>
								        <option value="Africa/Tunis" <?php selected($store_location_timezone,"Africa/Tunis",true); ?>>(GMT/UTC + 01:00) Tunis</option>
								        <option value="Africa/Windhoek" <?php selected($store_location_timezone,"Africa/Windhoek",true); ?>>(GMT/UTC + 02:00) Windhoek</option>
								    </optgroup>
								    <optgroup label="America">
								        <option value="America/Adak" <?php selected($store_location_timezone,"America/Adak",true); ?>>(GMT/UTC - 10:00) Adak</option>
								        <option value="America/Anchorage" <?php selected($store_location_timezone,"America/Anchorage",true); ?>>(GMT/UTC - 09:00) Anchorage</option>
								        <option value="America/Anguilla" <?php selected($store_location_timezone,"America/Anguilla",true); ?>>(GMT/UTC - 04:00) Anguilla</option>
								        <option value="America/Antigua" <?php selected($store_location_timezone,"America/Antigua",true); ?>>(GMT/UTC - 04:00) Antigua</option>
								        <option value="America/Araguaina" <?php selected($store_location_timezone,"America/Araguaina",true); ?>>(GMT/UTC - 03:00) Araguaina</option>
								        <option value="America/Argentina/Buenos_Aires" <?php selected($store_location_timezone,"America/Argentina/Buenos_Aires",true); ?>>(GMT/UTC - 03:00) Argentina/Buenos Aires</option>
								        <option value="America/Argentina/Catamarca" <?php selected($store_location_timezone,"America/Argentina/Catamarca",true); ?>>(GMT/UTC - 03:00) Argentina/Catamarca</option>
								        <option value="America/Argentina/Cordoba" <?php selected($store_location_timezone,"America/Argentina/Cordoba",true); ?>>(GMT/UTC - 03:00) Argentina/Cordoba</option>
								        <option value="America/Argentina/Jujuy" <?php selected($store_location_timezone,"America/Argentina/Jujuy",true); ?>>(GMT/UTC - 03:00) Argentina/Jujuy</option>
								        <option value="America/Argentina/La_Rioja" <?php selected($store_location_timezone,"America/Argentina/La_Rioja",true); ?>>(GMT/UTC - 03:00) Argentina/La Rioja</option>
								        <option value="America/Argentina/Mendoza" <?php selected($store_location_timezone,"America/Argentina/Mendoza",true); ?>>(GMT/UTC - 03:00) Argentina/Mendoza</option>
								        <option value="America/Argentina/Rio_Gallegos" <?php selected($store_location_timezone,"America/Argentina/Rio_Gallegos",true); ?>>(GMT/UTC - 03:00) Argentina/Rio Gallegos</option>
								        <option value="America/Argentina/Salta" <?php selected($store_location_timezone,"America/Argentina/Salta",true); ?>>(GMT/UTC - 03:00) Argentina/Salta</option>
								        <option value="America/Argentina/San_Juan" <?php selected($store_location_timezone,"America/Argentina/San_Juan",true); ?>>(GMT/UTC - 03:00) Argentina/San Juan</option>
								        <option value="America/Argentina/San_Luis" <?php selected($store_location_timezone,"America/Argentina/San_Luis",true); ?>>(GMT/UTC - 03:00) Argentina/San Luis</option>
								        <option value="America/Argentina/Tucuman" <?php selected($store_location_timezone,"America/Argentina/Tucuman",true); ?>>(GMT/UTC - 03:00) Argentina/Tucuman</option>
								        <option value="America/Argentina/Ushuaia" <?php selected($store_location_timezone,"America/Argentina/Ushuaia",true); ?>>(GMT/UTC - 03:00) Argentina/Ushuaia</option>
								        <option value="America/Aruba" <?php selected($store_location_timezone,"America/Aruba",true); ?>>(GMT/UTC - 04:00) Aruba</option>
								        <option value="America/Asuncion" <?php selected($store_location_timezone,"America/Asuncion",true); ?>>(GMT/UTC - 03:00) Asuncion</option>
								        <option value="America/Atikokan" <?php selected($store_location_timezone,"America/Atikokan",true); ?>>(GMT/UTC - 05:00) Atikokan</option>
								        <option value="America/Bahia" <?php selected($store_location_timezone,"America/Bahia",true); ?>>(GMT/UTC - 03:00) Bahia</option>
								        <option value="America/Bahia_Banderas" <?php selected($store_location_timezone,"America/Bahia_Banderas",true); ?>>(GMT/UTC - 06:00) Bahia Banderas</option>
								        <option value="America/Barbados" <?php selected($store_location_timezone,"America/Barbados",true); ?>>(GMT/UTC - 04:00) Barbados</option>
								        <option value="America/Belem" <?php selected($store_location_timezone,"America/Belem",true); ?>>(GMT/UTC - 03:00) Belem</option>
								        <option value="America/Belize" <?php selected($store_location_timezone,"America/Belize",true); ?>>(GMT/UTC - 06:00) Belize</option>
								        <option value="America/Blanc-Sablon" <?php selected($store_location_timezone,"America/Blanc-Sablon",true); ?>>(GMT/UTC - 04:00) Blanc-Sablon</option>
								        <option value="America/Boa_Vista" <?php selected($store_location_timezone,"America/Boa_Vista",true); ?>>(GMT/UTC - 04:00) Boa Vista</option>
								        <option value="America/Bogota" <?php selected($store_location_timezone,"America/Bogota",true); ?>>(GMT/UTC - 05:00) Bogota</option>
								        <option value="America/Boise" <?php selected($store_location_timezone,"America/Boise",true); ?>>(GMT/UTC - 07:00) Boise</option>
								        <option value="America/Cambridge_Bay" <?php selected($store_location_timezone,"America/Cambridge_Bay",true); ?>>(GMT/UTC - 07:00) Cambridge Bay</option>
								        <option value="America/Campo_Grande" <?php selected($store_location_timezone,"America/Campo_Grande",true); ?>>(GMT/UTC - 03:00) Campo Grande</option>
								        <option value="America/Cancun" <?php selected($store_location_timezone,"America/Cancun",true); ?>>(GMT/UTC - 05:00) Cancun</option>
								        <option value="America/Caracas" <?php selected($store_location_timezone,"America/Caracas",true); ?>>(GMT/UTC - 04:30) Caracas</option>
								        <option value="America/Cayenne" <?php selected($store_location_timezone,"America/Cayenne",true); ?>>(GMT/UTC - 03:00) Cayenne</option>
								        <option value="America/Cayman" <?php selected($store_location_timezone,"America/Cayman",true); ?>>(GMT/UTC - 05:00) Cayman</option>
								        <option value="America/Chicago" <?php selected($store_location_timezone,"America/Chicago",true); ?>>(GMT/UTC - 06:00) Chicago</option>
								        <option value="America/Chihuahua" <?php selected($store_location_timezone,"America/Chihuahua",true); ?>>(GMT/UTC - 07:00) Chihuahua</option>
								        <option value="America/Costa_Rica" <?php selected($store_location_timezone,"America/Costa_Rica",true); ?>>(GMT/UTC - 06:00) Costa Rica</option>
								        <option value="America/Creston" <?php selected($store_location_timezone,"America/Creston",true); ?>>(GMT/UTC - 07:00) Creston</option>
								        <option value="America/Cuiaba" <?php selected($store_location_timezone,"America/Cuiaba",true); ?>>(GMT/UTC - 03:00) Cuiaba</option>
								        <option value="America/Curacao" <?php selected($store_location_timezone,"America/Curacao",true); ?>>(GMT/UTC - 04:00) Curacao</option>
								        <option value="America/Danmarkshavn" <?php selected($store_location_timezone,"America/Danmarkshavn",true); ?>>(GMT/UTC + 00:00) Danmarkshavn</option>
								        <option value="America/Dawson" <?php selected($store_location_timezone,"America/Dawson",true); ?>>(GMT/UTC - 08:00) Dawson</option>
								        <option value="America/Dawson_Creek" <?php selected($store_location_timezone,"America/Dawson_Creek",true); ?>>(GMT/UTC - 07:00) Dawson Creek</option>
								        <option value="America/Denver" <?php selected($store_location_timezone,"America/Denver",true); ?>>(GMT/UTC - 07:00) Denver</option>
								        <option value="America/Detroit" <?php selected($store_location_timezone,"America/Detroit",true); ?>>(GMT/UTC - 05:00) Detroit</option>
								        <option value="America/Dominica" <?php selected($store_location_timezone,"America/Dominica",true); ?>>(GMT/UTC - 04:00) Dominica</option>
								        <option value="America/Edmonton" <?php selected($store_location_timezone,"America/Edmonton",true); ?>>(GMT/UTC - 07:00) Edmonton</option>
								        <option value="America/Eirunepe" <?php selected($store_location_timezone,"America/Eirunepe",true); ?>>(GMT/UTC - 05:00) Eirunepe</option>
								        <option value="America/El_Salvador" <?php selected($store_location_timezone,"America/El_Salvador",true); ?>>(GMT/UTC - 06:00) El Salvador</option>
								        <option value="America/Fort_Nelson" <?php selected($store_location_timezone,"America/Fort_Nelson",true); ?>>(GMT/UTC - 07:00) Fort Nelson</option>
								        <option value="America/Fortaleza" <?php selected($store_location_timezone,"America/Fortaleza",true); ?>>(GMT/UTC - 03:00) Fortaleza</option>
								        <option value="America/Glace_Bay" <?php selected($store_location_timezone,"America/Glace_Bay",true); ?>>(GMT/UTC - 04:00) Glace Bay</option>
								        <option value="America/Godthab" <?php selected($store_location_timezone,"America/Godthab",true); ?>>(GMT/UTC - 03:00) Godthab</option>
								        <option value="America/Goose_Bay" <?php selected($store_location_timezone,"America/Goose_Bay",true); ?>>(GMT/UTC - 04:00) Goose Bay</option>
								        <option value="America/Grand_Turk" <?php selected($store_location_timezone,"America/Grand_Turk",true); ?>>(GMT/UTC - 04:00) Grand Turk</option>
								        <option value="America/Grenada" <?php selected($store_location_timezone,"America/Grenada",true); ?>>(GMT/UTC - 04:00) Grenada</option>
								        <option value="America/Guadeloupe" <?php selected($store_location_timezone,"America/Guadeloupe",true); ?>>(GMT/UTC - 04:00) Guadeloupe</option>
								        <option value="America/Guatemala" <?php selected($store_location_timezone,"America/Guatemala",true); ?>>(GMT/UTC - 06:00) Guatemala</option>
								        <option value="America/Guayaquil" <?php selected($store_location_timezone,"America/Guayaquil",true); ?>>(GMT/UTC - 05:00) Guayaquil</option>
								        <option value="America/Guyana" <?php selected($store_location_timezone,"America/Guyana",true); ?>>(GMT/UTC - 04:00) Guyana</option>
								        <option value="America/Halifax" <?php selected($store_location_timezone,"America/Halifax",true); ?>>(GMT/UTC - 04:00) Halifax</option>
								        <option value="America/Havana" <?php selected($store_location_timezone,"America/Havana",true); ?>>(GMT/UTC - 05:00) Havana</option>
								        <option value="America/Hermosillo" <?php selected($store_location_timezone,"America/Hermosillo",true); ?>>(GMT/UTC - 07:00) Hermosillo</option>
								        <option value="America/Indiana/Indianapolis" <?php selected($store_location_timezone,"America/Indiana/Indianapolis",true); ?>>(GMT/UTC - 05:00) Indiana/Indianapolis</option>
								        <option value="America/Indiana/Knox" <?php selected($store_location_timezone,"America/Indiana/Knox",true); ?>>(GMT/UTC - 06:00) Indiana/Knox</option>
								        <option value="America/Indiana/Marengo" <?php selected($store_location_timezone,"America/Indiana/Marengo",true); ?>>(GMT/UTC - 05:00) Indiana/Marengo</option>
								        <option value="America/Indiana/Petersburg" <?php selected($store_location_timezone,"America/Indiana/Petersburg",true); ?>>(GMT/UTC - 05:00) Indiana/Petersburg</option>
								        <option value="America/Indiana/Tell_City" <?php selected($store_location_timezone,"America/Indiana/Tell_City",true); ?>>(GMT/UTC - 06:00) Indiana/Tell City</option>
								        <option value="America/Indiana/Vevay" <?php selected($store_location_timezone,"America/Indiana/Vevay",true); ?>>(GMT/UTC - 05:00) Indiana/Vevay</option>
								        <option value="America/Indiana/Vincennes" <?php selected($store_location_timezone,"America/Indiana/Vincennes",true); ?>>(GMT/UTC - 05:00) Indiana/Vincennes</option>
								        <option value="America/Indiana/Winamac" <?php selected($store_location_timezone,"America/Indiana/Winamac",true); ?>>(GMT/UTC - 05:00) Indiana/Winamac</option>
								        <option value="America/Inuvik" <?php selected($store_location_timezone,"America/Inuvik",true); ?>>(GMT/UTC - 07:00) Inuvik</option>
								        <option value="America/Iqaluit" <?php selected($store_location_timezone,"America/Iqaluit",true); ?>>(GMT/UTC - 05:00) Iqaluit</option>
								        <option value="America/Jamaica" <?php selected($store_location_timezone,"America/Jamaica",true); ?>>(GMT/UTC - 05:00) Jamaica</option>
								        <option value="America/Juneau" <?php selected($store_location_timezone,"America/Juneau",true); ?>>(GMT/UTC - 09:00) Juneau</option>
								        <option value="America/Kentucky/Louisville" <?php selected($store_location_timezone,"America/Kentucky/Louisville",true); ?>>(GMT/UTC - 05:00) Kentucky/Louisville</option>
								        <option value="America/Kentucky/Monticello" <?php selected($store_location_timezone,"America/Kentucky/Monticello",true); ?>>(GMT/UTC - 05:00) Kentucky/Monticello</option>
								        <option value="America/Kralendijk" <?php selected($store_location_timezone,"America/Kralendijk",true); ?>>(GMT/UTC - 04:00) Kralendijk</option>
								        <option value="America/La_Paz" <?php selected($store_location_timezone,"America/La_Paz",true); ?>>(GMT/UTC - 04:00) La Paz</option>
								        <option value="America/Lima" <?php selected($store_location_timezone,"America/Lima",true); ?>>(GMT/UTC - 05:00) Lima</option>
								        <option value="America/Los_Angeles" <?php selected($store_location_timezone,"America/Los_Angeles",true); ?>>(GMT/UTC - 08:00) Los Angeles</option>
								        <option value="America/Lower_Princes" <?php selected($store_location_timezone,"America/Lower_Princes",true); ?>>(GMT/UTC - 04:00) Lower Princes</option>
								        <option value="America/Maceio" <?php selected($store_location_timezone,"America/Maceio",true); ?>>(GMT/UTC - 03:00) Maceio</option>
								        <option value="America/Managua" <?php selected($store_location_timezone,"America/Managua",true); ?>>(GMT/UTC - 06:00) Managua</option>
								        <option value="America/Manaus" <?php selected($store_location_timezone,"America/Manaus",true); ?>>(GMT/UTC - 04:00) Manaus</option>
								        <option value="America/Marigot" <?php selected($store_location_timezone,"America/Marigot",true); ?>>(GMT/UTC - 04:00) Marigot</option>
								        <option value="America/Martinique" <?php selected($store_location_timezone,"America/Martinique",true); ?>>(GMT/UTC - 04:00) Martinique</option>
								        <option value="America/Matamoros" <?php selected($store_location_timezone,"America/Matamoros",true); ?>>(GMT/UTC - 06:00) Matamoros</option>
								        <option value="America/Mazatlan" <?php selected($store_location_timezone,"America/Mazatlan",true); ?>>(GMT/UTC - 07:00) Mazatlan</option>
								        <option value="America/Menominee" <?php selected($store_location_timezone,"America/Menominee",true); ?>>(GMT/UTC - 06:00) Menominee</option>
								        <option value="America/Merida" <?php selected($store_location_timezone,"America/Merida",true); ?>>(GMT/UTC - 06:00) Merida</option>
								        <option value="America/Metlakatla" <?php selected($store_location_timezone,"America/Metlakatla",true); ?>>(GMT/UTC - 09:00) Metlakatla</option>
								        <option value="America/Mexico_City" <?php selected($store_location_timezone,"America/Mexico_City",true); ?>>(GMT/UTC - 06:00) Mexico City</option>
								        <option value="America/Miquelon" <?php selected($store_location_timezone,"America/Miquelon",true); ?>>(GMT/UTC - 03:00) Miquelon</option>
								        <option value="America/Moncton" <?php selected($store_location_timezone,"America/Moncton",true); ?>>(GMT/UTC - 04:00) Moncton</option>
								        <option value="America/Monterrey" <?php selected($store_location_timezone,"America/Monterrey",true); ?>>(GMT/UTC - 06:00) Monterrey</option>
								        <option value="America/Montevideo" <?php selected($store_location_timezone,"America/Montevideo",true); ?>>(GMT/UTC - 03:00) Montevideo</option>
								        <option value="America/Montserrat" <?php selected($store_location_timezone,"America/Montserrat",true); ?>>(GMT/UTC - 04:00) Montserrat</option>
								        <option value="America/Nassau" <?php selected($store_location_timezone,"America/Nassau",true); ?>>(GMT/UTC - 05:00) Nassau</option>
								        <option value="America/New_York" <?php selected($store_location_timezone,"America/New_York",true); ?>>(GMT/UTC - 05:00) New York</option>
								        <option value="America/Nipigon" <?php selected($store_location_timezone,"America/Nipigon",true); ?>>(GMT/UTC - 05:00) Nipigon</option>
								        <option value="America/Nome" <?php selected($store_location_timezone,"America/Nome",true); ?>>(GMT/UTC - 09:00) Nome</option>
								        <option value="America/Noronha" <?php selected($store_location_timezone,"America/Noronha",true); ?>>(GMT/UTC - 02:00) Noronha</option>
								        <option value="America/North_Dakota/Beulah" <?php selected($store_location_timezone,"America/North_Dakota/Beulah",true); ?>>(GMT/UTC - 06:00) North Dakota/Beulah</option>
								        <option value="America/North_Dakota/Center" <?php selected($store_location_timezone,"America/North_Dakota/Center",true); ?>>(GMT/UTC - 06:00) North Dakota/Center</option>
								        <option value="America/North_Dakota/New_Salem" <?php selected($store_location_timezone,"America/North_Dakota/New_Salem",true); ?>>(GMT/UTC - 06:00) North Dakota/New Salem</option>
								        <option value="America/Ojinaga" <?php selected($store_location_timezone,"America/Ojinaga",true); ?>>(GMT/UTC - 07:00) Ojinaga</option>
								        <option value="America/Panama" <?php selected($store_location_timezone,"America/Panama",true); ?>>(GMT/UTC - 05:00) Panama</option>
								        <option value="America/Pangnirtung" <?php selected($store_location_timezone,"America/Pangnirtung",true); ?>>(GMT/UTC - 05:00) Pangnirtung</option>
								        <option value="America/Paramaribo" <?php selected($store_location_timezone,"America/Paramaribo",true); ?>>(GMT/UTC - 03:00) Paramaribo</option>
								        <option value="America/Phoenix" <?php selected($store_location_timezone,"America/Phoenix",true); ?>>(GMT/UTC - 07:00) Phoenix</option>
								        <option value="America/Port-au-Prince" <?php selected($store_location_timezone,"America/Port-au-Prince",true); ?>>(GMT/UTC - 05:00) Port-au-Prince</option>
								        <option value="America/Port_of_Spain" <?php selected($store_location_timezone,"America/Port_of_Spain",true); ?>>(GMT/UTC - 04:00) Port of Spain</option>
								        <option value="America/Porto_Velho" <?php selected($store_location_timezone,"America/Porto_Velho",true); ?>>(GMT/UTC - 04:00) Porto Velho</option>
								        <option value="America/Puerto_Rico" <?php selected($store_location_timezone,"America/Puerto_Rico",true); ?>>(GMT/UTC - 04:00) Puerto Rico</option>
								        <option value="America/Rainy_River" <?php selected($store_location_timezone,"America/Rainy_River",true); ?>>(GMT/UTC - 06:00) Rainy River</option>
								        <option value="America/Rankin_Inlet" <?php selected($store_location_timezone,"America/Rankin_Inlet",true); ?>>(GMT/UTC - 06:00) Rankin Inlet</option>
								        <option value="America/Recife" <?php selected($store_location_timezone,"America/Recife",true); ?>>(GMT/UTC - 03:00) Recife</option>
								        <option value="America/Regina" <?php selected($store_location_timezone,"America/Regina",true); ?>>(GMT/UTC - 06:00) Regina</option>
								        <option value="America/Resolute" <?php selected($store_location_timezone,"America/Resolute",true); ?>>(GMT/UTC - 06:00) Resolute</option>
								        <option value="America/Rio_Branco" <?php selected($store_location_timezone,"America/Rio_Branco",true); ?>>(GMT/UTC - 05:00) Rio Branco</option>
								        <option value="America/Santarem" <?php selected($store_location_timezone,"America/Santarem",true); ?>>(GMT/UTC - 03:00) Santarem</option>
								        <option value="America/Santiago" <?php selected($store_location_timezone,"America/Santiago",true); ?>>(GMT/UTC - 03:00) Santiago</option>
								        <option value="America/Santo_Domingo" <?php selected($store_location_timezone,"America/Santo_Domingo",true); ?>>(GMT/UTC - 04:00) Santo Domingo</option>
								        <option value="America/Sao_Paulo" <?php selected($store_location_timezone,"America/Sao_Paulo",true); ?>>(GMT/UTC - 02:00) Sao Paulo</option>
								        <option value="America/Scoresbysund" <?php selected($store_location_timezone,"America/Scoresbysund",true); ?>>(GMT/UTC - 01:00) Scoresbysund</option>
								        <option value="America/Sitka" <?php selected($store_location_timezone,"America/Sitka",true); ?>>(GMT/UTC - 09:00) Sitka</option>
								        <option value="America/St_Barthelemy" <?php selected($store_location_timezone,"America/St_Barthelemy",true); ?>>(GMT/UTC - 04:00) St. Barthelemy</option>
								        <option value="America/St_Johns" <?php selected($store_location_timezone,"America/St_Johns",true); ?>>(GMT/UTC - 03:30) St. Johns</option>
								        <option value="America/St_Kitts" <?php selected($store_location_timezone,"America/St_Kitts",true); ?>>(GMT/UTC - 04:00) St. Kitts</option>
								        <option value="America/St_Lucia" <?php selected($store_location_timezone,"America/St_Lucia",true); ?>>(GMT/UTC - 04:00) St. Lucia</option>
								        <option value="America/St_Thomas" <?php selected($store_location_timezone,"America/St_Thomas",true); ?>>(GMT/UTC - 04:00) St. Thomas</option>
								        <option value="America/St_Vincent" <?php selected($store_location_timezone,"America/St_Vincent",true); ?>>(GMT/UTC - 04:00) St. Vincent</option>
								        <option value="America/Swift_Current" <?php selected($store_location_timezone,"America/Swift_Current",true); ?>>(GMT/UTC - 06:00) Swift Current</option>
								        <option value="America/Tegucigalpa" <?php selected($store_location_timezone,"America/Tegucigalpa",true); ?>>(GMT/UTC - 06:00) Tegucigalpa</option>
								        <option value="America/Thule" <?php selected($store_location_timezone,"America/Thule",true); ?>>(GMT/UTC - 04:00) Thule</option>
								        <option value="America/Thunder_Bay" <?php selected($store_location_timezone,"America/Thunder_Bay",true); ?>>(GMT/UTC - 05:00) Thunder Bay</option>
								        <option value="America/Tijuana" <?php selected($store_location_timezone,"America/Tijuana",true); ?>>(GMT/UTC - 08:00) Tijuana</option>
								        <option value="America/Toronto" <?php selected($store_location_timezone,"America/Toronto",true); ?>>(GMT/UTC - 05:00) Toronto</option>
								        <option value="America/Tortola" <?php selected($store_location_timezone,"America/Tortola",true); ?>>(GMT/UTC - 04:00) Tortola</option>
								        <option value="America/Vancouver" <?php selected($store_location_timezone,"America/Vancouver",true); ?>>(GMT/UTC - 08:00) Vancouver</option>
								        <option value="America/Whitehorse" <?php selected($store_location_timezone,"America/Whitehorse",true); ?>>(GMT/UTC - 08:00) Whitehorse</option>
								        <option value="America/Winnipeg" <?php selected($store_location_timezone,"America/Winnipeg",true); ?>>(GMT/UTC - 06:00) Winnipeg</option>
								        <option value="America/Yakutat" <?php selected($store_location_timezone,"America/Yakutat",true); ?>>(GMT/UTC - 09:00) Yakutat</option>
								        <option value="America/Yellowknife" <?php selected($store_location_timezone,"America/Yellowknife",true); ?>>(GMT/UTC - 07:00) Yellowknife</option>
								    </optgroup>
								    <optgroup label="Antarctica">
								        <option value="Antarctica/Casey" <?php selected($store_location_timezone,"Antarctica/Casey",true); ?>>(GMT/UTC + 08:00) Casey</option>
								        <option value="Antarctica/Davis" <?php selected($store_location_timezone,"Antarctica/Davis",true); ?>>(GMT/UTC + 07:00) Davis</option>
								        <option value="Antarctica/DumontDUrville" <?php selected($store_location_timezone,"Antarctica/DumontDUrville",true); ?>>(GMT/UTC + 10:00) DumontDUrville</option>
								        <option value="Antarctica/Macquarie" <?php selected($store_location_timezone,"Antarctica/Macquarie",true); ?>>(GMT/UTC + 11:00) Macquarie</option>
								        <option value="Antarctica/Mawson" <?php selected($store_location_timezone,"Antarctica/Mawson",true); ?>>(GMT/UTC + 05:00) Mawson</option>
								        <option value="Antarctica/McMurdo" <?php selected($store_location_timezone,"Antarctica/McMurdo",true); ?>>(GMT/UTC + 13:00) McMurdo</option>
								        <option value="Antarctica/Palmer" <?php selected($store_location_timezone,"Antarctica/Palmer",true); ?>>(GMT/UTC - 03:00) Palmer</option>
								        <option value="Antarctica/Rothera" <?php selected($store_location_timezone,"Antarctica/Rothera",true); ?>>(GMT/UTC - 03:00) Rothera</option>
								        <option value="Antarctica/Syowa" <?php selected($store_location_timezone,"Antarctica/Syowa",true); ?>>(GMT/UTC + 03:00) Syowa</option>
								        <option value="Antarctica/Troll" <?php selected($store_location_timezone,"Antarctica/Troll",true); ?>>(GMT/UTC + 00:00) Troll</option>
								        <option value="Antarctica/Vostok" <?php selected($store_location_timezone,"Antarctica/Vostok",true); ?>>(GMT/UTC + 06:00) Vostok</option>
								    </optgroup>
								    <optgroup label="Arctic">
								        <option value="Arctic/Longyearbyen" <?php selected($store_location_timezone,"Arctic/Longyearbyen",true); ?>>(GMT/UTC + 01:00) Longyearbyen</option>
								    </optgroup>
								    <optgroup label="Asia">
								        <option value="Asia/Aden" <?php selected($store_location_timezone,"Asia/Aden",true); ?>>(GMT/UTC + 03:00) Aden</option>
								        <option value="Asia/Almaty" <?php selected($store_location_timezone,"Asia/Almaty",true); ?>>(GMT/UTC + 06:00) Almaty</option>
								        <option value="Asia/Amman" <?php selected($store_location_timezone,"Asia/Amman",true); ?>>(GMT/UTC + 02:00) Amman</option>
								        <option value="Asia/Anadyr" <?php selected($store_location_timezone,"Asia/Anadyr",true); ?>>(GMT/UTC + 12:00) Anadyr</option>
								        <option value="Asia/Aqtau" <?php selected($store_location_timezone,"Asia/Aqtau",true); ?>>(GMT/UTC + 05:00) Aqtau</option>
								        <option value="Asia/Aqtobe" <?php selected($store_location_timezone,"Asia/Aqtobe",true); ?>>(GMT/UTC + 05:00) Aqtobe</option>
								        <option value="Asia/Ashgabat" <?php selected($store_location_timezone,"Asia/Ashgabat",true); ?>>(GMT/UTC + 05:00) Ashgabat</option>
								        <option value="Asia/Baghdad" <?php selected($store_location_timezone,"Asia/Baghdad",true); ?>>(GMT/UTC + 03:00) Baghdad</option>
								        <option value="Asia/Bahrain" <?php selected($store_location_timezone,"Asia/Bahrain",true); ?>>(GMT/UTC + 03:00) Bahrain</option>
								        <option value="Asia/Baku" <?php selected($store_location_timezone,"Asia/Baku",true); ?>>(GMT/UTC + 04:00) Baku</option>
								        <option value="Asia/Bangkok" <?php selected($store_location_timezone,"Asia/Bangkok",true); ?>>(GMT/UTC + 07:00) Bangkok</option>
								        <option value="Asia/Barnaul" <?php selected($store_location_timezone,"Asia/Barnaul",true); ?>>(GMT/UTC + 07:00) Barnaul</option>
								        <option value="Asia/Beirut" <?php selected($store_location_timezone,"Asia/Beirut",true); ?>>(GMT/UTC + 02:00) Beirut</option>
								        <option value="Asia/Bishkek" <?php selected($store_location_timezone,"Asia/Bishkek",true); ?>>(GMT/UTC + 06:00) Bishkek</option>
								        <option value="Asia/Brunei" <?php selected($store_location_timezone,"Asia/Brunei",true); ?>>(GMT/UTC + 08:00) Brunei</option>
								        <option value="Asia/Chita" <?php selected($store_location_timezone,"Asia/Chita",true); ?>>(GMT/UTC + 09:00) Chita</option>
								        <option value="Asia/Choibalsan" <?php selected($store_location_timezone,"Asia/Choibalsan",true); ?>>(GMT/UTC + 08:00) Choibalsan</option>
								        <option value="Asia/Colombo" <?php selected($store_location_timezone,"Asia/Colombo",true); ?>>(GMT/UTC + 05:30) Colombo</option>
								        <option value="Asia/Damascus" <?php selected($store_location_timezone,"Asia/Damascus",true); ?>>(GMT/UTC + 02:00) Damascus</option>
								        <option value="Asia/Dhaka" <?php selected($store_location_timezone,"Asia/Dhaka",true); ?>>(GMT/UTC + 06:00) Dhaka</option>
								        <option value="Asia/Dili" <?php selected($store_location_timezone,"Asia/Dili",true); ?>>(GMT/UTC + 09:00) Dili</option>
								        <option value="Asia/Dubai" <?php selected($store_location_timezone,"Asia/Dubai",true); ?>>(GMT/UTC + 04:00) Dubai</option>
								        <option value="Asia/Dushanbe" <?php selected($store_location_timezone,"Asia/Dushanbe",true); ?>>(GMT/UTC + 05:00) Dushanbe</option>
								        <option value="Asia/Gaza" <?php selected($store_location_timezone,"Asia/Gaza",true); ?>>(GMT/UTC + 02:00) Gaza</option>
								        <option value="Asia/Hebron" <?php selected($store_location_timezone,"Asia/Hebron",true); ?>>(GMT/UTC + 02:00) Hebron</option>
								        <option value="Asia/Ho_Chi_Minh" <?php selected($store_location_timezone,"Asia/Ho_Chi_Minh",true); ?>>(GMT/UTC + 07:00) Ho Chi Minh</option>
								        <option value="Asia/Hong_Kong" <?php selected($store_location_timezone,"Asia/Hong_Kong",true); ?>>(GMT/UTC + 08:00) Hong Kong</option>
								        <option value="Asia/Hovd" <?php selected($store_location_timezone,"Asia/Hovd",true); ?>>(GMT/UTC + 07:00) Hovd</option>
								        <option value="Asia/Irkutsk" <?php selected($store_location_timezone,"Asia/Irkutsk",true); ?>>(GMT/UTC + 08:00) Irkutsk</option>
								        <option value="Asia/Jakarta" <?php selected($store_location_timezone,"Asia/Jakarta",true); ?>>(GMT/UTC + 07:00) Jakarta</option>
								        <option value="Asia/Jayapura" <?php selected($store_location_timezone,"Asia/Jayapura",true); ?>>(GMT/UTC + 09:00) Jayapura</option>
								        <option value="Asia/Jerusalem" <?php selected($store_location_timezone,"Asia/Jerusalem",true); ?>>(GMT/UTC + 02:00) Jerusalem</option>
								        <option value="Asia/Kabul" <?php selected($store_location_timezone,"Asia/Kabul",true); ?>>(GMT/UTC + 04:30) Kabul</option>
								        <option value="Asia/Kamchatka" <?php selected($store_location_timezone,"Asia/Kamchatka",true); ?>>(GMT/UTC + 12:00) Kamchatka</option>
								        <option value="Asia/Karachi" <?php selected($store_location_timezone,"Asia/Karachi",true); ?>>(GMT/UTC + 05:00) Karachi</option>
								        <option value="Asia/Kathmandu" <?php selected($store_location_timezone,"Asia/Kathmandu",true); ?>>(GMT/UTC + 05:45) Kathmandu</option>
								        <option value="Asia/Khandyga" <?php selected($store_location_timezone,"Asia/Khandyga",true); ?>>(GMT/UTC + 09:00) Khandyga</option>
								        <option value="Asia/Kolkata" <?php selected($store_location_timezone,"Asia/Kolkata",true); ?>>(GMT/UTC + 05:30) Kolkata</option>
								        <option value="Asia/Krasnoyarsk" <?php selected($store_location_timezone,"Asia/Krasnoyarsk",true); ?>>(GMT/UTC + 07:00) Krasnoyarsk</option>
								        <option value="Asia/Kuala_Lumpur" <?php selected($store_location_timezone,"Asia/Kuala_Lumpur",true); ?>>(GMT/UTC + 08:00) Kuala Lumpur</option>
								        <option value="Asia/Kuching" <?php selected($store_location_timezone,"Asia/Kuching",true); ?>>(GMT/UTC + 08:00) Kuching</option>
								        <option value="Asia/Kuwait" <?php selected($store_location_timezone,"Asia/Kuwait",true); ?>>(GMT/UTC + 03:00) Kuwait</option>
								        <option value="Asia/Macau" <?php selected($store_location_timezone,"Asia/Macau",true); ?>>(GMT/UTC + 08:00) Macau</option>
								        <option value="Asia/Magadan" <?php selected($store_location_timezone,"Asia/Magadan",true); ?>>(GMT/UTC + 10:00) Magadan</option>
								        <option value="Asia/Makassar" <?php selected($store_location_timezone,"Asia/Makassar",true); ?>>(GMT/UTC + 08:00) Makassar</option>
								        <option value="Asia/Manila" <?php selected($store_location_timezone,"Asia/Manila",true); ?>>(GMT/UTC + 08:00) Manila</option>
								        <option value="Asia/Muscat" <?php selected($store_location_timezone,"Asia/Muscat",true); ?>>(GMT/UTC + 04:00) Muscat</option>
								        <option value="Asia/Nicosia" <?php selected($store_location_timezone,"Asia/Nicosia",true); ?>>(GMT/UTC + 02:00) Nicosia</option>
								        <option value="Asia/Novokuznetsk" <?php selected($store_location_timezone,"Asia/Novokuznetsk",true); ?>>(GMT/UTC + 07:00) Novokuznetsk</option>
								        <option value="Asia/Novosibirsk" <?php selected($store_location_timezone,"Asia/Novosibirsk",true); ?>>(GMT/UTC + 06:00) Novosibirsk</option>
								        <option value="Asia/Omsk" <?php selected($store_location_timezone,"Asia/Omsk",true); ?>>(GMT/UTC + 06:00) Omsk</option>
								        <option value="Asia/Oral" <?php selected($store_location_timezone,"Asia/Oral",true); ?>>(GMT/UTC + 05:00) Oral</option>
								        <option value="Asia/Phnom_Penh" <?php selected($store_location_timezone,"Asia/Phnom_Penh",true); ?>>(GMT/UTC + 07:00) Phnom Penh</option>
								        <option value="Asia/Pontianak" <?php selected($store_location_timezone,"Asia/Pontianak",true); ?>>(GMT/UTC + 07:00) Pontianak</option>
								        <option value="Asia/Pyongyang" <?php selected($store_location_timezone,"Asia/Pyongyang",true); ?>>(GMT/UTC + 08:30) Pyongyang</option>
								        <option value="Asia/Qatar" <?php selected($store_location_timezone,"Asia/Qatar",true); ?>>(GMT/UTC + 03:00) Qatar</option>
								        <option value="Asia/Qyzylorda" <?php selected($store_location_timezone,"Asia/Qyzylorda",true); ?>>(GMT/UTC + 06:00) Qyzylorda</option>
								        <option value="Asia/Rangoon" <?php selected($store_location_timezone,"Asia/Rangoon",true); ?>>(GMT/UTC + 06:30) Rangoon</option>
								        <option value="Asia/Riyadh" <?php selected($store_location_timezone,"Asia/Riyadh",true); ?>>(GMT/UTC + 03:00) Riyadh</option>
								        <option value="Asia/Sakhalin" <?php selected($store_location_timezone,"Asia/Sakhalin",true); ?>>(GMT/UTC + 11:00) Sakhalin</option>
								        <option value="Asia/Samarkand" <?php selected($store_location_timezone,"Asia/Samarkand",true); ?>>(GMT/UTC + 05:00) Samarkand</option>
								        <option value="Asia/Seoul" <?php selected($store_location_timezone,"Asia/Seoul",true); ?>>(GMT/UTC + 09:00) Seoul</option>
								        <option value="Asia/Shanghai" <?php selected($store_location_timezone,"Asia/Shanghai",true); ?>>(GMT/UTC + 08:00) Shanghai</option>
								        <option value="Asia/Singapore" <?php selected($store_location_timezone,"Asia/Singapore",true); ?>>(GMT/UTC + 08:00) Singapore</option>
								        <option value="Asia/Srednekolymsk" <?php selected($store_location_timezone,"Asia/Srednekolymsk",true); ?>>(GMT/UTC + 11:00) Srednekolymsk</option>
								        <option value="Asia/Taipei" <?php selected($store_location_timezone,"Asia/Taipei",true); ?>>(GMT/UTC + 08:00) Taipei</option>
								        <option value="Asia/Tashkent" <?php selected($store_location_timezone,"Asia/Tashkent",true); ?>>(GMT/UTC + 05:00) Tashkent</option>
								        <option value="Asia/Tbilisi" <?php selected($store_location_timezone,"Asia/Tbilisi",true); ?>>(GMT/UTC + 04:00) Tbilisi</option>
								        <option value="Asia/Tehran" <?php selected($store_location_timezone,"Asia/Tehran",true); ?>>(GMT/UTC + 03:30) Tehran</option>
								        <option value="Asia/Thimphu" <?php selected($store_location_timezone,"Asia/Thimphu",true); ?>>(GMT/UTC + 06:00) Thimphu</option>
								        <option value="Asia/Tokyo" <?php selected($store_location_timezone,"Asia/Tokyo",true); ?>>(GMT/UTC + 09:00) Tokyo</option>
								        <option value="Asia/Ulaanbaatar" <?php selected($store_location_timezone,"Asia/Ulaanbaatar",true); ?>>(GMT/UTC + 08:00) Ulaanbaatar</option>
								        <option value="Asia/Urumqi" <?php selected($store_location_timezone,"Asia/Urumqi",true); ?>>(GMT/UTC + 06:00) Urumqi</option>
								        <option value="Asia/Ust-Nera" <?php selected($store_location_timezone,"Asia/Ust-Nera",true); ?>>(GMT/UTC + 10:00) Ust-Nera</option>
								        <option value="Asia/Vientiane" <?php selected($store_location_timezone,"Asia/Vientiane",true); ?>>(GMT/UTC + 07:00) Vientiane</option>
								        <option value="Asia/Vladivostok" <?php selected($store_location_timezone,"Asia/Vladivostok",true); ?>>(GMT/UTC + 10:00) Vladivostok</option>
								        <option value="Asia/Yakutsk" <?php selected($store_location_timezone,"Asia/Yakutsk",true); ?>>(GMT/UTC + 09:00) Yakutsk</option>
								        <option value="Asia/Yekaterinburg" <?php selected($store_location_timezone,"Asia/Yekaterinburg",true); ?>>(GMT/UTC + 05:00) Yekaterinburg</option>
								        <option value="Asia/Yerevan" <?php selected($store_location_timezone,"Asia/Yerevan",true); ?>>(GMT/UTC + 04:00) Yerevan</option>
								    </optgroup>
								    <optgroup label="Atlantic">
								        <option value="Atlantic/Azores" <?php selected($store_location_timezone,"Atlantic/Azores",true); ?>>(GMT/UTC - 01:00) Azores</option>
								        <option value="Atlantic/Bermuda" <?php selected($store_location_timezone,"Atlantic/Bermuda",true); ?>>(GMT/UTC - 04:00) Bermuda</option>
								        <option value="Atlantic/Canary" <?php selected($store_location_timezone,"Atlantic/Canary",true); ?>>(GMT/UTC + 00:00) Canary</option>
								        <option value="Atlantic/Cape_Verde" <?php selected($store_location_timezone,"Atlantic/Cape_Verde",true); ?>>(GMT/UTC - 01:00) Cape Verde</option>
								        <option value="Atlantic/Faroe" <?php selected($store_location_timezone,"Atlantic/Faroe",true); ?>>(GMT/UTC + 00:00) Faroe</option>
								        <option value="Atlantic/Madeira" <?php selected($store_location_timezone,"Atlantic/Madeira",true); ?>>(GMT/UTC + 00:00) Madeira</option>
								        <option value="Atlantic/Reykjavik" <?php selected($store_location_timezone,"Atlantic/Reykjavik",true); ?>>(GMT/UTC + 00:00) Reykjavik</option>
								        <option value="Atlantic/South_Georgia" <?php selected($store_location_timezone,"Atlantic/South_Georgia",true); ?>>(GMT/UTC - 02:00) South Georgia</option>
								        <option value="Atlantic/St_Helena" <?php selected($store_location_timezone,"Atlantic/St_Helena",true); ?>>(GMT/UTC + 00:00) St. Helena</option>
								        <option value="Atlantic/Stanley" <?php selected($store_location_timezone,"Atlantic/Stanley",true); ?>>(GMT/UTC - 03:00) Stanley</option>
								    </optgroup>
								    <optgroup label="Australia">
								        <option value="Australia/Adelaide" <?php selected($store_location_timezone,"Australia/Adelaide",true); ?>>(GMT/UTC + 10:30) Adelaide</option>
								        <option value="Australia/Brisbane" <?php selected($store_location_timezone,"Australia/Brisbane",true); ?>>(GMT/UTC + 10:00) Brisbane</option>
								        <option value="Australia/Broken_Hill" <?php selected($store_location_timezone,"Australia/Broken_Hill",true); ?>>(GMT/UTC + 10:30) Broken Hill</option>
								        <option value="Australia/Currie" <?php selected($store_location_timezone,"Australia/Currie",true); ?>>(GMT/UTC + 11:00) Currie</option>
								        <option value="Australia/Darwin" <?php selected($store_location_timezone,"Australia/Darwin",true); ?>>(GMT/UTC + 09:30) Darwin</option>
								        <option value="Australia/Eucla" <?php selected($store_location_timezone,"Australia/Eucla",true); ?>>(GMT/UTC + 08:45) Eucla</option>
								        <option value="Australia/Hobart" <?php selected($store_location_timezone,"Australia/Hobart",true); ?>>(GMT/UTC + 11:00) Hobart</option>
								        <option value="Australia/Lindeman" <?php selected($store_location_timezone,"Australia/Lindeman",true); ?>>(GMT/UTC + 10:00) Lindeman</option>
								        <option value="Australia/Lord_Howe" <?php selected($store_location_timezone,"Australia/Lord_Howe",true); ?>>(GMT/UTC + 11:00) Lord Howe</option>
								        <option value="Australia/Melbourne" <?php selected($store_location_timezone,"Australia/Melbourne",true); ?>>(GMT/UTC + 11:00) Melbourne</option>
								        <option value="Australia/Perth" <?php selected($store_location_timezone,"Australia/Perth",true); ?>>(GMT/UTC + 08:00) Perth</option>
								        <option value="Australia/Sydney" <?php selected($store_location_timezone,"Australia/Sydney",true); ?>>(GMT/UTC + 11:00) Sydney</option>
								    </optgroup>
								    <optgroup label="Europe">
								        <option value="Europe/Amsterdam" <?php selected($store_location_timezone,"Europe/Amsterdam",true); ?>>(GMT/UTC + 01:00) Amsterdam</option>
								        <option value="Europe/Andorra" <?php selected($store_location_timezone,"Europe/Andorra",true); ?>>(GMT/UTC + 01:00) Andorra</option>
								        <option value="Europe/Astrakhan" <?php selected($store_location_timezone,"Europe/Astrakhan",true); ?>>(GMT/UTC + 04:00) Astrakhan</option>
								        <option value="Europe/Athens" <?php selected($store_location_timezone,"Europe/Athens",true); ?>>(GMT/UTC + 02:00) Athens</option>
								        <option value="Europe/Belgrade" <?php selected($store_location_timezone,"Europe/Belgrade",true); ?>>(GMT/UTC + 01:00) Belgrade</option>
								        <option value="Europe/Berlin" <?php selected($store_location_timezone,"Europe/Berlin",true); ?>>(GMT/UTC + 01:00) Berlin</option>
								        <option value="Europe/Bratislava" <?php selected($store_location_timezone,"Europe/Bratislava",true); ?>>(GMT/UTC + 01:00) Bratislava</option>
								        <option value="Europe/Brussels" <?php selected($store_location_timezone,"Europe/Brussels",true); ?>>(GMT/UTC + 01:00) Brussels</option>
								        <option value="Europe/Bucharest" <?php selected($store_location_timezone,"Europe/Bucharest",true); ?>>(GMT/UTC + 02:00) Bucharest</option>
								        <option value="Europe/Budapest" <?php selected($store_location_timezone,"Europe/Budapest",true); ?>>(GMT/UTC + 01:00) Budapest</option>
								        <option value="Europe/Busingen" <?php selected($store_location_timezone,"Europe/Busingen",true); ?>>(GMT/UTC + 01:00) Busingen</option>
								        <option value="Europe/Chisinau" <?php selected($store_location_timezone,"Europe/Chisinau",true); ?>>(GMT/UTC + 02:00) Chisinau</option>
								        <option value="Europe/Copenhagen" <?php selected($store_location_timezone,"Europe/Copenhagen",true); ?>>(GMT/UTC + 01:00) Copenhagen</option>
								        <option value="Europe/Dublin" <?php selected($store_location_timezone,"Europe/Dublin",true); ?>>(GMT/UTC + 00:00) Dublin</option>
								        <option value="Europe/Gibraltar" <?php selected($store_location_timezone,"Europe/Gibraltar",true); ?>>(GMT/UTC + 01:00) Gibraltar</option>
								        <option value="Europe/Guernsey" <?php selected($store_location_timezone,"Europe/Guernsey",true); ?>>(GMT/UTC + 00:00) Guernsey</option>
								        <option value="Europe/Helsinki" <?php selected($store_location_timezone,"Europe/Helsinki",true); ?>>(GMT/UTC + 02:00) Helsinki</option>
								        <option value="Europe/Isle_of_Man" <?php selected($store_location_timezone,"Europe/Isle_of_Man",true); ?>>(GMT/UTC + 00:00) Isle of Man</option>
								        <option value="Europe/Istanbul" <?php selected($store_location_timezone,"Europe/Istanbul",true); ?>>(GMT/UTC + 02:00) Istanbul</option>
								        <option value="Europe/Jersey" <?php selected($store_location_timezone,"Europe/Jersey",true); ?>>(GMT/UTC + 00:00) Jersey</option>
								        <option value="Europe/Kaliningrad" <?php selected($store_location_timezone,"Europe/Kaliningrad",true); ?>>(GMT/UTC + 02:00) Kaliningrad</option>
								        <option value="Europe/Kiev" <?php selected($store_location_timezone,"Europe/Kiev",true); ?>>(GMT/UTC + 02:00) Kiev</option>
								        <option value="Europe/Lisbon" <?php selected($store_location_timezone,"Europe/Lisbon",true); ?>>(GMT/UTC + 00:00) Lisbon</option>
								        <option value="Europe/Ljubljana" <?php selected($store_location_timezone,"Europe/Ljubljana",true); ?>>(GMT/UTC + 01:00) Ljubljana</option>
								        <option value="Europe/London" <?php selected($store_location_timezone,"Europe/London",true); ?>>(GMT/UTC + 01:00) London</option>
								        <option value="Europe/Luxembourg" <?php selected($store_location_timezone,"Europe/Luxembourg",true); ?>>(GMT/UTC + 01:00) Luxembourg</option>
								        <option value="Europe/Madrid" <?php selected($store_location_timezone,"Europe/Madrid",true); ?>>(GMT/UTC + 01:00) Madrid</option>
								        <option value="Europe/Malta" <?php selected($store_location_timezone,"Europe/Malta",true); ?>>(GMT/UTC + 01:00) Malta</option>
								        <option value="Europe/Mariehamn" <?php selected($store_location_timezone,"Europe/Mariehamn",true); ?>>(GMT/UTC + 02:00) Mariehamn</option>
								        <option value="Europe/Minsk" <?php selected($store_location_timezone,"Europe/Minsk",true); ?>>(GMT/UTC + 03:00) Minsk</option>
								        <option value="Europe/Monaco" <?php selected($store_location_timezone,"Europe/Monaco",true); ?>>(GMT/UTC + 01:00) Monaco</option>
								        <option value="Europe/Moscow" <?php selected($store_location_timezone,"Europe/Moscow",true); ?>>(GMT/UTC + 03:00) Moscow</option>
								        <option value="Europe/Oslo" <?php selected($store_location_timezone,"Europe/Oslo",true); ?>>(GMT/UTC + 01:00) Oslo</option>
								        <option value="Europe/Paris" <?php selected($store_location_timezone,"Europe/Paris",true); ?>>(GMT/UTC + 01:00) Paris</option>
								        <option value="Europe/Podgorica" <?php selected($store_location_timezone,"Europe/Podgorica",true); ?>>(GMT/UTC + 01:00) Podgorica</option>
								        <option value="Europe/Prague" <?php selected($store_location_timezone,"Europe/Prague",true); ?>>(GMT/UTC + 01:00) Prague</option>
								        <option value="Europe/Riga" <?php selected($store_location_timezone,"Europe/Riga",true); ?>>(GMT/UTC + 02:00) Riga</option>
								        <option value="Europe/Rome" <?php selected($store_location_timezone,"Europe/Rome",true); ?>>(GMT/UTC + 01:00) Rome</option>
								        <option value="Europe/Samara" <?php selected($store_location_timezone,"Europe/Samara",true); ?>>(GMT/UTC + 04:00) Samara</option>
								        <option value="Europe/San_Marino" <?php selected($store_location_timezone,"Europe/San_Marino",true); ?>>(GMT/UTC + 01:00) San Marino</option>
								        <option value="Europe/Sarajevo" <?php selected($store_location_timezone,"Europe/Sarajevo",true); ?>>(GMT/UTC + 01:00) Sarajevo</option>
								        <option value="Europe/Simferopol" <?php selected($store_location_timezone,"Europe/Simferopol",true); ?>>(GMT/UTC + 03:00) Simferopol</option>
								        <option value="Europe/Skopje" <?php selected($store_location_timezone,"Europe/Skopje",true); ?>>(GMT/UTC + 01:00) Skopje</option>
								        <option value="Europe/Sofia" <?php selected($store_location_timezone,"Europe/Sofia",true); ?>>(GMT/UTC + 02:00) Sofia</option>
								        <option value="Europe/Stockholm" <?php selected($store_location_timezone,"Europe/Stockholm",true); ?>>(GMT/UTC + 01:00) Stockholm</option>
								        <option value="Europe/Tallinn" <?php selected($store_location_timezone,"Europe/Tallinn",true); ?>>(GMT/UTC + 02:00) Tallinn</option>
								        <option value="Europe/Tirane" <?php selected($store_location_timezone,"Europe/Tirane",true); ?>>(GMT/UTC + 01:00) Tirane</option>
								        <option value="Europe/Ulyanovsk" <?php selected($store_location_timezone,"Europe/Ulyanovsk",true); ?>>(GMT/UTC + 04:00) Ulyanovsk</option>
								        <option value="Europe/Uzhgorod" <?php selected($store_location_timezone,"Europe/Uzhgorod",true); ?>>(GMT/UTC + 02:00) Uzhgorod</option>
								        <option value="Europe/Vaduz" <?php selected($store_location_timezone,"Europe/Vaduz",true); ?>>(GMT/UTC + 01:00) Vaduz</option>
								        <option value="Europe/Vatican" <?php selected($store_location_timezone,"Europe/Vatican",true); ?>>(GMT/UTC + 01:00) Vatican</option>
								        <option value="Europe/Vienna" <?php selected($store_location_timezone,"Europe/Vienna",true); ?>>(GMT/UTC + 01:00) Vienna</option>
								        <option value="Europe/Vilnius" <?php selected($store_location_timezone,"Europe/Vilnius",true); ?>>(GMT/UTC + 02:00) Vilnius</option>
								        <option value="Europe/Volgograd" <?php selected($store_location_timezone,"Europe/Volgograd",true); ?>>(GMT/UTC + 03:00) Volgograd</option>
								        <option value="Europe/Warsaw" <?php selected($store_location_timezone,"Europe/Warsaw",true); ?>>(GMT/UTC + 01:00) Warsaw</option>
								        <option value="Europe/Zagreb" <?php selected($store_location_timezone,"Europe/Zagreb",true); ?>>(GMT/UTC + 01:00) Zagreb</option>
								        <option value="Europe/Zaporozhye" <?php selected($store_location_timezone,"Europe/Zaporozhye",true); ?>>(GMT/UTC + 02:00) Zaporozhye</option>
								        <option value="Europe/Zurich" <?php selected($store_location_timezone,"Europe/Zurich",true); ?>>(GMT/UTC + 01:00) Zurich</option>
								    </optgroup>
								    <optgroup label="Indian">
								        <option value="Indian/Antananarivo" <?php selected($store_location_timezone,"Indian/Antananarivo",true); ?>>(GMT/UTC + 03:00) Antananarivo</option>
								        <option value="Indian/Chagos" <?php selected($store_location_timezone,"Indian/Chagos",true); ?>>(GMT/UTC + 06:00) Chagos</option>
								        <option value="Indian/Christmas" <?php selected($store_location_timezone,"Indian/Christmas",true); ?>>(GMT/UTC + 07:00) Christmas</option>
								        <option value="Indian/Cocos" <?php selected($store_location_timezone,"Indian/Cocos",true); ?>>(GMT/UTC + 06:30) Cocos</option>
								        <option value="Indian/Comoro" <?php selected($store_location_timezone,"Indian/Comoro",true); ?>>(GMT/UTC + 03:00) Comoro</option>
								        <option value="Indian/Kerguelen" <?php selected($store_location_timezone,"Indian/Kerguelen",true); ?>>(GMT/UTC + 05:00) Kerguelen</option>
								        <option value="Indian/Mahe" <?php selected($store_location_timezone,"Indian/Mahe",true); ?>>(GMT/UTC + 04:00) Mahe</option>
								        <option value="Indian/Maldives" <?php selected($store_location_timezone,"Indian/Maldives",true); ?>>(GMT/UTC + 05:00) Maldives</option>
								        <option value="Indian/Mauritius" <?php selected($store_location_timezone,"Indian/Mauritius",true); ?>>(GMT/UTC + 04:00) Mauritius</option>
								        <option value="Indian/Mayotte" <?php selected($store_location_timezone,"Indian/Mayotte",true); ?>>(GMT/UTC + 03:00) Mayotte</option>
								        <option value="Indian/Reunion" <?php selected($store_location_timezone,"Indian/Reunion",true); ?>>(GMT/UTC + 04:00) Reunion</option>
								    </optgroup>
								    <optgroup label="Pacific">
								        <option value="Pacific/Apia" <?php selected($store_location_timezone,"Pacific/Apia",true); ?>>(GMT/UTC + 14:00) Apia</option>
								        <option value="Pacific/Auckland" <?php selected($store_location_timezone,"Pacific/Auckland",true); ?>>(GMT/UTC + 13:00) Auckland</option>
								        <option value="Pacific/Bougainville" <?php selected($store_location_timezone,"Pacific/Bougainville",true); ?>>(GMT/UTC + 11:00) Bougainville</option>
								        <option value="Pacific/Chatham" <?php selected($store_location_timezone,"Pacific/Chatham",true); ?>>(GMT/UTC + 13:45) Chatham</option>
								        <option value="Pacific/Chuuk" <?php selected($store_location_timezone,"Pacific/Chuuk",true); ?>>(GMT/UTC + 10:00) Chuuk</option>
								        <option value="Pacific/Easter" <?php selected($store_location_timezone,"Pacific/Easter",true); ?>>(GMT/UTC - 05:00) Easter</option>
								        <option value="Pacific/Efate" <?php selected($store_location_timezone,"Pacific/Efate",true); ?>>(GMT/UTC + 11:00) Efate</option>
								        <option value="Pacific/Enderbury" <?php selected($store_location_timezone,"Pacific/Enderbury",true); ?>>(GMT/UTC + 13:00) Enderbury</option>
								        <option value="Pacific/Fakaofo" <?php selected($store_location_timezone,"Pacific/Fakaofo",true); ?>>(GMT/UTC + 13:00) Fakaofo</option>
								        <option value="Pacific/Fiji" <?php selected($store_location_timezone,"Pacific/Fiji",true); ?>>(GMT/UTC + 12:00) Fiji</option>
								        <option value="Pacific/Funafuti" <?php selected($store_location_timezone,"Pacific/Funafuti",true); ?>>(GMT/UTC + 12:00) Funafuti</option>
								        <option value="Pacific/Galapagos" <?php selected($store_location_timezone,"Pacific/Galapagos",true); ?>>(GMT/UTC - 06:00) Galapagos</option>
								        <option value="Pacific/Gambier" <?php selected($store_location_timezone,"Pacific/Gambier",true); ?>>(GMT/UTC - 09:00) Gambier</option>
								        <option value="Pacific/Guadalcanal" <?php selected($store_location_timezone,"Pacific/Guadalcanal",true); ?>>(GMT/UTC + 11:00) Guadalcanal</option>
								        <option value="Pacific/Guam" <?php selected($store_location_timezone,"Pacific/Guam",true); ?>>(GMT/UTC + 10:00) Guam</option>
								        <option value="Pacific/Honolulu" <?php selected($store_location_timezone,"Pacific/Honolulu",true); ?>>(GMT/UTC - 10:00) Honolulu</option>
								        <option value="Pacific/Johnston" <?php selected($store_location_timezone,"Pacific/Johnston",true); ?>>(GMT/UTC - 10:00) Johnston</option>
								        <option value="Pacific/Kiritimati" <?php selected($store_location_timezone,"Pacific/Kiritimati",true); ?>>(GMT/UTC + 14:00) Kiritimati</option>
								        <option value="Pacific/Kosrae" <?php selected($store_location_timezone,"Pacific/Kosrae",true); ?>>(GMT/UTC + 11:00) Kosrae</option>
								        <option value="Pacific/Kwajalein" <?php selected($store_location_timezone,"Pacific/Kwajalein",true); ?>>(GMT/UTC + 12:00) Kwajalein</option>
								        <option value="Pacific/Majuro" <?php selected($store_location_timezone,"Pacific/Majuro",true); ?>>(GMT/UTC + 12:00) Majuro</option>
								        <option value="Pacific/Marquesas" <?php selected($store_location_timezone,"Pacific/Marquesas",true); ?>>(GMT/UTC - 09:30) Marquesas</option>
								        <option value="Pacific/Midway" <?php selected($store_location_timezone,"Pacific/Midway",true); ?>>(GMT/UTC - 11:00) Midway</option>
								        <option value="Pacific/Nauru" <?php selected($store_location_timezone,"Pacific/Nauru",true); ?>>(GMT/UTC + 12:00) Nauru</option>
								        <option value="Pacific/Niue" <?php selected($store_location_timezone,"Pacific/Niue",true); ?>>(GMT/UTC - 11:00) Niue</option>
								        <option value="Pacific/Norfolk" <?php selected($store_location_timezone,"Pacific/Norfolk",true); ?>>(GMT/UTC + 11:00) Norfolk</option>
								        <option value="Pacific/Noumea" <?php selected($store_location_timezone,"Pacific/Noumea",true); ?>>(GMT/UTC + 11:00) Noumea</option>
								        <option value="Pacific/Pago_Pago" <?php selected($store_location_timezone,"Pacific/Pago_Pago",true); ?>>(GMT/UTC - 11:00) Pago Pago</option>
								        <option value="Pacific/Palau" <?php selected($store_location_timezone,"Pacific/Palau",true); ?>>(GMT/UTC + 09:00) Palau</option>
								        <option value="Pacific/Pitcairn" <?php selected($store_location_timezone,"Pacific/Pitcairn",true); ?>>(GMT/UTC - 08:00) Pitcairn</option>
								        <option value="Pacific/Pohnpei" <?php selected($store_location_timezone,"Pacific/Pohnpei",true); ?>>(GMT/UTC + 11:00) Pohnpei</option>
								        <option value="Pacific/Port_Moresby" <?php selected($store_location_timezone,"Pacific/Port_Moresby",true); ?>>(GMT/UTC + 10:00) Port Moresby</option>
								        <option value="Pacific/Rarotonga" <?php selected($store_location_timezone,"Pacific/Rarotonga",true); ?>>(GMT/UTC - 10:00) Rarotonga</option>
								        <option value="Pacific/Saipan" <?php selected($store_location_timezone,"Pacific/Saipan",true); ?>>(GMT/UTC + 10:00) Saipan</option>
								        <option value="Pacific/Tahiti" <?php selected($store_location_timezone,"Pacific/Tahiti",true); ?>>(GMT/UTC - 10:00) Tahiti</option>
								        <option value="Pacific/Tarawa" <?php selected($store_location_timezone,"Pacific/Tarawa",true); ?>>(GMT/UTC + 12:00) Tarawa</option>
								        <option value="Pacific/Tongatapu" <?php selected($store_location_timezone,"Pacific/Tongatapu",true); ?>>(GMT/UTC + 13:00) Tongatapu</option>
								        <option value="Pacific/Wake" <?php selected($store_location_timezone,"Pacific/Wake",true); ?>>(GMT/UTC + 12:00) Wake</option>
								        <option value="Pacific/Wallis" <?php selected($store_location_timezone,"Pacific/Wallis",true); ?>>(GMT/UTC + 12:00) Wallis</option>
								    </optgroup>
								</select>
	                    	</div>

	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_delivery_timezone_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>
			</div>

			<div data-tab="tab2" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('Order Type Settings', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-delivery-option-notice"><span class="dashicons dashicons-yes"></span><?php _e(' Settings Changed Successfully', 'woo-delivery'); ?></p>
	                    <form action="" method="post" id ="coderockz_delivery_delivery_option_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

	                        <div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label" style="width:426px!important"><?php _e('Give Option to choose from Delivery or Pickup', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable it if you want to give the freedom to customer whether he wants Home delivery or he picks the ordered products from a pickup location. Default is disable."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_enable_option_time_pickup">
							       <input type="checkbox" name="coderockz_enable_option_time_pickup" id="coderockz_enable_option_time_pickup" <?php echo (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_delivery_option_label"><?php _e('Order Type Field Label', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Order Type field label. Default is Order Type."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_delivery_option_label" name="coderockz_woo_delivery_delivery_option_label" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset($delivery_option_settings['delivery_option_label']) && !empty($delivery_option_settings['delivery_option_label'])) ? stripslashes($delivery_option_settings['delivery_option_label']) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_option_delivery_label"><?php _e('Delivery Option Label', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Order Type's Home Delivery option label. Default is Delivery."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_option_delivery_label" name="coderockz_woo_delivery_option_delivery_label" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_option_pickup_label"><?php _e('Self Pickup Option Label', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Order Type's Self Pickup option label. Default is Pickup."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_option_pickup_label" name="coderockz_woo_delivery_option_pickup_label" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Dynamically Enable/Disable Delivery/Pickup Based on WooCommerce Shipping', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable it if you want to see the delivery or pickup option based on your WoCommerce Shipping. Default is disable."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_woo_delivery_enable_dynamic_order_type">
							       <input type="checkbox" name="coderockz_woo_delivery_enable_dynamic_order_type" id="coderockz_woo_delivery_enable_dynamic_order_type" class="coderockz_woo_delivery_enable_dynamic_order_type"/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Dynamically Change Shipping Method Based on Delivery/Pickup', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable it if you want to see the delivery or pickup option based on your WoCommerce Shipping. Default is disable."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_woo_delivery_enable_dynamic_order_type">
							       <input type="checkbox" name="coderockz_woo_delivery_enable_dynamic_order_type" id="coderockz_woo_delivery_enable_dynamic_order_type" class="coderockz_woo_delivery_enable_dynamic_order_type"/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>

	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_delivery_delivery_option_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>

			</div>

			<div data-tab="tab3" class="coderockz-woo-delivery-tabcontent">
				
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('General Delivery Date Settings', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-date-tab-notice"><span class="dashicons dashicons-yes"></span><?php _e(' Settings Changed Successfully', 'woo-delivery'); ?></p>
	                    <form action="" method="post" id ="coderockz_delivery_date_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Enable Delivery Date', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable Delivery Date input field in woocommerce order checkout page."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_enable_delivery_date">
							       <input type="checkbox" name="coderockz_enable_delivery_date" id="coderockz_enable_delivery_date" <?php echo (isset($date_settings['enable_delivery_date']) && !empty($date_settings['enable_delivery_date'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Make Delivery Date Field Mandatory', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Make Delivery Date input field mandatory in woocommerce order checkout page. Default is optional."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_delivery_date_mandatory">
							       <input type="checkbox" name="coderockz_delivery_date_mandatory" id="coderockz_delivery_date_mandatory" <?php echo (isset($date_settings['delivery_date_mandatory']) && !empty($date_settings['delivery_date_mandatory'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_date_field_label"><?php _e('Delivery Date Field Label', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Delivery Date input field label and placeholder. Default is Delivery Date."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_delivery_date_field_label" name="coderockz_delivery_date_field_label" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset($date_settings['field_label']) && !empty($date_settings['field_label'])) ? esc_attr($date_settings['field_label']) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_date_selectable_date"><?php _e('Allow Delivery in Next Available Days', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="User can only select the number of date from calander that is specified Here. Other dates are disabled. Only numerical value is excepted. Default is 365 days."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input onkeyup="if(!Number.isInteger(Number(this.value)) || this.value < 1) this.value = null;" id="coderockz_delivery_date_selectable_date" name="coderockz_delivery_date_selectable_date" type="number" class="coderockz-woo-delivery-number-field" value="<?php echo (isset($date_settings['selectable_date']) && !empty($date_settings['selectable_date'])) ? stripslashes(esc_attr($date_settings['selectable_date'])) : ""; ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_date_week_starts_from"><?php _e('Week Starts From', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Delivery Date's calendar will start from the day that is selected Here. Default is Sunday."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<select class="coderockz-woo-delivery-select-field" name="coderockz_delivery_date_week_starts_from">
	                    			<option value="" <?php if(isset($date_settings['week_starts_from']) && $date_settings['week_starts_from'] == ""){ echo "selected"; } ?>><?php _e('Select Day', 'woo-delivery'); ?></option>
									<option value="0" <?php if(isset($date_settings['week_starts_from']) && $date_settings['week_starts_from'] == "0"){ echo "selected"; } ?>>Sunday</option>
									<option value="1" <?php if(isset($date_settings['week_starts_from']) && $date_settings['week_starts_from'] == "1"){ echo "selected"; } ?>>Monday</option>
									<option value="2" <?php if(isset($date_settings['week_starts_from']) && $date_settings['week_starts_from'] == "2"){ echo "selected"; } ?>>Tuesday</option>
									<option value="3" <?php if(isset($date_settings['week_starts_from']) && $date_settings['week_starts_from'] == "3"){ echo "selected"; } ?>>Wednesday</option>
									<option value="4" <?php if(isset($date_settings['week_starts_from']) && $date_settings['week_starts_from'] == "4"){ echo "selected"; } ?>>Thursday</option>
									<option value="5" <?php if(isset($date_settings['week_starts_from']) && $date_settings['week_starts_from'] == "5"){ echo "selected"; } ?>>Friday</option>
									<option value="6" <?php if(isset($date_settings['week_starts_from']) && $date_settings['week_starts_from'] == "6"){ echo "selected"; } ?>>Saturday</option>
								</select>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_date_format"><?php _e('Delivery Date Format', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Date format that is used in everywhere which is available by this plugin. Default is F j, Y ( ex. March 6, 2011 )."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<select class="coderockz-woo-delivery-select-field" name="coderockz_delivery_date_format">
									<option value="F j, Y" <?php if(isset($date_settings['date_format']) && $date_settings['date_format'] == "F j, Y"){ echo "selected"; } ?>>F j, Y ( ex. March 6, 2011 )</option>
									<option value="d-m-Y" <?php if(isset($date_settings['date_format']) && $date_settings['date_format'] == "d-m-Y"){ echo "selected"; } ?>>d-m-Y ( ex. 29-03-2011 )</option>
									<option value="m/d/Y" <?php if(isset($date_settings['date_format']) && $date_settings['date_format'] == "m/d/Y"){ echo "selected"; } ?>>m/d/Y ( ex. 03/29/2011 )</option>
									<option value="d.m.Y" <?php if(isset($date_settings['date_format']) && $date_settings['date_format'] == "d.m.Y"){ echo "selected"; } ?>>d.m.Y ( ex. 29.03.2011 )</option>
								</select>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Auto Select 1st Available Date', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable the option if you want to select the first available date automatically and shown in the delivery date field. Default is disable."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_auto_select_first_date">
							       <input type="checkbox" name="coderockz_auto_select_first_date" id="coderockz_auto_select_first_date" <?php echo (isset($date_settings['auto_select_first_date']) && !empty($date_settings['auto_select_first_date'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label coderockz-woo-delivery-checkbox-label" for="coderockz_delivery_date_delivery_days"><?php _e('Delivery Days', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip coderockz-woo-delivery-checkbox-tooltip" tooltip="Delivery is only available in those days that are checked. Other dates corresponding to the unchecked days are disabled in the calendar."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<div id="coderockz_delivery_date_delivery_days" style="display:inline-block">
	                    		<input type="checkbox" name="coderockz_delivery_date_delivery_days[]" value="6" <?php echo in_array("6",$selected_delivery_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Saturday</label><br/>
								<input type="checkbox" name="coderockz_delivery_date_delivery_days[]" value="0" <?php echo in_array("0",$selected_delivery_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Sunday</label><br/>
								<input type="checkbox" name="coderockz_delivery_date_delivery_days[]" value="1" <?php echo in_array("1",$selected_delivery_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Monday</label><br/>
								<input type="checkbox" name="coderockz_delivery_date_delivery_days[]" value="2" <?php echo in_array("2",$selected_delivery_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Tuesday</label><br/>
								<input type="checkbox" name="coderockz_delivery_date_delivery_days[]" value="3" <?php echo in_array("3",$selected_delivery_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Wednesday</label><br/>
								<input type="checkbox" name="coderockz_delivery_date_delivery_days[]" value="4" <?php echo in_array("4",$selected_delivery_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Thursday</label><br/>
								<input type="checkbox" name="coderockz_delivery_date_delivery_days[]" value="5" <?php echo in_array("5",$selected_delivery_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Friday</label><br/>
								</div>
	                    	</div>

	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_delivery_date_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>

			</div>

			<div data-tab="tab4" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('General Pickup Date Settings', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-pickup-date-tab-notice"><span class="dashicons dashicons-yes"></span><?php _e(' Settings Changed Successfully', 'woo-delivery'); ?></p>
	                    <form action="" method="post" id ="coderockz_delivery_pickup_date_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Enable Pickup Date', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable Pickup Date input field in woocommerce order checkout page."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_enable_pickup_date">
							       <input type="checkbox" name="coderockz_enable_pickup_date" id="coderockz_enable_pickup_date" <?php echo (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Make Pickup Date Field Mandatory', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Make Pickup Date input field mandatory in woocommerce order checkout page. Default is optional."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_pickup_date_mandatory">
							       <input type="checkbox" name="coderockz_pickup_date_mandatory" id="coderockz_pickup_date_mandatory" <?php echo (isset($pickup_date_settings['pickup_date_mandatory']) && !empty($pickup_date_settings['pickup_date_mandatory'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_date_field_label"><?php _e('Pickup Date Field Label', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Pickup Date input field heading. Default is Pickup Date."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_pickup_date_field_label" name="coderockz_pickup_date_field_label" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes(esc_attr($pickup_date_settings['pickup_field_label'])) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_date_selectable_date"><?php _e('Allow Pickup in Next Available Days', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="User can only select the number of date from calander that is specified Here. Other dates are disabled. Only numerical value is excepted. Default is 365 days."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input onkeyup="if(!Number.isInteger(Number(this.value)) || this.value < 1) this.value = null;" id="coderockz_pickup_date_selectable_date" name="coderockz_pickup_date_selectable_date" type="number" class="coderockz-woo-delivery-number-field" value="<?php echo (isset($pickup_date_settings['selectable_date']) && !empty($pickup_date_settings['selectable_date'])) ? stripslashes(esc_attr($pickup_date_settings['selectable_date'])) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_date_week_starts_from"><?php _e('Week Starts From', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Pickup Date's calendar will start from the day that is selected Here. Default is Sunday."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<select class="coderockz-woo-delivery-select-field" name="coderockz_pickup_date_week_starts_from">
	                    			<option value="" <?php if(isset($pickup_date_settings['week_starts_from']) && $pickup_date_settings['week_starts_from'] == ""){ echo "selected"; } ?>><?php _e('Select Day', 'woo-delivery'); ?></option>
									<option value="0" <?php if(isset($pickup_date_settings['week_starts_from']) && $pickup_date_settings['week_starts_from'] == "0"){ echo "selected"; } ?>>Sunday</option>
									<option value="1" <?php if(isset($pickup_date_settings['week_starts_from']) && $pickup_date_settings['week_starts_from'] == "1"){ echo "selected"; } ?>>Monday</option>
									<option value="2" <?php if(isset($pickup_date_settings['week_starts_from']) && $pickup_date_settings['week_starts_from'] == "2"){ echo "selected"; } ?>>Tuesday</option>
									<option value="3" <?php if(isset($pickup_date_settings['week_starts_from']) && $pickup_date_settings['week_starts_from'] == "3"){ echo "selected"; } ?>>Wednesday</option>
									<option value="4" <?php if(isset($pickup_date_settings['week_starts_from']) && $pickup_date_settings['week_starts_from'] == "4"){ echo "selected"; } ?>>Thursday</option>
									<option value="5" <?php if(isset($pickup_date_settings['week_starts_from']) && $pickup_date_settings['week_starts_from'] == "5"){ echo "selected"; } ?>>Friday</option>
									<option value="6" <?php if(isset($pickup_date_settings['week_starts_from']) && $pickup_date_settings['week_starts_from'] == "6"){ echo "selected"; } ?>>Saturday</option>
								</select>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_date_format"><?php _e('Pickup Date Format', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Date format that is used in everywhere which is available by this plugin. Default is F j, Y ( ex. March 6, 2011 )."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<select class="coderockz-woo-delivery-select-field" name="coderockz_pickup_date_format">
	                    			<option value="F j, Y" <?php if(isset($pickup_date_settings['date_format']) && $pickup_date_settings['date_format'] == "F j, Y"){ echo "selected"; } ?>>F j, Y ( ex. March 6, 2011 )</option>
									<option value="d-m-Y" <?php if(isset($pickup_date_settings['date_format']) && $pickup_date_settings['date_format'] == "d-m-Y"){ echo "selected"; } ?>>d-m-Y ( ex. 29-03-2011 )</option>
									<option value="m/d/Y" <?php if(isset($pickup_date_settings['date_format']) && $pickup_date_settings['date_format'] == "m/d/Y"){ echo "selected"; } ?>>m/d/Y ( ex. 03/29/2011 )</option>
									<option value="d.m.Y" <?php if(isset($pickup_date_settings['date_format']) && $pickup_date_settings['date_format'] == "d.m.Y"){ echo "selected"; } ?>>d.m.Y ( ex. 29.03.2011 )</option>
									
								</select>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Auto Select 1st Available Date', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable the option if you want to select the first available date automatically and shown in the pickup date field. Default is disable."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_auto_select_first_pickup_date">
							       <input type="checkbox" name="coderockz_auto_select_first_pickup_date" id="coderockz_auto_select_first_pickup_date" <?php echo (isset($pickup_date_settings['auto_select_first_pickup_date']) && !empty($pickup_date_settings['auto_select_first_pickup_date'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label coderockz-woo-delivery-checkbox-label" for="coderockz_pickup_date_delivery_days"><?php _e('Pickup Days', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip coderockz-woo-delivery-checkbox-tooltip" tooltip="Pickup is only available in those days that are checked. Other dates corresponding to the unchecked days are disabled in the calendar."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<div id="coderockz_pickup_date_delivery_days" style="display:inline-block">
	                    		<input type="checkbox" name="coderockz_pickup_date_delivery_days[]" value="6" <?php echo in_array("6",$selected_pickup_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Saturday</label><br/>
								<input type="checkbox" name="coderockz_pickup_date_delivery_days[]" value="0" <?php echo in_array("0",$selected_pickup_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Sunday</label><br/>
								<input type="checkbox" name="coderockz_pickup_date_delivery_days[]" value="1" <?php echo in_array("1",$selected_pickup_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Monday</label><br/>
								<input type="checkbox" name="coderockz_pickup_date_delivery_days[]" value="2" <?php echo in_array("2",$selected_pickup_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Tuesday</label><br/>
								<input type="checkbox" name="coderockz_pickup_date_delivery_days[]" value="3" <?php echo in_array("3",$selected_pickup_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Wednesday</label><br/>
								<input type="checkbox" name="coderockz_pickup_date_delivery_days[]" value="4" <?php echo in_array("4",$selected_pickup_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Thursday</label><br/>
								<input type="checkbox" name="coderockz_pickup_date_delivery_days[]" value="5" <?php echo in_array("5",$selected_pickup_day) ? "checked" : "";?>><label class="coderockz-woo-delivery-checkbox-field-text">Friday</label><br/>
								</div>
	                    	</div>

	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_delivery_pickup_date_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>
			</div>

			<div data-tab="tab5" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('Off Days', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-date-tab-offdays-notice"></p>
						<input class="coderockz-woo-delivery-add-year-btn" type="button" value="<?php _e('Add New Year', 'woo-delivery'); ?>">
	                    <form action="" method="post" id ="coderockz_delivery_date_offdays_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>
	                        <div id="coderockz-woo-delivery-offdays" class="coderockz-woo-delivery-offdays">
							    
	                        	<?php
	                        		$month_array = ['january','february','march','april','may','june','july','august','september','october','november','december'];
									$offdays_html = "";
									$offdays_years = get_option('coderockz_woo_delivery_date_settings');
									if(isset($offdays_years['off_days']) && !empty($offdays_years['off_days'])) {
										foreach($offdays_years['off_days'] as $year=>$months) {
											
											$offdays_html .= '<div class="coderockz-woo-delivery-add-year-html coderockz-woo-delivery-form-group">';
											if(array_keys($offdays_years['off_days'])[0] == $year) {
												$offdays_html .= '<img class="coderockz-arrow" src="'. CODEROCKZ_WOO_DELIVERY_URL .'/admin/images/arrow.png" alt="" style="width: 20px;vertical-align: top;margin-top: 12px;margin-right: 15px;">';	

											} else {
												$offdays_html .= '<button class="coderockz-offdays-year-remove"><span class="dashicons dashicons-trash"></span></button>';
											}
											
											$offdays_html .= '<input style="width:125px" class="coderockz-woo-delivery-input-field coderockz_woo_delivery_offdays_year" maxlength="4" type="text" value="'.$year.'" placeholder="'.__('Year (ex. 2019)', 'woo-delivery').'" style="vertical-align:top;" autocomplete="off" name="coderockz_woo_delivery_offdays_year_'.$year.'">';
											$offdays_html .= '<div style="display:inline-block;" class="coderockz_woo_delivery_offdays_another_month coderockz_woo_delivery_offdays_another_month_'.$year.'">';
											foreach($months as $month=>$date) {
												$offdays_html .= '<div class="coderockz_woo_delivery_offdays_add_another_month">';
												$offdays_html .= '<select style="width:125px!important" class="coderockz-woo-delivery-select-field" name="coderockz_woo_delivery_offdays_month_'.$year.'[]">';
												$offdays_html .= '<option value="">'.__('Select Month', 'woo-delivery').'</option>';
												foreach($month_array as $single_month) {
													$single_month == $month ? $selected = "selected" : $selected = "";
													$offdays_html .= '<option value="'.$single_month.'"'.$selected.'>'.ucfirst($single_month).'</option>';
												}
												$offdays_html .= '</select>';
												$offdays_html .= '<input id="coderockz_woo_delivery_offdays_dates" type="text" class="coderockz-woo-delivery-input-field" value="'.$date.'" placeholder="'.__('Comma(,) Separeted Date', 'woo-delivery').'" style="width:200px;vertical-align:top;" autocomplete="off" name="coderockz_woo_delivery_offdays_dates_'.$month.'_'.$year.'">';
												if(array_keys($months)[0] != $month) {
													
													$offdays_html .= '<button class="coderockz-offdays-month-remove"><span class="dashicons dashicons-trash"></span></button>';
												}
												$offdays_html .= '</div>';
											}
											$offdays_html .= '</div>';
											$offdays_html .= '<br>
												    	  <span style="position:relative;left:35%">
														    <input class="coderockz-woo-delivery-add-month-btn" type="button" value="'.__("Add Month", "woo-delivery").'">
														    <div class="coderockz-woo-delivery-dummy-btn" style="position:absolute; left:0; right:0; top:0; bottom:0; cursor: pointer;"></div>
														  </span>';
											
											$offdays_html .= '</div>';
										}
										echo $offdays_html;
									} else {
	                        	?>

							    <div class="coderockz-woo-delivery-add-year-html coderockz-woo-delivery-form-group">
							    	<img class="coderockz-arrow" src="<?php echo CODEROCKZ_WOO_DELIVERY_URL ?>/admin/images/arrow.png" alt="" style="width: 20px;vertical-align: top;margin-top: 12px;margin-right: 15px;">
							        <input style="width:125px" class="coderockz-woo-delivery-input-field coderockz_woo_delivery_offdays_year" maxlength="4" type="text" value="<?php  ?>" placeholder="<?php _e('Year (ex. 2019)', 'woo-delivery'); ?>" style="vertical-align:top;" autocomplete="off"/>
							        <div class="coderockz_woo_delivery_offdays_another_month" style="display:inline-block;">
								        <div class="coderockz_woo_delivery_offdays_add_another_month">
									        <select style="width:125px!important" class="coderockz-woo-delivery-select-field" disabled="disabled">
									        	<option value=""><?php _e('Select Month', 'woo-delivery'); ?></option>
									        	<?php
									        	$month_array = ['january','february','march','april','may','june','july','august','september','october','november','december'];
									        	foreach($month_array as $single_month) {
													echo '<option value="'.$single_month.'">'.ucfirst($single_month).'</option>';
												}
									        	?>
									            
										    </select>
									        <input style="width:200px" id="coderockz_woo_delivery_offdays_dates" type="text" class="coderockz-woo-delivery-input-field" value="<?php  ?>" placeholder="<?php _e('Comma(,) Separeted Date', 'woo-delivery'); ?>" style="vertical-align:top;" autocomplete="off" disabled="disabled"/>
								    	</div>
							    	</div>
							    	<br/>
							    	<span style="position:relative;left:18%">
									  <input class="coderockz-woo-delivery-add-month-btn" type="button" value="<?php _e('Add Month', 'woo-delivery'); ?>" disabled="disabled">
									  <div class="coderockz-woo-delivery-dummy-btn" style="position:absolute; left:0; right:0; top:0; bottom:0; cursor: pointer;"></div>
									</span>


							    </div>
								<?php } ?>
							</div>
	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_delivery_date_offdays_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>
			</div>

			<div data-tab="tab6" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('General Delivery Time Settings', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-time-tab-notice"><span class="dashicons dashicons-yes"></span><?php _e(' Settings Changed Successfully', 'woo-delivery'); ?></p>
	                    <form action="" method="post" id ="coderockz_delivery_time_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Enable Delivery Time', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable Delivery Time select field in woocommerce order checkout page."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_enable_delivery_time">
							       <input type="checkbox" name="coderockz_enable_delivery_time" id="coderockz_enable_delivery_time" <?php echo (isset($time_settings['enable_delivery_time']) && !empty($time_settings['enable_delivery_time'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Make Delivery Time Field Mandatory', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Make Delivery Time select field mandatory in woocommerce order checkout page. Default is optional."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_delivery_time_mandatory">
							       <input type="checkbox" name="coderockz_delivery_time_mandatory" id="coderockz_delivery_time_mandatory" <?php echo (isset($time_settings['delivery_time_mandatory']) && !empty($time_settings['delivery_time_mandatory'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_field_label"><?php _e('Delivery Time Field Label', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Delivery Time select field label and placeholder. Default is Delivery Time."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_delivery_time_field_label" name="coderockz_delivery_time_field_label" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset($time_settings['field_label']) && !empty($time_settings['field_label'])) ? esc_attr($time_settings['field_label']) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<?php 
                    			$start_hour = "";
            					$start_min = "";
            					$start_format= "am";
                    			
                    			if(isset($time_settings['delivery_time_starts']) && $time_settings['delivery_time_starts'] !='') {
                    				$delivery_time_starts = (int)$time_settings['delivery_time_starts'];

                    				if($delivery_time_starts == 0) {
		            					$start_hour = "12";
		            					$start_min = "00";
		            					$start_format= "am";
		            				} elseif($delivery_time_starts > 0 && $delivery_time_starts <= 59) {

                    					$start_hour = "12";
                    					$start_min = sprintf("%02d", $delivery_time_starts);
                    					$start_format= "am";
                    				} elseif($delivery_time_starts > 59 && $delivery_time_starts <= 719) {
										$start_min = sprintf("%02d", (int)$delivery_time_starts%60);
										$start_hour = sprintf("%02d", ((int)$delivery_time_starts-$start_min)/60);
										$start_format= "am";
										
                    				} elseif($delivery_time_starts > 719 && $delivery_time_starts <= 1439) {
										$start_min = sprintf("%02d", (int)$delivery_time_starts%60);
										$start_hour = sprintf("%02d", ((int)$delivery_time_starts-$start_min)/60);
										if($start_hour>12) {
											$start_hour = sprintf("%02d", $start_hour-12);
										}
										$start_format= "pm";
                    				} elseif($delivery_time_starts == 1440) {
										$start_min = "00";
										$start_hour = "12";
										$start_format= "am";
                    				}

                    			}
                    		?>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_slot_starts"><?php _e('Time Slot Starts From', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Delivery Time starts from the time that is specified here. Only numerical value is accepted."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<div id="coderockz_delivery_time_slot_starts" class="coderockz_delivery_time_slot_starts">
	                    			
	                        	<input name="coderockz_delivery_time_slot_starts_hour" type="number" class="coderockz-woo-delivery-number-field" max="12" min="1" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value > 12 || this.value < 1) this.value = null;" value="<?php echo $start_hour; ?>" placeholder="Hour" autocomplete="off"/>
	                        	<input name="coderockz_delivery_time_slot_starts_min" type="number" class="coderockz-woo-delivery-number-field" max="59" min="0" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value > 59 || this.value < 0) this.value = null;" value="<?php echo $start_min; ?>" placeholder="Minute" autocomplete="off"/>
	                        	<select class="coderockz-woo-delivery-select-field" name="coderockz_delivery_time_slot_starts_format">
									<option value="am" <?php selected($start_format,"am",true); ?>>AM</option>
									<option value="pm" <?php selected($start_format,"pm",true); ?>>PM</option>
								</select>
	                        	</div>
	                    	</div>
	                    	<?php 
                    			$end_hour = "";
            					$end_min = "";
            					$end_format= "am";
                    			
                    			if(isset($time_settings['delivery_time_ends']) && $time_settings['delivery_time_ends'] !='') {
                    				$delivery_time_ends = (int)$time_settings['delivery_time_ends'];
                    				if($delivery_time_ends == 0) {
		            					$end_hour = "12";
		            					$end_min = "00";
		            					$end_format= "am";
		            				} elseif($delivery_time_ends > 0 && $delivery_time_ends <= 59) {
                    					$end_hour = "12";
                    					$end_min = sprintf("%02d", $delivery_time_ends);
                    					$end_format= "am";
                    				} elseif($delivery_time_ends > 59 && $delivery_time_ends <= 719) {
										$end_min = sprintf("%02d", (int)$delivery_time_ends%60);
										$end_hour = sprintf("%02d", ((int)$delivery_time_ends-$end_min)/60);
										$end_format= "am";
										
                    				} elseif($delivery_time_ends > 719 && $delivery_time_ends <= 1439) {
										$end_min = sprintf("%02d", (int)$delivery_time_ends%60);
										$end_hour = sprintf("%02d", ((int)$delivery_time_ends-$end_min)/60);
										if($end_hour>12) {
											$end_hour = sprintf("%02d", $end_hour-12);
										}
										$end_format= "pm";
                    				} elseif($delivery_time_ends == 1440) {
										$end_min = "00";
										$end_hour = "12";
										$end_format= "am";
                    				}

                    			}
                    		?>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_slot_ends"><?php _e('Time Slot Ends At', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Delivery Time ends at the time that is specified here. Only numerical value is accepted."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<div id="coderockz_delivery_time_slot_ends" class="coderockz_delivery_time_slot_ends">
	                        	<input name="coderockz_delivery_time_slot_ends_hour" type="number" class="coderockz-woo-delivery-number-field" max="12" min="1" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value > 12 || this.value < 1) this.value = null;" value="<?php echo $end_hour; ?>" placeholder="Hour" autocomplete="off"/>
	                        	<input name="coderockz_delivery_time_slot_ends_min" type="number" class="coderockz-woo-delivery-number-field" max="59" min="0" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value > 59 || this.value < 0) this.value = null;" value="<?php echo $end_min; ?>" placeholder="Minute" autocomplete="off"/>
	                        	<select class="coderockz-woo-delivery-select-field" name="coderockz_delivery_time_slot_ends_format">
									<option value="am" <?php selected($end_format,"am",true); ?>>AM</option>
									<option value="pm" <?php selected($end_format,"pm",true); ?>>PM</option>
								</select>
	                        	</div>
	                        	<p class="coderockz_end_time_greater_notice"><?php _e('End Time Must after Start Time', 'woo-delivery'); ?></p>
	                    	</div>
	                    	<?php
	                    		$duration = ""; 
	                    		$identity = "min";
	                    		$time_settings = get_option('coderockz_woo_delivery_time_settings');
                    			if(isset($time_settings['each_time_slot']) && !empty($time_settings['each_time_slot'])) {
                    				$time_slot_duration = (int)$time_settings['each_time_slot'];
                    				if($time_slot_duration <= 59) {
                    					$duration = $time_slot_duration;
                    				} else {
                    					$time_slot_duration = $time_slot_duration/60;
                    					$helper = new Coderockz_Woo_Delivery_Helper();
                    					if($helper->containsDecimal($time_slot_duration)){
                    						$duration = $time_slot_duration*60;
                    						$identity = "min";
                    					} else {
                    						$duration = $time_slot_duration;
                    						$identity = "hour";
                    					}
                    				}
                    			}
	                    	?>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_slot_duration"><?php _e('Each Time Slot Duration', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Each delivery time slot duration that is specified here. Only numerical value is accepted. Default is 3 hours."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<div id="coderockz_delivery_time_slot_duration" class="coderockz_delivery_time_slot_duration">
	                        	<input name="coderockz_delivery_time_slot_duration_time" type="number" min="1" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value < 1) this.value = null;" class="coderockz-woo-delivery-number-field" value="<?php echo $duration; ?>" placeholder="" autocomplete="off"/>
	                        	<select class="coderockz-woo-delivery-select-field" name="coderockz_delivery_time_slot_duration_format">
									<option value="min" <?php selected($identity,"min",true); ?>>Minutes</option>
									<option value="hour" <?php selected($identity,"hour",true); ?>>Hour</option>
								</select>
	                        	</div>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_maximum_order"><?php _e('Maximum Order Per Time Slot', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Each time slot take maximum number of orders that is specified here. After reaching the maximum order, the time slot is disabled automaticaly. Only numerical value is accepted. Blank this field or 0 value means each time slot takes unlimited order."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_delivery_time_maximum_order" name="coderockz_delivery_time_maximum_order" type="number" class="coderockz-woo-delivery-number-field" min="1" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value < 1) this.value = null;" value="<?php echo (isset($time_settings['max_order_per_slot']) && !empty($time_settings['max_order_per_slot'])) ? stripslashes(esc_attr($time_settings['max_order_per_slot'])) : ""; ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_format"><?php _e('Delivery Time format', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Time format that is used in everywhere which is available by this plugin. Default is 12 Hours."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<select class="coderockz-woo-delivery-select-field" name="coderockz_delivery_time_format">

	                    			<option value="" <?php if(isset($time_settings['time_format']) && $time_settings['time_format'] == ""){ echo "selected"; } ?>><?php _e('Select Time Format', 'woo-delivery'); ?></option>
									<option value="12" <?php if(isset($time_settings['time_format']) && $time_settings['time_format'] == "12"){ echo "selected"; } ?>>12 Hours</option>
									<option value="24" <?php if(isset($time_settings['time_format']) && $time_settings['time_format'] == "24"){ echo "selected"; } ?>>24 Hours</option>
								</select>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Disable Current Time Slot', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Make the time slot disabled that has the current time. In default, the time slot isn't disabled that has the current time."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_delivery_time_disable_current_time_slot">
							       <input type="checkbox" name="coderockz_delivery_time_disable_current_time_slot" id="coderockz_delivery_time_disable_current_time_slot" <?php echo (isset($time_settings['disabled_current_time_slot']) && !empty($time_settings['disabled_current_time_slot'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Auto Select 1st Available Time', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable the option if you want to select the first available time based on date automatically and shown in the delivery time field. Default is disable."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_auto_select_first_time">
							       <input type="checkbox" name="coderockz_auto_select_first_time" id="coderockz_auto_select_first_time" <?php echo (isset(get_option('coderockz_woo_delivery_time_settings')['auto_select_first_time']) && !empty(get_option('coderockz_woo_delivery_time_settings')['auto_select_first_time'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>


	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_delivery_time_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>
			</div>
			<div data-tab="tab7" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('General Pickup Time Settings', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-pickup-time-tab-notice"><span class="dashicons dashicons-yes"></span><?php _e(' Settings Changed Successfully', 'woo-delivery'); ?></p>
	                    <form action="" method="post" id ="coderockz_pickup_time_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Enable Pickup Time', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable Pickup Time select field in woocommerce order checkout page."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_enable_pickup_time">
							       <input type="checkbox" name="coderockz_enable_pickup_time" id="coderockz_enable_pickup_time" <?php echo (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Make Pickup Time Field Mandatory', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Make Pickup Time select field mandatory in woocommerce order checkout page. Default is optional."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_pickup_time_mandatory">
							       <input type="checkbox" name="coderockz_pickup_time_mandatory" id="coderockz_pickup_time_mandatory" <?php echo (isset($pickup_time_settings['pickup_time_mandatory']) && !empty($pickup_time_settings['pickup_time_mandatory'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_time_field_label"><?php _e('Pickup Time Field Label', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Pickup Time select field heading. Default is Pickup Time."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_pickup_time_field_label" name="coderockz_pickup_time_field_label" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes(esc_attr($pickup_time_settings['field_label'])) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<?php 
                    			$pickup_start_hour = "";
            					$pickup_start_min = "";
            					$pickup_start_format= "am";
                    			
                    			if(isset($pickup_time_settings['pickup_time_starts']) && $pickup_time_settings['pickup_time_starts'] !='') {
                    				$pickup_time_starts = (int)$pickup_time_settings['pickup_time_starts'];

                    				if($pickup_time_starts == 0) {
		            					$pickup_start_hour = "12";
		            					$pickup_start_min = "00";
		            					$pickup_start_format= "am";
		            				} elseif($pickup_time_starts > 0 && $pickup_time_starts <= 59) {

                    					$pickup_start_hour = "12";
                    					$pickup_start_min = sprintf("%02d", $pickup_time_starts);
                    					$pickup_start_format= "am";
                    				} elseif($pickup_time_starts > 59 && $pickup_time_starts <= 719) {
										$pickup_start_min = sprintf("%02d", (int)$pickup_time_starts%60);
										$pickup_start_hour = sprintf("%02d", ((int)$pickup_time_starts-$pickup_start_min)/60);
										$pickup_start_format= "am";
										
                    				} else {
										$pickup_start_min = sprintf("%02d", (int)$pickup_time_starts%60);
										$pickup_start_hour = sprintf("%02d", ((int)$pickup_time_starts-$pickup_start_min)/60);
										if($pickup_start_hour>12) {
											$pickup_start_hour = sprintf("%02d", $pickup_start_hour-12);
										}
										$pickup_start_format= "pm";
                    				}

                    			}
                    		?>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_time_slot_starts"><?php _e('Pickup Time Slot Starts From', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Pickup Time starts from the time that is specified here. Only numerical value is accepted."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<div id="coderockz_pickup_time_slot_starts" class="coderockz_pickup_time_slot_starts">
	                    			
	                        	<input name="coderockz_pickup_time_slot_starts_hour" type="number" class="coderockz-woo-delivery-number-field" max="12" min="1" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value > 12 || this.value < 1) this.value = null;" value="<?php echo $pickup_start_hour; ?>" placeholder="Hour" autocomplete="off"/>
	                        	<input name="coderockz_pickup_time_slot_starts_min" type="number" class="coderockz-woo-delivery-number-field" max="59" min="0" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value > 59 || this.value < 0) this.value = null;" value="<?php echo $pickup_start_min; ?>" placeholder="Minute" autocomplete="off"/>
	                        	<select class="coderockz-woo-delivery-select-field" name="coderockz_pickup_time_slot_starts_format">
									<option value="am" <?php selected($pickup_start_format,"am",true); ?>>AM</option>
									<option value="pm" <?php selected($pickup_start_format,"pm",true); ?>>PM</option>
								</select>
	                        	</div>
	                    	</div>
	                    	<?php 
                    			$pickup_end_hour = "";
            					$pickup_end_min = "";
            					$pickup_end_format= "am";
                    			
                    			if(isset($pickup_time_settings['pickup_time_ends']) && $pickup_time_settings['pickup_time_ends'] !='') {
                    				$pickup_time_ends = (int)$pickup_time_settings['pickup_time_ends'];
                    				if($pickup_time_ends == 0) {
		            					$pickup_end_hour = "12";
		            					$pickup_end_min = "00";
		            					$pickup_end_format= "am";
		            				} elseif($pickup_time_ends > 0 && $pickup_time_ends <= 59) {
                    					$pickup_end_hour = "12";
                    					$pickup_end_min = sprintf("%02d", $pickup_time_ends);
                    					$pickup_end_format= "am";
                    				} elseif($pickup_time_ends > 59 && $pickup_time_ends <= 719) {
										$pickup_end_min = sprintf("%02d", (int)$pickup_time_ends%60);
										$pickup_end_hour = sprintf("%02d", ((int)$pickup_time_ends-$pickup_end_min)/60);
										$pickup_end_format= "am";
										
                    				} elseif($pickup_time_ends > 719 && $pickup_time_ends <= 1439) {
										$pickup_end_min = sprintf("%02d", (int)$pickup_time_ends%60);
										$pickup_end_hour = sprintf("%02d", ((int)$pickup_time_ends-$pickup_end_min)/60);
										if($pickup_end_hour>12) {
											$pickup_end_hour = sprintf("%02d", $pickup_end_hour-12);
										}
										$pickup_end_format= "pm";
                    				} elseif($pickup_time_ends == 1440) {
										$pickup_end_min = "00";
										$pickup_end_hour = "12";
										$pickup_end_format= "am";
                    				}


                    			}
                    		?>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_slot_ends"><?php _e('Pickup Time Slot Ends At', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Pickup Time ends at the time that is specified here. Only numerical value is accepted."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<div id="coderockz_pickup_time_slot_ends" class="coderockz_pickup_time_slot_ends">
	                        	<input name="coderockz_pickup_time_slot_ends_hour" type="number" class="coderockz-woo-delivery-number-field" max="12" min="1" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value > 12 || this.value < 1) this.value = null;" value="<?php echo $pickup_end_hour; ?>" placeholder="Hour" autocomplete="off"/>
	                        	<input name="coderockz_pickup_time_slot_ends_min" type="number" class="coderockz-woo-delivery-number-field" max="59" min="0" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value > 59 || this.value < 0) this.value = null;" value="<?php echo $pickup_end_min; ?>" placeholder="Minute" autocomplete="off"/>
	                        	<select class="coderockz-woo-delivery-select-field" name="coderockz_pickup_time_slot_ends_format">
									<option value="am" <?php selected($pickup_end_format,"am",true); ?>>AM</option>
									<option value="pm" <?php selected($pickup_end_format,"pm",true); ?>>PM</option>
								</select>
	                        	</div>
	                        	<!-- <p class="coderockz_pickup_end_time_greater_notice">End Time Must after Start Time</p> -->
	                    	</div>
	                    	<?php
	                    		$pickup_duration = ""; 
	                    		$pickup_identity = "min";
                    			if(isset($pickup_time_settings['each_time_slot']) && !empty($pickup_time_settings['each_time_slot'])) {
                    				$pickup_time_slot_duration = (int)$pickup_time_settings['each_time_slot'];
                    				if($pickup_time_slot_duration <= 59) {
                    					$pickup_duration = $pickup_time_slot_duration;
                    				} else {
                    					$pickup_time_slot_duration = $pickup_time_slot_duration/60;
                    					$helper = new Coderockz_Woo_Delivery_Helper();
                    					if($helper->containsDecimal($pickup_time_slot_duration)){
                    						$pickup_duration = $pickup_time_slot_duration*60;
                    						$pickup_identity = "min";
                    					} else {
                    						$pickup_duration = $pickup_time_slot_duration;
                    						$pickup_identity = "hour";
                    					}
                    				}
                    			}
	                    	?>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_time_slot_duration"><?php _e('Each Pickup Time Slot Duration', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Each pickup time slot duration that is specified here. Only numerical value is accepted."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<div id="coderockz_pickup_time_slot_duration" class="coderockz_pickup_time_slot_duration">
	                        	<input name="coderockz_pickup_time_slot_duration_time" type="number" min="1" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value < 1) this.value = null;" class="coderockz-woo-delivery-number-field" value="<?php echo $pickup_duration; ?>" placeholder="" autocomplete="off"/>
	                        	<select class="coderockz-woo-delivery-select-field" name="coderockz_pickup_time_slot_duration_format">
									<option value="min" <?php selected($pickup_identity,"min",true); ?>>Minutes</option>
									<option value="hour" <?php selected($pickup_identity,"hour",true); ?>>Hour</option>
								</select>
	                        	</div>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_time_maximum_order"><?php _e('Maximum Pickup Per Time Slot', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Each time slot take maximum number of pickups that is specified here. After reaching the maximum pickup, the time slot is disabled automaticaly. Only numerical value is accepted. Blank this field means each time slot takes unlimited pickup."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_pickup_time_maximum_order" name="coderockz_pickup_time_maximum_order" type="number" class="coderockz-woo-delivery-number-field" min="1" onkeyup="if(!Number.isInteger(Number(this.value)) || this.value < 1) this.value = null;" value="<?php echo (isset($pickup_time_settings['max_pickup_per_slot']) && !empty($pickup_time_settings['max_pickup_per_slot'])) ? stripslashes(esc_attr($pickup_time_settings['max_pickup_per_slot'])) : ""; ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_pickup_time_format"><?php _e('Pickup Time format', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Time format that is used in everywhere which is available by this plugin. Default is 12 Hours."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<select class="coderockz-woo-delivery-select-field" name="coderockz_pickup_time_format">

	                    			<option value="" <?php if(isset($pickup_time_settings['time_format']) && $pickup_time_settings['time_format'] == ""){ echo "selected"; } ?>><?php _e('Select Time Format', 'woo-delivery'); ?></option>
									<option value="12" <?php if(isset($pickup_time_settings['time_format']) && $pickup_time_settings['time_format'] == "12"){ echo "selected"; } ?>>12 Hours</option>
									<option value="24" <?php if(isset($pickup_time_settings['time_format']) && $pickup_time_settings['time_format'] == "24"){ echo "selected"; } ?>>24 Hours</option>
								</select>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Disable Current Time Slot', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Make the time slot disabled that has the current time. In default, the time slot isn't disabled that has the current time."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_pickup_time_disable_current_time_slot">
							       <input type="checkbox" name="coderockz_pickup_time_disable_current_time_slot" id="coderockz_pickup_time_disable_current_time_slot" <?php echo (isset($pickup_time_settings['disabled_current_pickup_time_slot']) && !empty($pickup_time_settings['disabled_current_pickup_time_slot'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label"><?php _e('Auto Select 1st Available Time', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable the option if you want to select the first available time based on date automatically and shown in the pickup time field. Default is disable."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_auto_select_first_pickup_time">
							       <input type="checkbox" name="coderockz_auto_select_first_pickup_time" id="coderockz_auto_select_first_pickup_time" <?php echo (isset(get_option('coderockz_woo_delivery_pickup_settings')['auto_select_first_time']) && !empty(get_option('coderockz_woo_delivery_pickup_settings')['auto_select_first_time'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>
	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_pickup_time_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>
			</div>
			<div data-tab="tab8" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('Localization', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-localization-settings-notice"><span class="dashicons dashicons-yes"></span><?php _e(' Settings Changed Successfully', 'woo-delivery'); ?></p>
	                    <form action="" method="post" id ="coderockz_delivery_localization_settings_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

	                        <div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_order_limit_notice"><?php _e('Maximum Delivery Limit Exceed', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Maximum Order Limit Notice. Default is Maximum Order Limit Exceed."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_order_limit_notice" name="coderockz_woo_delivery_order_limit_notice" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['order_limit_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['order_limit_notice'])) ? get_option('coderockz_woo_delivery_localization_settings')['order_limit_notice'] : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_pickup_limit_notice"><?php _e('Maximum Pickup Limit Exceed', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Maximum Pickup Limit Notice. Default is Maximum Pickup Limit Exceed."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_pickup_limit_notice" name="coderockz_woo_delivery_pickup_limit_notice" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['pickup_limit_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['pickup_limit_notice'])) ? stripslashes(esc_attr(get_option('coderockz_woo_delivery_localization_settings')['pickup_limit_notice'])) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_delivery_details_text"><?php _e('Delivery Details', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Delivery Details text in order page, single order page, customer account page. Default is Delivery Details."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_delivery_details_text" name="coderockz_woo_delivery_delivery_details_text" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text'])) ? get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text'] : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_order_metabox_heading"><?php _e('Single Order Page Metabox Heading', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Single order page metabox heading text. Default is Delivery Date & Time."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_order_metabox_heading" name="coderockz_woo_delivery_order_metabox_heading" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['order_metabox_heading']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['order_metabox_heading'])) ? get_option('coderockz_woo_delivery_localization_settings')['order_metabox_heading'] : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_checkout_delivery_option_notice"><?php _e('Order Type Checkout Page Notice', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Notice if you make the order type field required but not given any value to the field. Default is Please Select Your Order Type."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_checkout_delivery_option_notice" name="coderockz_woo_delivery_checkout_delivery_option_notice" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['checkout_delivery_option_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['checkout_delivery_option_notice'])) ? get_option('coderockz_woo_delivery_localization_settings')['checkout_delivery_option_notice'] : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_checkout_date_notice"><?php _e('Delivery Date Checkout Page Notice', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Notice if you make the delivery date field required but not given any value to the field. Default is Please Enter Delivery Date."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_checkout_date_notice" name="coderockz_woo_delivery_checkout_date_notice" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['checkout_date_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['checkout_date_notice'])) ? get_option('coderockz_woo_delivery_localization_settings')['checkout_date_notice'] : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_checkout_pickup_date_notice"><?php _e('Pickup Date Checkout Page Notice', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Notice if you make the pickup date field required but not given any value to the field. Default is Please Enter Pickup Date."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_checkout_pickup_date_notice" name="coderockz_woo_delivery_checkout_pickup_date_notice" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['checkout_pickup_date_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['checkout_pickup_date_notice'])) ? stripslashes(esc_attr(get_option('coderockz_woo_delivery_localization_settings')['checkout_pickup_date_notice'])) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_checkout_time_notice"><?php _e('Delivery Time Checkout Page Notice', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Notice if you make the delivery time field required but not given any value to the field. Default is Please Enter Delivery Time."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_checkout_time_notice" name="coderockz_woo_delivery_checkout_time_notice" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['checkout_time_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['checkout_time_notice'])) ? get_option('coderockz_woo_delivery_localization_settings')['checkout_time_notice'] : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_checkout_pickup_time_notice"><?php _e('Pickup Time Checkout Page Notice', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Notice if you make the pickup time field required but not given any value to the field. Default is Please Enter Pickup Time."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_checkout_pickup_time_notice" name="coderockz_woo_delivery_checkout_pickup_time_notice" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset(get_option('coderockz_woo_delivery_localization_settings')['checkout_pickup_time_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['checkout_pickup_time_notice'])) ? stripslashes(esc_attr(get_option('coderockz_woo_delivery_localization_settings')['checkout_pickup_time_notice'])) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>
	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_delivery_localization_settings_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>
			</div>
			<div data-tab="tab9" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card">
					<p class="coderockz-woo-delivery-card-header"><?php _e('Other Settings', 'woo-delivery'); ?></p>
					<div class="coderockz-woo-delivery-card-body">
						<p class="coderockz-woo-delivery-other-settings-notice"><span class="dashicons dashicons-yes"></span><?php _e(' Settings Changed Successfully', 'woo-delivery'); ?></p>
	                    <form action="" method="post" id ="coderockz_delivery_other_settings_form_submit">
	                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

	                        <div class="coderockz-woo-delivery-form-group">
	                        	<span class="coderockz-woo-delivery-form-label" style="display:unset!important"><?php _e('Enable Delivery Field For Virtual Or Downloadable Products', 'woo-delivery'); ?></span>
	                        	<p class="coderockz-woo-delivery-tooltip" tooltip="Enable the delivery fields if there is any virtual or downloadable products in the cart. Default is disable."><span class="dashicons dashicons-editor-help"></span></p>
							    <label class="coderockz-woo-delivery-toogle-switch" for="coderockz_disable_fields_for_downloadable_products">
							       <input type="checkbox" name="coderockz_disable_fields_for_downloadable_products" id="coderockz_disable_fields_for_downloadable_products" <?php echo (isset(get_option('coderockz_woo_delivery_other_settings')['disable_fields_for_downloadable_products']) && !empty(get_option('coderockz_woo_delivery_other_settings')['disable_fields_for_downloadable_products'])) ? "checked" : "" ?>/>
							       <div class="coderockz-woo-delivery-toogle-slider coderockz-woo-delivery-toogle-round"></div>
							    </label>
	                    	</div>

	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" for="coderockz_woo_delivery_delivery_heading_checkout" style="display:unset!important"><?php _e('Heading On The Checkout Page', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Checkout heading text of delivery section. Default is Delivery Information."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<input id="coderockz_woo_delivery_delivery_heading_checkout" name="coderockz_woo_delivery_delivery_heading_checkout" type="text" class="coderockz-woo-delivery-input-field" value="<?php echo (isset($other_settings['delivery_heading_checkout']) && !empty($other_settings['delivery_heading_checkout'])) ? stripslashes(esc_attr($other_settings['delivery_heading_checkout'])) : "" ?>" placeholder="" autocomplete="off"/>
	                    	</div>

	                        <div class="coderockz-woo-delivery-form-group">
	                    		<label style="width:105px!important;" class="coderockz-woo-delivery-form-label" for="coderockz_delivery_time_format" style="display:unset!important"><?php _e('Field Position', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="Position of all the fields that are enabled by this plugin. Default is after order notes."><span class="dashicons dashicons-editor-help"></span></p>
	                    		<select class="coderockz-woo-delivery-select-field" name="coderockz_woo_delivery_field_position">
	                    			<option value="" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == ""){ echo "selected"; } ?>><?php _e('Select Position', 'woo-delivery'); ?></option>
									<option value="before_billing" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == "before_billing"){ echo "selected"; } ?>>Before Billing Address</option>
									<option value="after_billing" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == "after_billing"){ echo "selected"; } ?>>After Billing Address</option>
									<option value="before_shipping" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == "before_shipping"){ echo "selected"; } ?>>Before Shipping Address</option>
									<option value="after_shipping" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == "after_shipping"){ echo "selected"; } ?>>After Shipping Address</option>
									<option value="before_notes" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == "before_notes"){ echo "selected"; } ?>>Before Order Notes</option>
									<option value="after_notes" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == "after_notes"){ echo "selected"; } ?>>After Order Notes</option>
									<option value="before_payment" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == "before_payment"){ echo "selected"; } ?>>Between Your Order And Payment Section</option>
									<option value="before_your_order" <?php if(isset($other_settings['field_position']) && $other_settings['field_position'] == "before_your_order"){ echo "selected"; } ?>>Before Your Order Section</option>
								</select>
	                    	</div>
	                    	<div class="coderockz-woo-delivery-form-group">
	                    		<label class="coderockz-woo-delivery-form-label" style="display:unset!important"><?php _e('Custom CSS', 'woo-delivery'); ?></label>
	                    		<p class="coderockz-woo-delivery-tooltip" tooltip="If you want some custom css to avoid the plugin/theme conflict, put the css code here."><span class="dashicons dashicons-editor-help"></span></p>
	                        	<textarea id="coderockz_woo_delivery_code_editor_css" name="coderockz_woo_delivery_code_editor_css" class="coderockz-woo-delivery-textarea-field" placeholder="" autocomplete="off"><?php echo (isset($other_settings['custom_css']) && !empty($other_settings['custom_css'])) ? stripslashes(esc_attr($other_settings['custom_css'])) : "" ?>
                                </textarea>
	                    	</div>

	                        <input class="coderockz-woo-delivery-submit-btn" type="submit" name="coderockz_delivery_other_settings_form_submit" value="<?php _e('Save Changes', 'woo-delivery'); ?>" />

	                    </form>
                	</div>

                </div>
			</div>
			<div data-tab="tab10" class="coderockz-woo-delivery-tabcontent">
				<div class="coderockz-woo-delivery-card" style="box-sizing: border-box;padding: 30px 0 30px 30px;">
					<table width="100%">
					    <tr >
					        <th style="padding: 20px 20px 20px 10px;font-size: 18px;text-align: left;" width="50%">Features</th>
					        <th width="25%" style="text-align: center;font-size:18px">Free</th>
					        <th width="25%" style="text-align: center;font-size:18px">PRO</th>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Delivery Date</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Delivery Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Individual Pickup Date</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Individual Pickup Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Option for Selecting Home Delivery or Self Pickup</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Holidays</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Field Position Setting</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Every Texts are Translatable</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Separate Holidays for Delivery & Pickup</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide Plugin Module Completely for Specific Categories/Products</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide Plugin Module For Specific Shipping Method</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide Plugin Module For Specific User Role</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Category/product/zone/state/postcode wise offdays</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Specific dates as offdays for category/product/zone</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Set Specific dates/Weekdays as offdays for Delivery and Pickup individually</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Next Month Off for Certain Category</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Current Week Off/Next Week Off for Certain Category</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Shipping method wise Offdays</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Date Calendar Language</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Custom Delivery Time Slot</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Ability To Sort Orders Based on Delivery Details on The Woocommerce Orders Page</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Custom Pickup Time Slot</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide/Show Timeslot Based on Shipping Zone</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide/Show Timeslot Based on Shipping State</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide/Show Timeslot Based on Shipping PostCode</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide/Show Timeslot Based on Cart Products</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide/Show Timeslot Based on Cart Categories</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide Timeslot at a Specific Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide Timeslot for Current day</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Enable Timeslot only for Specific Date</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide/Show Pickup timeslot Based on Pickup Location</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Time slot with single time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Pickup Location</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Disable same day delivery/pickup</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Delivery/Pickup Details on a Calendar View</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Google Calendar Sync</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">WooCommerce shipping methods automatically changed based on Delivey/Pickup</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Dynamically Enable/Disable Delivery/Pickup Based on WooCommerce Shipping</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Delivery Tips Option</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Disable Delivery for Specific Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Disable Self Pickup for Specific Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Restrict Delivery Option(Cart Amount Base)</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Restrict Pickup Option(Cart Amount Base)</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Restrict Delivery Option Based on Category/Product</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Restrict Pickup Option Based on Category/Product</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Restrict Free Shipping(Cart Amount Base)</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Hide/Show free shipping only for today or some specific dates or any weekdays</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Enable/disable Free Shipping only for current date delivery</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>					    
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Special Open Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Set Category Wise Special Open Days for Delivery and Pickup Individually</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Delivery Reports with auto sorting</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Report of Product Quantity</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">One Tab To Control All Deliveries</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Delivery Reports As Excel Sheet(xlsx format)</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Product Quantity Reports As Excel Sheet(xlsx format)</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">WooCommerce App Support Using Order Note</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Filtering and Bulk Action Functionality on WooCommerce Order page </td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Controlling Store closing Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Different Store closing Time for Different Weekdays</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Disable Current day or Next Day or Further Day After a Certain Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Category wise Cutoff Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Different Processing Days for Delivery and Pickup</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Category Wise Processing Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Product Wise Processing Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Weekday Wise Processing Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Shipping Zone Wise Processing Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Shipping Method Wise Processing Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Different Processing Time for Delivery and Pickup</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Category Wise Processing Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Product Wise Processing Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Weekday Wise Processing Time</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Time Slot Fee</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Deliver Date Fee</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Weekday wise Delivery Fee</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Delivery Fee/Shipping Method within X Minutes/Hours</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Specific Shipping Method Only for First X Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Discount Coupon wise Specific Delivery Days</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Additional Field</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Change Delivery Details from Order Page</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-yes"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Notify Customer About Delivery Details Changing</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Laundry Service</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>

					    <tr>
					        <td class="coderockz-woo-delivery-proFree-feature">Google Calendar Sync</td>
					        <td class="coderockz-woo-delivery-proFree-free"><span class="dashicons dashicons-no-alt"></span></td>
					        <td class="coderockz-woo-delivery-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
					    </tr>
					    
					    <tfoot>
					        <tr>
					            <td class="coderockz-woo-delivery-proFree-feature"></td>
					            <td class="coderockz-woo-delivery-proFree-free"></td>
					            <td class="coderockz-woo-delivery-proFree-pro"><a href="https://coderockz.com/downloads/woocommerce-delivery-date-time-wordpress-plugin/" target="_blank" class="coderockz-woo-delivery-buy-now-btn">Buy Now</a></td>
					        </tr>
					    </tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>

</div>



