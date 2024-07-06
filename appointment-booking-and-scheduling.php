<?php
/**
 * Plugin Name:       Easy Booked - Appointment Booking Calendar And Scheduling Management System
 * Plugin URI:        http://joydevs.com/
 * Description:       Easy Appointment Booking manage system
 * Version:           2.1.0
 * Author:            JoyDevs
 * Author URI:        https://joydevs.com/
 * License:           GPL v2 or later
 * Text Domain:       appointment-booking
 * Domain Path:       /languages/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

if ( ! class_exists( 'Abs_AppointmentBooking' ) ) {

    /**
     * The main plugin class
     */
    final class Abs_AppointmentBooking {

        /**
         * Abs_AppointmentBooking constructor.
         */
        private function __construct() {
            $this->define_constants();

            register_activation_hook( __FILE__, array( $this, 'activate' ) );
            add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
            add_action( 'plugins_loaded', array( $this, 'plugins_loaded_text_domain' ) );
            add_action( 'register_plugin_activation', array( $this, 'activate' ) );
        }

        /**
         * Initializes a single instance
         */
        public static function init() {
            static $instance = false;

            if ( ! $instance ) {
                $instance = new self();
            }

            return $instance;
        }

        /**
         * Plugin text domain loaded
         */
        public function plugins_loaded_text_domain() {
            load_plugin_textdomain( 'appointment-booking', false, ABS_PLUGIN_PATH . 'languages/' );
        }

        /**
         * Define plugin path and url constants
         */
        public function define_constants() {
            define( 'ABS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
            define( 'ABS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            define( 'ABS_VERSION', '2.1.0' );
        }

        /**
         *  Init plugin
         */
        public function init_plugin() {
            new Appointment\Booking\Assets();
            $zoom = new \Appointment\Booking\Zoom();
            $email = new  Appointment\Booking\Admin\Email_Manage();

            if ( is_admin() ) {
                new Appointment\Booking\Admin( $email, $zoom );
            } else {
                new Appointment\Booking\Frontend();
            }

            new Appointment\Booking\Shortcode();
            new Appointment\Booking\WcPayment_Status();

            new Appointment\Booking\Elementor_Widget();

            do_action( 'loaded_easy_booked_pro' );

        }

        /***
         * Do Stuff Plugin activation
         */
        public function activate() {
            $install = new Appointment\Booking\Installer();
            $install->run();
        }
    }

}

/**
 * Initializes the main plugin
 *
 * @return \Abs_AppointmentBooking
 */
function abs_appointment_booking() {
    return Abs_AppointmentBooking::init();
}

/**
 * Rick off the plugin
 */
abs_appointment_booking();






