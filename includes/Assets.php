<?php


namespace Appointment\Booking;

/**
 * Class Assets
 *
 * @package Appointment \Booking
 */
class Assets {

    /**
     * Assets constructor.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'front_end_enqueue' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
    }

    /**
     * Front css js enqueue
     */
    public function front_end_enqueue() {
        wp_enqueue_style( 'fontawesome', ABS_PLUGIN_URL . 'assets/css/all.css', false, ABS_VERSION );
        wp_enqueue_style( 'abs-frontend', ABS_PLUGIN_URL . 'assets/css/front-end.css', false, ABS_VERSION );
        wp_enqueue_style( 'abs-dashboard', ABS_PLUGIN_URL . 'assets/css/dashboard.css', false, ABS_VERSION );
        wp_enqueue_style( 'abs-phone-selector', ABS_PLUGIN_URL . 'assets/css/country-code-selector-public.css', false, ABS_VERSION );
        wp_enqueue_style( 'abs-tooltipster', ABS_PLUGIN_URL . 'assets/tooltips/tooltipster.main.css', false, ABS_VERSION );
        wp_enqueue_style( 'abs-tooltipster-light', ABS_PLUGIN_URL . 'assets/tooltips/themes/tooltipster-light.css', false, ABS_VERSION );
        wp_enqueue_script( 'abs-tooltipster', ABS_PLUGIN_URL . 'assets/tooltips/tooltipster.main.js', array( 'jquery' ), ABS_VERSION, true );
        wp_enqueue_script( 'abs-phone-selector', ABS_PLUGIN_URL . 'assets/js/country-code-selector-public.js', array( 'jquery' ), ABS_VERSION, true );
        wp_enqueue_script( 'abs-calendar', ABS_PLUGIN_URL . 'assets/js/calendar.js', array( 'jquery' ), ABS_VERSION, true );
        wp_enqueue_script( 'abs-frontend', ABS_PLUGIN_URL . 'assets/js/frontend.js', array( 'jquery' ), ABS_VERSION, true );

        wp_localize_script( 'abs-frontend', 'abs_data', array(
            'abs_plugin_url'     => ABS_PLUGIN_URL,
            'plugin_images_url'  => ABS_PLUGIN_URL . 'assets/images/',
            'ajax_url'           => admin_url( 'admin-ajax.php' ),
            'abs_user_register'  => wp_create_nonce( 'abs_user_register' ),
            'abs_user_login'     => wp_create_nonce( 'abs_user_login' ),
        ) );

        $options = get_option( 'abs_setting' );

        $primary_color   = isset( $options['color_primary'] ) ? $options['color_primary'] : '#8806CE';
        $secondary_color = isset( $options['color_secondary'] ) ? $options['color_secondary'] : '#9B23D0';
        $tertiary_color  = isset( $options['color_tertiary'] ) ? $options['color_tertiary'] : '#02ce9e';
        $button_hover_color = isset( $options['color_hover'] ) ? $options['color_hover'] : '#02b98e';

        $custom_css = "
            .abs-calendar-header {
                background: {$primary_color};
            }
      
            .abs-calendar-header th {
                border: 1px solid {$primary_color};
            }
            
            .abs-days {
                background: {$secondary_color};
                border: 1px solid {$secondary_color};
            }
            
            .abs-today-active span {
                border: 3px solid {$tertiary_color}; 
            }
            
            .abs-time-slots-active .abs-number:hover {
                background: {$tertiary_color};
            }
            
            .abs-bookme-timeslot-button {
                background: {$tertiary_color};
            }
            
            .abs-calendar-header th:first-child {
                border-top: 1px solid {$primary_color} !important;
            }
            
            .abs-bookme-timeslot-button:hover {
                background: {$button_hover_color};
            }
            
            .ab-book-button {
                background: {$tertiary_color};
                border: {$tertiary_color};
            }
            
            .ab-book-button:focus, 
            .ab-book-button:hover {
                background: {$button_hover_color};
                border: {$button_hover_color};
            }
            
            .ab-book-button-two {
                border: 1px solid {$tertiary_color};
            }
            
            .ab-book-button-two {
                color: {$tertiary_color};
            }
            
            .ab-book-button-two:focus, 
            .ab-book-button-two:hover {
                background: transparent;
                border: 1px solid {$button_hover_color};
                color: {$button_hover_color};
                outline: unset !important;
            }
            
            .abs-book-success-massage p {
                color: {$tertiary_color};
            }
        ";
        wp_add_inline_style( 'abs-frontend', $custom_css );
    }

    /**
     * Admin css js enqueue
     */
    public function admin_enqueue() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'abs-backend', ABS_PLUGIN_URL . 'assets/js/backend.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-core' ), ABS_VERSION, true );
        wp_enqueue_style( 'abs-backend', ABS_PLUGIN_URL . 'assets/css/backend.css', null, ABS_VERSION );
        wp_localize_script( 'abs-backend', 'abs_data', array(
            'ajax_url'                      => admin_url( 'admin-ajax.php' ),
            'abs_time_slot'                 => wp_create_nonce( 'abs_time_slot' ),
            'abs_custom_time_slot'          => wp_create_nonce( 'abs_custom_time_slot' ),
            'abs_check_api_connection'      => wp_create_nonce( 'abs_check_api_connection' ),
            'abs_check_api_connection_text' => esc_html__( 'Your zoom API connection check now ...', 'appointment-booking' ),
            'abs_file_required' => esc_html__( 'All fields are required.', 'appointment-booking' ),
            'abs_must_larger' => esc_html__( 'Must be larger or equal to Start date', 'appointment-booking' ),
            'abs_start_date' => esc_html__( 'Please select start date' , 'appointment-booking' ),
            'abs_error_filed' => esc_html__( 'Please fill this input field' , 'appointment-booking' ),
        ) );

    }

}