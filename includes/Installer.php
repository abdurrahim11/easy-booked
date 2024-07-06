<?php


namespace Appointment\Booking;

/**
 * Class Installer
 * @package Appointment \Booking
 */
class Installer {

    /**
     * Installer constructor.
     */
    public function __construct() {
        if ( ! wp_next_scheduled( 'abs_email_manage' ) ) {
            wp_schedule_event( time(), 'abs_every_five_minute', 'abs_email_manage' );
        }
    }

    /**
     * Initializes class
     *
     * @return void
     */
    public function run() {
        $this->create_table();
        $this->easy_booked_role();
        $this->create_page();
        $this->add_version();

        update_option( 'abs_email_reminder_time', 30 );

    }

    /**
     * Store plugin version
     *
     * @return void
     */
    public function add_version() {
        $installed = get_option( 'abs_installed' );

        if ( ! $installed ) {
            update_option( 'abs_installed', time() );
        }

        update_option( 'abs_version', ABS_VERSION );
    }

    /**
     * Create necessary database tables
     */
    public function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $time_slots_schema = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}abs_time_slots (
            id INT(10) UNSIGNED AUTO_INCREMENT,
            calendar_id VARCHAR(10) NOT NULL,
            day_name VARCHAR(15) NOT NULL,
            title VARCHAR(250) DEFAULT NULL,
            booking_time VARCHAR(20) NOT NULL,
            space_available INT(10) NOT NULL,
            booked_space INT(10 ) DEFAULT 0,
            product INT(50) DEFAULT NULL,
            booked_space_cancel INT(10 ) DEFAULT 0,
            status INT(2) DEFAULT 0,
            PRIMARY KEY (id)
        ) $charset_collate";

        $this->table_migrate( $time_slots_schema );

        $custom_time_slots_schema = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}abs_custom_time_slots (
            id INT(10) UNSIGNED AUTO_INCREMENT,
            calendar_id VARCHAR(10) NOT NULL,
            start_date VARCHAR(20) NOT NULL,
            end_date VARCHAR(20) NOT NULL,
            slots_type INT(2) DEFAULT NULL,
            title VARCHAR(250) DEFAULT NULL,
            booking_time VARCHAR(20)  DEFAULT NULL,
            space_available INT(10)  DEFAULT NULL,
            booked_space INT(10) DEFAULT 1,
            product INT(50) DEFAULT NULL,
            booked_space_cancel INT(10) DEFAULT 0,
            status INT(2) DEFAULT 0,
            PRIMARY KEY (id)
        ) $charset_collate";

        $this->table_migrate( $custom_time_slots_schema );

        $abs_calendar = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}abs_calendar (
            id INT(10) UNSIGNED AUTO_INCREMENT,
            name VARCHAR(150) NOT NULL,
            booking_type VARCHAR(50) NOT NULL,
            appointment_type VARCHAR(25) NOT NULL,
            appointment_default VARCHAR(50) NOT NULL,
            status INT(2) DEFAULT 0,
            PRIMARY KEY (id)
        ) $charset_collate";

        $this->table_migrate( $abs_calendar );
    }

    /**
     * Table migrate table
     *
     * @param $schema
     */
    public function table_migrate( $schema ) {
        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

    /**
     * Register role
     */
    public function easy_booked_role() {
        add_role(
            'booked_customer',
            esc_html__('Booked Customer', 'appointment-booking' ),
            array(
                'read'         => true,
                'delete_posts' => false
            )
        );

    }

    /**
     * Create page
     */
    public function create_page() {
        $user_id = get_current_user_id();

        if ( ! get_page_by_path( 'Register' ) ) {

            $args = array(
                'post_author'    => $user_id,
                'post_content'   => '[easy-booked-registration-form]',
                'post_title'     => 'Register',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed',
                '_aviaLayoutBuilder_active' => 'active',
                '_aviaLayoutBuilderCleanData' => '[easy-booked-registration-form]',
            );

            $post_id = wp_insert_post( $args );
            update_option( 'abs_user_register_page', $post_id );
        }

        if ( ! get_page_by_path( 'Profile ' ) ) {

            $args = array(
                'post_author'    => $user_id,
                'post_content'   => '[easy-booked-profile]',
                'post_title'     => 'Profile',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed',
                '_aviaLayoutBuilder_active' => 'active',
                '_aviaLayoutBuilderCleanData' => '[easy-booked-profile]',
            );

            $post_id = wp_insert_post( $args );
            update_option( 'abs_user_profile', $post_id );

            $data = array( 'after_register_redirect' => $post_id, 'login_redirect' => $post_id );
            update_option( 'abs_setting', $data );
        }

        if ( ! get_page_by_path( 'Login' ) ) {

            $args = array(
                'post_author'    => $user_id,
                'post_content'   => '[easy-booked-login]',
                'post_title'     => 'Login',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed',
                '_aviaLayoutBuilder_active' => 'active',
                '_aviaLayoutBuilderCleanData' => '[easy-booked-login]',
            );

            $post_id = wp_insert_post( $args );
            update_option( 'abs_user_login', $post_id );
            $data = get_option( 'abs_setting' );
            $data['logout_redirect'] = $post_id;
            update_option( 'abs_setting', $data );
        }

    }

}