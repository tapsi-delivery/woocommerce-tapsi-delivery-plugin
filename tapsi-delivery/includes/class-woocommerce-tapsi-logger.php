<?php

/**
 * Tapsi Logger
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 */

/**
 * Tapsi Logger
 *
 * Enables streamlined access to the WC_Logger class.
 * Automatically sets the context for the plugin.
 * Automatically runs input through wc_print_r for output
 * Access using the same methods as WC_Logger
 * Example: WCDD()->log->debug( "Hello World" );
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Tapsi_Logger
{

    /**
     * WooCommerce logging object
     *
     * @var WC_Logger
     */
    public $logger;

    /**
     * Context for logs
     *
     * @var array
     */
    public $context = array(
        'source' => 'tapsi-delivery'
    );

    /**
     * Log levels for WooCommerce that we should handle
     *
     * @var array
     */
    private $levels;

    /**
     * The minimum log level we will handle. Filterable.
     *
     * @var string
     */
    private $log_level;

    /**
     * Instantiate logger
     */
    public function __construct()
    {
        // Set up access to the WooCommerce logger
        $this->logger = wc_get_logger();

        // Get the log level desired
        if (!defined('WCDD_LOG_LEVEL')) define('WCDD_LOG_LEVEL', 'error');
        $this->log_level = apply_filters('wcdd_log_level', WCDD_LOG_LEVEL);

        // Set up the log levels
        $this->levels = array();
        $all_levels = array('emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug');
        foreach ($all_levels as $level) {
            $this->levels[] = $level;
            if ($level == $this->log_level) break; // Ignore log levels below our set level
        }
    }

    /**
     * Magic caller to map to the WC_Logger class
     * This method intercepts nonexistent methods
     * If the method is a WooCommerce log level, it logs the message
     *
     * @param string $method Name of the log level
     * @param array $args Array of arguments. First is the message
     * @return void
     */
    public function __call($level, $args)
    {
        if (in_array($level, $this->levels)) {
            $message = wc_print_r(array_shift($args), true);
            $this->logger->log($level, $message, $this->context);
        }
    }

}