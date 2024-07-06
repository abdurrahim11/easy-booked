<?php


namespace Appointment\Booking;

use \Firebase\JWT\JWT;

/**
 * Class Zoom
 *
 * @package Appointment\Booking
 */
class Zoom {

    /**
     * API endpoint base
     *
     * @var string
     */
    private $api_url = 'https://api.zoom.us/v2/';

    /**
     * Zoom constructor.
     */
    public function __construct() {
        add_action( 'abs_appointment_confirmation_email', array( $this, 'create_zoom_meeting' ), 10, 2 );
    }

    /**
     * Zoom setting
     */
    public function zoom_setting() {
        $nonce = sanitize_text_field( $_POST['nonce'] );

        if ( !wp_verify_nonce( $nonce, 'abs_zoom' ) ) {
            wp_die();
        }

        update_option( 'abs_enable_zoom', sanitize_text_field( $_POST['enable_zoom'] ) );
        update_option( 'abs_zoom_api_key', sanitize_text_field( $_POST['zoom_api_key'] ) );
        update_option( 'abs_zoom_secret_key', sanitize_text_field( $_POST['zoom_secret_key'] ) );
        update_option( 'abs_duration_zoom', sanitize_text_field( $_POST['duration_zoom'] ) );
        update_option( 'abs_zoom_password', sanitize_text_field( $_POST['zoom_password'] ) );

        wp_redirect( 'admin.php?page=setting&action=zoom' );
        exit();
    }

    /**
     * Zoom api connection check
     */
    public function check_api_connection() {
        $nonce = sanitize_text_field( $_POST['nonce'] );

        if ( !wp_verify_nonce( $nonce, 'abs_check_api_connection' ) ) {
            wp_die();
        }

        $result = json_decode( $this->listUsers() );

        if ( isset( $result->code ) ) {

            $data = array(
                'status'  => 'failed',
                'massage' => $result->message,
            );

            wp_send_json( $data );
        } else {

            $data = array(
                'status'  => 'success',
                'massage' => esc_html__( 'Your connection success', 'appointment-booking' ),
            );

            wp_send_json( $data );
        }

        wp_die();
    }

    /**
     * Get zoom access token
     *
     * @return string
     */
    public function get_zoom_access_token() {

        $key = get_option( 'abs_zoom_secret_key', true );
        $payload = array(
            "iss" => get_option( 'abs_zoom_api_key', true ),
            'exp' => time() + 3600,
        );

        return JWT::encode( $payload, $key );
    }

    /**
     * Create zoom meeting link
     *
     * @param $id
     * @param $title
     */
    public function create_zoom_meeting( $id, $title ) {
        if ( empty( get_option( 'abs_enable_zoom' ) ) ) {
            return;
        }

        $duration = get_option( 'abs_duration_zoom', 30 );
        if ( ! empty( get_post_meta( $id, 'end_date', true ) ) ) {
            $duration = ( get_post_meta( $id, 'end_date', true ) - get_post_meta( $id, 'start_date', true ) ) / 60;
        }

        $createAMeetingArray = array();
        $createAMeetingArray['topic'] = $title;
        $createAMeetingArray['type'] = 2; //Scheduled
        $createAMeetingArray['start_time'] = date_i18n( 'Y-m-d\TH:i:s', get_post_meta( $id, 'start_date', true ) );
        $createAMeetingArray['timezone'] = get_option( 'timezone_string' );
        $createAMeetingArray['password'] = get_option( 'abs_zoom_password', 123456 );
        $createAMeetingArray['duration'] = $duration;

        if ( !empty( $createAMeetingArray ) ) {
            $data_json = $this->sendRequest( 'users/me/meetings', $createAMeetingArray, "POST" );
            $data = json_decode( $data_json );

            update_post_meta( $id, 'zoom_join_link', $data->join_url );
            update_post_meta( $id, 'zoom_password', $data->password );
        } else {
            return;
        }
    }

    /**
     * Get zoom list
     *
     * @param int $page
     * @param array $args
     * @return WP_Error|array|bool|string
     */
    public function listUsers( $page = 1, $args = array() ) {
        $defaults = array(
            'page_size'   => 300,
            'page_number' => absint( $page )
        );

        $args = wp_parse_args( $args, $defaults );

        return $this->sendRequest( 'users', $args, "GET" );
    }

    /**
     * Send request to API
     *
     * @param        $calledFunction
     * @param        $data
     * @param string $request
     *
     * @return array|bool|string|WP_Error
     */
    protected function sendRequest( $calledFunction, $data, $request = "GET" ) {
        $request_url = $this->api_url . $calledFunction;
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->get_zoom_access_token(),
                'Content-Type'  => 'application/json'
            )
        );

        if ( $request === "GET" ) {
            $args['body'] = !empty( $data ) ? $data : array();
            $response = wp_remote_get( $request_url, $args );
        } else if ( $request === "DELETE" ) {
            $args['body'] = !empty( $data ) ? json_encode( $data ) : array();
            $args['method'] = "DELETE";
            $response = wp_remote_request( $request_url, $args );
        } else if ( $request === "PATCH" ) {
            $args['body'] = !empty( $data ) ? json_encode( $data ) : array();
            $args['method'] = "PATCH";
            $response = wp_remote_request( $request_url, $args );
        } else if ( $request === "PUT" ) {
            $args['body'] = !empty( $data ) ? json_encode( $data ) : array();
            $args['method'] = "PUT";
            $response = wp_remote_request( $request_url, $args );
        } else {
            $args['body'] = !empty( $data ) ? json_encode( $data ) : array();
            $args['method'] = "POST";
            $response = wp_remote_post( $request_url, $args );
        }

        $response = wp_remote_retrieve_body( $response );

        if ( !$response ) {
            return false;
        }

        return $response;
    }
}