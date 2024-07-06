<?php


namespace Appointment\Booking\Admin;

/**
 * Class Page_Title_Prefix
 *
 * @package Appointment \Booking\Admin
 */
class Page_Title_Prefix {

    /**
     * Page_Title_Prefix constructor.
     */
    public function __construct() {
        add_filter( 'display_post_states', array( $this, 'add_post_state' ), 10, 2 );
    }

    /**
     * Register page title
     *
     * @param $post_states
     * @param $post
     * @return mixed
     */
    public function add_post_state( $post_states, $post ) {
        if( $post->ID == get_option( 'abs_user_profile' ) OR $post->ID == get_option( 'abs_user_register_page' ) OR  $post->ID == get_option( 'abs_user_login' ) OR $post->ID == get_option( 'abs_user_appointment' ) ) {
            $post_states[] = esc_html__( 'Easy Booked', 'appointment-booking' );
        }
        return $post_states;
    }

}