<?php


namespace Appointment\Booking\Elementor_Widget;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Class Booking_Calendar_Widget
 *
 * @package Appointment\Booking\Elementor_Widget
 */
class Booking_Calendar_Widget extends Widget_Base {

    /**
     * @return string
     */
    public function get_name() {
        return 'booking_calendar_widget';
    }

    /**
     * @return string
     */
    public function get_title() {
        return esc_html__('Booking Calendar', 'appointment-booking');
    }

    /**
     * @return string
     */
    public function get_icon() {
        return 'eicon-calendar';
    }

    /**
     * @return array|string[]
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Elementor control register
     */
    protected function _register_controls() {
        $options = get_option( 'abs_setting' );

        $primary_color   = isset( $options['color_primary'] ) ? $options['color_primary'] : '#8806CE';
        $secondary_color = isset( $options['color_secondary'] ) ? $options['color_secondary'] : '#9B23D0';
        $tertiary_color  = isset( $options['color_tertiary'] ) ? $options['color_tertiary'] : '#02ce9e';
        $button_hover_color = isset( $options['color_hover'] ) ? $options['color_hover'] : '#02b98e';

        $options = $this->get_calendar_options();

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Booking Calendar', 'appointment-booking'),
            ]
        );

        $this->add_control(
            'calendar_id',
            [
                'label' => esc_html__('Select Calendar', 'appointment-booking'),
                'type' => Controls_Manager::SELECT,
                'options' => $options,
                'default' => '1',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Calendar Style', 'appointment-booking'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color_primary',
            [
                'label' => esc_html__('Primary Color', 'appointment-booking'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .abs-calendar-header' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .abs-calendar-header th' => 'border: 1px solid {{VALUE}};',
                    '{{WRAPPER}} .abs-calendar-header th:first-child' => 'border-top: 1px solid {{VALUE}} !important;',
                ],
                'default' => $primary_color,
            ]
        );

        $this->add_control(
            'color_secondary',
            [
                'label' => esc_html__('Secondary Color', 'appointment-booking'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .abs-days' => 'background: {{VALUE}}; border: 1px solid {{VALUE}};',
                ],
                'default' => $secondary_color,
            ]
        );

        $this->add_control(
            'color_tertiary',
            [
                'label' => esc_html__('Tertiary Color', 'appointment-booking'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .abs-today-active span' => 'border: 3px solid {{VALUE}};',
                    '{{WRAPPER}} .abs-time-slots-active .abs-number:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .abs-bookme-timeslot-button' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .abs-book-button' => 'background: {{VALUE}}; border: {{VALUE}};',
                    '{{WRAPPER}} .ab-book-button:focus, {{WRAPPER}} .ab-book-button:hover' => 'background: {{VALUE}}; border: {{VALUE}};',
                    '{{WRAPPER}} .ab-book-button-two' => 'border: 1px solid {{VALUE}}; color: {{VALUE}};',
                    '{{WRAPPER}} .abs-book-success-massage p' => 'color: {{VALUE}};',
                ],
                'default' => $tertiary_color,
            ]
        );

        $this->add_control(
            'color_hover',
            [
                'label' => esc_html__('Hover Color', 'appointment-booking'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .abs-bookme-timeslot-button:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .ab-book-button:focus, {{WRAPPER}} .ab-book-button:hover' => 'background: {{VALUE}}; border: {{VALUE}};',
                    '{{WRAPPER}} .ab-book-button-two:focus, {{WRAPPER}} .ab-book-button-two:hover' => 'background: transparent; border: 1px solid {{VALUE}}; color: {{VALUE}};',
                ],
                'default' => $button_hover_color,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $calendar_id = $settings['calendar_id'];

        if( ! empty( $calendar_id ) ) {
            echo do_shortcode( "[easy-booked calendar={$calendar_id}]" );
        }
    }

    /**
     * @return array
     */
    protected function get_calendar_options() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'abs_calendar';

        // Query to get rows from the calendar table
        $results = $wpdb->get_results("SELECT id, name FROM $table_name", ARRAY_A);

        // Prepare options array for the select dropdown
        $options = [];
        foreach ($results as $row) {
            $options[$row['id']] = $row['name'];
        }

        return $options;
    }
}
