<?php


namespace Appointment\Booking;

/**
 * Class Shortcode
 *
 * @package Appointment \Booking
 */
class Shortcode {

    /**
     * Shortcode constructor.
     */
    public function __construct() {
        $profile = new Frontend\Profile();
        new Frontend\PopUp();
        $user_register = new Frontend\Users_Register();
        $calendar = new Frontend\Build_Calendar();
        $booked_appointment = new Frontend\Booked_Appointment();
        $this->dispatch_actions( $user_register, $calendar, $booked_appointment, $profile );
    }

    /**
     * Dispatch and bind actions
     */
    public function dispatch_actions( $user_register, $calendar, $booked_appointment, $profile ) {
        // Appointments booking
        add_action( 'wp_ajax_abs_get_appointments', array( $calendar, 'get_appointments' ) );
        add_action( 'wp_ajax_nopriv_abs_get_appointments', array( $calendar, 'get_appointments' ) );
        add_action( 'wp_ajax_booked_form_contain', array( $calendar, 'booked_form_contain' ) );
        add_action( 'wp_ajax_nopriv_booked_form_contain', array( $calendar, 'booked_form_contain' ) );
        add_action( 'wp_ajax_abs_booked_appointment', array( $booked_appointment, 'booked_appointment' ) );
        add_action( 'wp_ajax_nopriv_abs_booked_appointment', array( $booked_appointment, 'booked_appointment' ) );
        add_action( 'wp_ajax_book_calendar_load', array( $calendar, 'book_calendar_load' ) );
        add_action( 'wp_ajax_nopriv_book_calendar_load', array( $calendar, 'book_calendar_load' ) );

        // User register form
        add_action( 'wp_ajax_nopriv_abs_registration_user', array( $user_register, 'user_register_handler' ) );
        add_action( 'wp_ajax_abs_registration_user', array( $user_register, 'user_register_handler' ) );
        add_action( 'wp_ajax_abs_user_login', array( $user_register, 'user_login_handler' ) );
        add_action( 'wp_ajax_nopriv_abs_user_login', array( $user_register, 'user_login_handler' ) );
        add_action( 'admin_post_abs_user_profile_edit', array( $profile, 'user_profile_edit' ) );
        add_action( 'admin_post_nopriv_abs_user_profile_edit', array( $profile, 'user_profile_edit' ) );
    }
}