<?php


namespace Appointment\Booking\Admin;

/**
 * Class Appointment_Manage
 *
 * @package Appointment\Booking\Admin
 */
class Appointment_Manage {

    /**
     * Load calendar page
     */
    public function page() {

        $template = __DIR__ . '/views/appointments-list.php';

        if ( file_exists( $template ) ) {
            require_once $template;
        }
    }

    /**
     * Appointments Approve
     */
    public function appointments_approve() {
        $wpnonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

        if ( ! wp_verify_nonce( $wpnonce, 'abs-appointments-approve' ) ) {
            wp_die();
        }

        $id = sanitize_text_field( $_REQUEST['id'] );

        wp_update_post( array(
            "ID" => $id,
            "post_status" => "eb-approve",
        ) );

        do_action( 'abs_appointment_approval_email', $id );
        $location = $_SERVER['HTTP_REFERER'];
        wp_redirect( esc_url_raw( $location ) );
        exit();
    }

    /**
     * Appointments Pending
     */
    public function appointments_pending() {
        $wpnonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

        if ( ! wp_verify_nonce( $wpnonce, 'abs-appointments-pending' ) ) {
            wp_die();
        }

        $id = sanitize_text_field( $_REQUEST['id'] );

        wp_update_post( array(
            "ID" => $id,
            "post_status" => "eb-pending",
        ) );

        $location = $_SERVER['HTTP_REFERER'];
        wp_redirect( esc_url_raw( $location ) );
        exit();
    }

    /**
     * Appointments Delete
     */
    public function appointments_delete() {
        $wpnonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

        if ( ! wp_verify_nonce( $wpnonce, 'abs-appointments-delete' ) ) {
            wp_die();
        }

        $id = sanitize_text_field( $_REQUEST['id'] );

        wp_delete_post( $id, true );

        $location = $_SERVER['HTTP_REFERER'];
        wp_redirect( esc_url_raw( $location ) );
        exit();
    }

}