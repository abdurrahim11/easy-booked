<?php


namespace Appointment\Booking\Admin;


/**
 * Class Setting
 *
 * @package Appointment\Booking\Admin
 */
class Setting {

    /**
     * Load menu setting page
     */
    public function page() {
        $action = '';

        if ( isset( $_GET['action'] ) ) {
            $action = sanitize_text_field( $_GET['action'] );
        }

        switch ( $action ) {
            case 'email':
                require_once __DIR__ . '/views/email.php';
                break;
            case 'zoom':
                require_once __DIR__ . '/views/zoom.php';
                break;
            default:
                require_once __DIR__ . '/views/setting.php';
        }
    }

    /**
     * Setting update
     */
    public function setting() {
        $nonce = sanitize_text_field( $_POST['_wpnonce'] );

        if ( ! wp_verify_nonce( $nonce, 'abs_setting' ) ) {
            wp_die();
        }

        $data = array();
        foreach ( $_POST['abs'] as $key => $row ) {
            $data[ $key ] = sanitize_text_field( $row );
        }

        update_option( 'abs_setting', $data );

        wp_redirect( 'admin.php?page=setting' );
        exit();
    }

    public function get_options(  $index_name ) {
        $options = get_option( 'abs_setting' );
        if ( isset( $options[ $index_name ] ) ) {
            return $options[ $index_name ];
        } else {
            return;
        }
    }
}