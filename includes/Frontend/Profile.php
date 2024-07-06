<?php


namespace Appointment\Booking\Frontend;

/**
 * Class Appointment
 *
 * @package Appointment \Booking\Frontend
 */
ob_start(); // The shortcode provides ob_start for redirect support

class Profile {

    /**
     * Appointment  constructor.
     */
    public function __construct() {
        add_shortcode( 'easy-booked-profile', array( $this, 'profile' ) );
    }

    /**
     * @return false|string
     */
    public function profile( $atts, $content = "" ) {

        if ( ! is_user_logged_in() ) {
            $link = get_permalink( get_option( 'abs_user_login' ) );
            wp_redirect( esc_url_raw( $link ) );// redirect to home page
            exit;
        }

        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}abs_appointment WHERE user_id = %s",
                get_current_user_id()
            )
        );

        $action = '';

        if ( isset( $_GET['action'] ) ) {
            $action = $_GET['action'];
        }

        ob_start();

        switch ( $action ) {
            case 'history':
                $template = __DIR__ . '/views/history.php';
                break;
            case 'profile':
                $template =  __DIR__ . '/views/profile-edit.php';
                break;
            case 'upcomming':
                $template =  __DIR__ . '/views/profile.php';
                break;
            default:
                $template =  __DIR__ . '/views/profile.php';
        }

        $template = apply_filters( 'abs_user_template_load', $template, $action );

        if ( file_exists( $template ) ) {
            require_once $template;
        }

        $contain = ob_get_contents();
        ob_end_clean();
        return $contain;
    }

    /**
     * User profile info update
     */
    public function user_profile_edit() {

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'abs_user_profile_edit' ) ) {
            wp_die();
        }

        $args = array(
            'ID'         => get_current_user_id(),
            'user_email' => sanitize_text_field( $_POST['email'] ),
            'user_pass' => sanitize_text_field( $_POST['change_password'] ),
        );

        wp_update_user( $args );
        update_user_meta( get_current_user_id(),'first_name', sanitize_text_field( $_POST['first_name'] ) );
        update_user_meta( get_current_user_id(), 'phone_number', sanitize_text_field( $_POST['mobile_number'] )  );

        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect( $location );
        exit();
    }

}



