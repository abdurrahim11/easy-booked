<?php


namespace Appointment\Booking\Admin;

/**
 * Class Email_Manage
 *
 * @package Appointment\Booking\Admin
 */
class Email_Manage {

    /**
     * Email_Manage constructor.
     */
    public function __construct() {
        add_action( 'abs_email_manage', array( $this, 'appointment_reminder' ) );
        add_action( 'abs_appointment_confirmation_email', array( $this, 'confirmation_email' ), 10, 2 );
        add_action( 'abs_appointment_approval_email', array( $this, 'approval_email' ) );
    }

    /**
     * Email template update
     */
    public function email() {

        if ( !wp_verify_nonce( $_POST['nonce'], 'abs_email' ) ) {
            wp_die();
        }

        $enable_reminder = sanitize_text_field( $_POST['enable_email_appointment_reminder'] );
        update_option( 'abs_enable_email_appointment_reminder', $enable_reminder );

        $email_reminder_time = sanitize_text_field( $_POST['email_reminder_time'] );
        update_option( 'abs_email_reminder_time', $email_reminder_time );

        $contain = wp_kses_post( $_POST['appointment_reminder_contain'] );
        update_option( 'abs_appointment_reminder_contain', $contain );

        $enable_confirmation = sanitize_text_field( $_POST['enable_appointment_confirmation'] );
        update_option( 'abs_enable_appointment_confirmation', $enable_confirmation );

        $appointment_confirmation_contain = wp_kses_post( $_POST['appointment_confirmation_contain'] );
        update_option( 'abs_appointment_confirmation_contain', $appointment_confirmation_contain );

        $enable_appointment_approval = sanitize_text_field( $_POST['enable_appointment_approval'] );
        update_option( 'abs_enable_appointment_approval', $enable_appointment_approval );

        $appointment_approval_contain = wp_kses_post( $_POST['appointment_approval_contain'] );
        update_option( 'abs_appointment_approval_contain', $appointment_approval_contain );

        wp_redirect( 'admin.php?page=setting&action=email' );
        exit();
    }

    /**
     * Appointment reminder email sent
     */
    public function appointment_reminder() {
        if ( empty( get_option( 'abs_enable_email_appointment_reminder' ) ) ) {
            return;
        }

        $reminder_time = get_option( 'abs_email_reminder_time' );
        $results = abs_get_appointment_reminder_id( $reminder_time );

        foreach ( $results as $id ) {

            if ( ! function_exists( 'wp_mail' ) ) {
                require_one . 'wp-includes/pluggable.php';
            }

            $massage = get_option( 'abs_appointment_reminder_contain' );
            $massage_html = wp_kses_post( $this->process_variables( $massage, $id ) );
            $header = array( 'Content-type: text/html; charset=UTF-8' );
            wp_mail( get_post_meta( $id, 'email', true ), 'Appointment Reminder', $massage_html, $header );
            update_post_meta( $id, 'appointment_reminder_email', 2 );
        }

    }

    /**
     * Appointment confirmation email sent
     */
    public function confirmation_email( $appointment_id, $title ) {
        if ( empty( get_option( 'abs_enable_appointment_confirmation' ) ) ) {
            return;
        }

        if ( ! function_exists( 'wp_mail' ) ) {
            require_one . 'wp-includes/pluggable.php';
        }

        $massage = get_option( 'abs_appointment_confirmation_contain' );
        $massage_html = wp_kses_post( $this->process_variables( $massage, $appointment_id ) );
        $header = array( 'Content-type: text/html; charset=UTF-8' );
        wp_mail( get_post_meta( $appointment_id, 'email', true ) , 'Appointment Confirm', $massage_html, $header );
        update_post_meta( $appointment_id, 'confirmation_email', true );
    }

    /**
     * Appointment approval email sent
     */
    public function approval_email( $appointment_id ) {

        if ( empty( get_option( 'abs_enable_appointment_approval' ) ) ) {
            return;
        }

        if ( ! function_exists( 'wp_mail' ) ) {
            require_one . 'wp-includes/pluggable.php';
        }

        $massage = get_option( 'abs_appointment_approval_contain' );
        $massage_html = wp_kses_post( $this->process_variables( $massage,$appointment_id ) );
        $header = array( 'Content-type: text/html; charset=UTF-8' );
        wp_mail( get_post_meta( $appointment_id, 'email', true ), 'Appointment Approval', $massage_html, $header );
        update_post_meta( $appointment_id, 'approval_email', 2 );
    }

    /**
     * Process email template variables
     *
     * @param $message
     * @param $appointment
     * @return string|string[]
     */
    public function process_variables( $message, $appointment ) {
        preg_match_all( "/%(.*?)%/", $message, $search );
        global $wpdb;

        foreach ( $search['1'] as $row ) {

            if ( strtolower( $row ) === strtolower( 'id' ) ) {
                $message = str_replace( "%" . $row . "%", $appointment, $message );
            }

            if ( strtolower( $row ) === strtolower( 'name' ) ) {
                $message = str_replace( "%" . $row . "%", get_the_title( $appointment ), $message );

            }

            if ( strtolower( $row ) === strtolower( 'email' ) ) {
                $message = str_replace( "%" . $row . "%", get_post_meta( $appointment, 'email', true ), $message );

            }

            if ( strtolower( $row ) === strtolower( 'title' ) ) {

                $table_name = $wpdb->prefix . 'abs_custom_time_slots';

                if ( strtolower( get_post_meta( $appointment, 'slot_type', true ) ) === strtolower( 'day_time_slot' ) ) {
                    $table_name = $wpdb->prefix . 'abs_time_slots';
                }

                $time_slots = $wpdb->get_row(
                    $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %s", get_post_meta( $appointment, 'slot_id', true ) )
                );

                $message = str_replace( "%" . $row . "%", $time_slots->title, $message );
            }

            if ( strtolower( $row ) === strtolower( 'date' ) ) {
                $message = str_replace( "%" . $row . "%", date_i18n( 'Y-m-d', get_post_meta( $appointment, 'start_date', true ) ), $message );
            }

            if ( strtolower( $row ) == strtolower( 'time' ) ) {

                $time_format = get_option( 'time_format' );

                if ( ! empty( get_post_meta( $appointment, 'end_date', true ) ) ) {
                    $appointment_time = sprintf(
                        "from %s to %s on %s",
                        date_i18n( $time_format, get_post_meta( $appointment, 'start_date', true ) ),
                        date_i18n( $time_format, get_post_meta( $appointment, 'end_date', true ) ),
                        date_i18n( 'Y-m-d', get_post_meta( $appointment, 'start_date', true ) )
                    );
                } else {
                    $appointment_time = sprintf(
                        "%s (All day)",
                        date_i18n( 'Y-m-d', get_post_meta( $appointment, 'start_date', true ) )
                    );
                }

                $message = str_replace( "%" . $row . "%", $appointment_time, $message );

            }

            if ( strtolower( $row ) === strtolower( 'zoom_join_link' ) ) {
                $message = str_replace( "%" . $row . "%", get_post_meta( $appointment, 'zoom_join_link', true ), $message );
            }

            if ( strtolower( $row ) === strtolower( 'zoom_password' ) ) {
                $message = str_replace( "%" . $row . "%", get_post_meta( $appointment, 'zoom_password', true ), $message );
            }

        }

        return $message;
    }


}