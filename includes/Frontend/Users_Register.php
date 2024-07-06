<?php


namespace Appointment\Booking\Frontend;

/**
 * Class Users_Register
 *
 * @package Profile\Booking\Frontend
 */
ob_start(); // The shortcode provides ob_start for redirect support

class Users_Register {

    /**
     * Users_Register constructor.
     */
    public function __construct() {
        add_shortcode( 'easy-booked-registration-form', array( $this, 'registration_shortcode' ) );
        add_shortcode( 'easy-booked-login', array( $this, 'login_shortcode' ) );
    }

    /**
     * User register form
     */
    public function registration_shortcode( $atts, $content = "" ) {
        if ( ! current_user_can('manage_options') ) {
            if ( is_user_logged_in() ) {
                $link = get_permalink( get_option( 'abs_user_profile' ) );
                wp_redirect( esc_url_raw( $link ) );// redirect to home page
                exit;
            }
        }
        ob_start()
        ?>
        <div class="abs-registration-form">
            <form class="abs-register-form" method="post">
                <div class="abs-reg-form-group">
                    <label><?php esc_html_e( 'Full Name :', 'appointment-booking' ); ?></label>
                    <input name="full_name" type="text" class="abs-input abs-input-full-name" required />
                </div>
                <div class="abs-reg-form-group">
                    <label><?php esc_html_e( 'Username :', 'appointment-booking' ); ?></label>
                    <input name="username" type="text" class="abs-input abs-input-username" required />
                </div>
                <div class="abs-reg-form-group">
                    <label><?php esc_html_e( 'E-mail :', 'appointment-booking' ); ?></label>
                    <input name="email" type="email" class="abs-input abs-input-email" required />
                </div>
                <div class="abs-reg-form-group">
                    <label><?php esc_html_e( 'Phone Number:', 'appointment-booking' ); ?></label>
                    <input  type="text" class="abs-input abs-user-phone-number" required />
                </div>
                <div class="abs-reg-form-group">
                    <label><?php esc_html_e( 'Password :', 'appointment-booking' ); ?></label>
                    <input name="password" type="password" class="abs-input abs-input-password" required/>
                </div>
                <div class="abs-reg-form-group">
                    <p class="abs-error"></p>
                    <button class="button abs-register-button  abs-ladda-button abs-slide-right"><span class="label "><?php esc_html_e( 'Register', 'appointment-booking' ); ?></span> <span class="spinner"></span></button>
                </div>
            </form>
        </div>
        <?php
        $contain = ob_get_contents();
        ob_clean();
        return $contain;
    }

    /**
     * User register form handler
     */
    public function user_register_handler() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'abs_user_register' ) ) {
            $error['massage'] = esc_html__( 'You are trying to spamming', 'appointment-booking' );
            $error['status'] = 'error';
            echo wp_send_json( $error );
            wp_die();
        }

        $full_name = sanitize_text_field( $_POST['full_name'] );
        $username = sanitize_text_field( $_POST['username'] );
        $email = sanitize_text_field( $_POST['email'] );
        $password = sanitize_text_field( $_POST['password'] );
        $phone_number = sanitize_text_field( $_POST['phone_number'] );

        $user_id = wp_create_user(
            $username,
            $password,
            $email
        );

        if ( is_wp_error($user_id) )  {
            $error['massage'] = esc_html__( 'Username or Email already registered. Please try another one.', 'appointment-booking' );
            $error['status'] = 'error';
            echo wp_send_json( $error );
            wp_die();
        } else{
            update_user_meta( $user_id,'first_name', $full_name );
            update_user_meta( $user_id, 'phone_number', $phone_number );
            $user = get_user_by( 'id', $user_id );
            $user->set_role( 'booked_customer' );
            $setting = get_option( 'abs_setting' );
            $id = $setting['after_register_redirect'];
            $link = get_permalink( $id );
            $status['status'] = 'redirect';
            $status['location'] = esc_url_raw( $link );
            $info['user_login'] = $email;
            $info['user_password'] = $password;
            wp_signon( $info, false );
            echo wp_send_json( $status );
            wp_die();
        }

    }

    /**
     * Login form
     */
    public function login_shortcode( $atts, $content = "" ) {
        if ( ! current_user_can('manage_options') ) {
            if ( is_user_logged_in() ) {
                $link = get_permalink( get_option( 'abs_user_profile' ) );
                wp_redirect( esc_url_raw( $link ) );// redirect to home page
                exit;
            }
        }
        ob_start()
        ?>
        <div class="abs-registration-form">
            <form class="abs-login-form" method="post">
                <div class="abs-reg-form-group">
                    <label><?php esc_html_e( 'E-mail :', 'appointment-booking' ); ?></label>
                    <input name="email" type="email" class="abs-input abs-login-input-email" required />
                </div>
                <div class="abs-reg-form-group">
                    <label><?php esc_html_e( 'Password :', 'appointment-booking' ); ?></label>
                    <input name="email" type="password" class="abs-input abs-login-input-password" required />
                </div>
                <p class="forgetmenot">
                    <input name="rememberme" type="checkbox" id="rememberme" class="abs-rememberme" value="forever">
                    <label for="rememberme"><?php esc_html_e( 'Remember Me', 'appointment-booking' ); ?></label>
                </p>
                <div class="abs-reg-form-group">
                    <p class="abs-error abs-login-error"></p>
                    <button class="button abs-register-button  abs-ladda-button abs-slide-right"><span class="label "><?php esc_html_e( 'Login', 'appointment-booking' ); ?></span> <span class="spinner"></span></button>
                </div>
            </form>
        </div>
        <?php
        $contain = ob_get_contents();
        ob_clean();
        return $contain;
    }

    /**
     * Login handler
     */
    public function user_login_handler() {

        if ( ! wp_verify_nonce( $_POST['nonce'], 'abs_user_login' ) ) {
            $error['massage'] = esc_html__( 'You are trying to spamming', 'appointment-booking' );
            $error['status'] = 'error';
            echo wp_send_json( $error );
            wp_die();
        }

        $email = sanitize_text_field( $_POST['email'] );
        $password = sanitize_text_field( $_POST['password'] );
        $rememberme = sanitize_text_field( $_POST['rememberme'] );

        $info['user_login'] = $email;
        $info['user_password'] = $password;

        if ( ! empty( $rememberme ) ) {
            $info['remember'] = true;
        }

        $setting = get_option( 'abs_setting' );
        $id = $setting['login_redirect'];
        $link = get_permalink( $id );
        $status['status'] = 'success';
        $status['nonce'] = wp_create_nonce( 'abs_user_login' );
        $status['location'] = esc_url_raw( $link );

        $user_signon = wp_signon( $info, false );

        if ( is_wp_error( $user_signon ) ){
            $error['massage'] = esc_html__( 'Wrong username or password.', 'appointment-booking' );
            $error['status'] = 'error';
            echo wp_send_json( $error );
        } else {
            echo wp_send_json( $status );
        }

        wp_die();
    }
}
