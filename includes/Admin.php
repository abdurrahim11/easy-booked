<?php


namespace Appointment\Booking;

/**
 * Class Admin
 * @package Appointment \Booking
 */
class Admin {

    /**
     * Admin constructor.
     */
    public function __construct( $email_manage, $zoom ) {
        $calendar_manage = new Admin\Calendar_Manage();
        $time_slots = new Admin\Time_Slots();
        $custom_time_slots = new Admin\Custom_Time_Slots();
        $appointment_manage = new Admin\Appointment_Manage();
        $export = new Admin\Export();
        $setting = new Admin\Setting();
        new Admin\Page_Title_Prefix();
        new Admin\Add_Mata_Fields();
        new Admin\Menu( $calendar_manage, $setting, $appointment_manage );
        $this->dispatch_actions( $time_slots, $custom_time_slots, $calendar_manage, $setting, $email_manage, $appointment_manage, $export, $zoom );
    }

    /**
     * Dispatch and bind actions
     */
    public function dispatch_actions( $time_slots, $custom_time_slots, $calendar_manage, $setting, $email_manage, $appointment_manage, $export, $zoom ) {
        // Calendar create delete
        add_action( 'admin_post_abs-calendar-delete', array( $calendar_manage, 'calendar_delete' ) );
        add_action( 'admin_post_abs_create_new_calendar', array( $calendar_manage, 'create_new_calendar' ) );

        // General setting
        add_action( 'admin_post_abs_general', array( $calendar_manage, 'general_setting' ) );

        // Calendar Time Slots
        add_action( 'wp_ajax_abs_time_slots', array( $time_slots, 'create_time_slot' ) );
        add_action( 'wp_ajax_abs_load_time_slots', array( $time_slots, 'get_timeslots_all' ) );
        add_action( 'wp_ajax_abs_time_slots_remove', array( $time_slots, 'time_slots_remove' ) );
        add_action( 'wp_ajax_abs_time_slots_sub', array( $time_slots, 'time_slots_sub_add' ) );

        // Calendar Custom Time Slots
        add_action( 'wp_ajax_abs_custom_time_slots', array( $custom_time_slots, 'create_custom_time_slots' ) );
        add_action( 'wp_ajax_abs_load_custom_time', array( $custom_time_slots, 'get_custom_timeslots_all' ) );
        add_action( 'wp_ajax_abs_custom_time_slots_remove', array( $custom_time_slots, 'custom_time_slots_remove' ) );
        add_action( 'wp_ajax_abs_custom_time_slots_sub', array( $custom_time_slots, 'custom_time_slots_sub_add' ) );
        add_action( 'wp_ajax_get_appointments', array( $time_slots, 'create_custom_time_slots' ) );

        // Admin setting
        add_action( 'admin_post_abs_setting', array( $setting, 'setting' ) );

        // Email setting
        add_action( 'admin_post_abs_email', array( $email_manage, 'email' ) );

        // Appointment Manage
        add_action( 'admin_post_abs-appointments-approve', array( $appointment_manage, 'appointments_approve' ) );
        add_action( 'admin_post_abs-appointments-pending', array( $appointment_manage, 'appointments_pending' ) );
        add_action( 'admin_post_abs-appointments-delete', array( $appointment_manage, 'appointments_delete' ) );

        // CSV Export
        add_action( 'admin_post_abs-export-csv', array( $export, 'export_csv' ) );

        // Zoom setting
        add_action( 'admin_post_abs_zoom', array( $zoom, 'zoom_setting' ) );
        add_action( 'wp_ajax_abs_check_api_connection', array( $zoom, 'check_api_connection' ) );
    }
}


add_action( "init", function () {
    if (!is_admin()) {
        add_filter('show_admin_bar', '__return_true', 99999999999999999 );
    }

});