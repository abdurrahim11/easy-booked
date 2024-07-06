<?php


namespace Appointment\Booking\Admin;

/**
 * Class Add_Mata_Fields
 *
 * @package Appointment\Booking\Admin
 */
class Add_Mata_Fields {

    /**
     * Add_Mata_Fields constructor.
     */
    public function __construct() {
        add_action( 'personal_options_update', array( $this, 'usermeta_form_field_phone_number_update' ) );
        add_action( 'edit_user_profile_update', array( $this, 'usermeta_form_field_phone_number_update' ) );
        add_filter('user_contactmethods', array( $this, 'mata_phone_numbers') );
    }

    /**
     * Phone number filed create
     */
    public function mata_phone_numbers( $profile_fields ) {
        $profile_fields['phone_number'] = esc_html__('Phone Number', 'appointment-booking' );
        return $profile_fields;
    }

    /**
     * Phone number update
     */
    public function usermeta_form_field_phone_number_update( $user_id ) {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }

        return update_user_meta( $user_id, 'phone_number', sanitize_text_field( $_POST['phone_number'] ) );
    }
}