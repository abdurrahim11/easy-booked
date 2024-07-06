<?php


namespace Appointment\Booking\Frontend;

/**
 * Class PopUp
 *
 * @package Appointment\Booking\Frontend
 */
class PopUp {

    /**
     * PopUp constructor.
     */
    public function __construct() {
        add_shortcode( 'easy-booked-popup', array( $this, 'popup' ) );
    }

    /**
     * @param $atts
     * @param string $content
     * @return string
     */
    public function popup( $atts, $content = "" ) {
        $attributes = shortcode_atts(
            array(
                'button-text'  => 'Book Now',
                'button-class' => 'button',
            ),
            $atts,
            'easy-booked-popup'
        );

        $html = sprintf(
            ' 
            <button class="%s abs-pop-up-button">%s</button>
            <div class="abs-pop-up" max-width>
                <!-- Modal content -->
                <div class="abs-pop-up-modal-content">
                    <div class="abs-pop-up-close">
                        <i class="fas fa-times "></i>
                    </div>
                    %s
                </div>
            </div>',
            esc_attr( $attributes['button-class'] ),
            esc_html( $attributes['button-text'] ),
            do_shortcode( wp_kses_post( $content ) )
        );

        return $html;
    }
}