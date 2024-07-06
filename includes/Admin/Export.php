<?php


namespace Appointment\Booking\Admin;

/**
 * Class Export
 *
 * @package Appointment\Booking\Admin
 */
class Export {
    /**
     * Appointment info export csv
     */
    public function export_csv() {
        global $wpdb;

        $rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'easy-appointments' ORDER BY id DESC", 'ARRAY_A' );
        if ( $rows ) {
            $output_filename = 'appointment-list' . '.csv';
            $output_handle = @fopen( 'php://output', 'w' );

            header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
            header( 'Content-Description: File Transfer' );
            header( 'Content-type: text/csv' );
            header( 'Content-Disposition: attachment; filename=' .
                $output_filename );
            header( 'Expires: 0' );
            header( 'Pragma: public' );

            $first = true;

            foreach ( $rows as $row ) {
                // Add table headers
                if ( $first ) {
                    $titles = $this->csv_column_title( $row );
                    fputcsv( $output_handle, $titles );
                    $first = false;
                }

                //Add body
                $leadArray = $this->csv_column_body( $row );
                fputcsv( $output_handle, $leadArray );
            }

            fclose( $output_handle );
            exit();
        }
    }

    /**
     * File header area
     *
     * @param $row
     * @return array
     */
    public function csv_column_title( $row ) {
        $titles = array(
            esc_html__( 'Full Name', 'appointment-booking' ),
            esc_html__( 'Phone', 'appointment-booking' ),
            esc_html__( 'Email', 'appointment-booking' ),
            esc_html__( 'Date', 'appointment-booking' ),
            esc_html__( 'Time Slots', 'appointment-booking' ),
            esc_html__( 'Payment Status', 'appointment-booking' ),
            esc_html__( 'Status', 'appointment-booking' )
        );

        if ( strtolower( get_option( 'abs_enable_zoom' ) ) === strtolower( 'yes'  ) ) {
            $titles[] = esc_html__( 'Zoom Join Link', 'appointment-booking' );
            $titles[] = esc_html__( 'Zoom Join Password', 'appointment-booking' );
        }

        $options = get_option( 'abs_custom_fields' );

        if ( $options ) {
            foreach ( $options as $row ) {
                $titles[] = $row['label'];
            }
        }

        return $titles;
    }

    /**
     * File body area
     *
     * @param $row
     * @return array
     */
    public function csv_column_body( $row ) {
        $leadArray = array();
        array_push( $leadArray, $row['post_title'] );
        array_push( $leadArray, get_post_meta( $row['ID'], 'phone', true )  );
        array_push( $leadArray, get_post_meta( $row['ID'], 'email', true ) );
        array_push( $leadArray, date_i18n( 'Y-m-d', get_post_meta( $row['ID'], 'start_date', true ) ) );

        $time_format = get_option( 'time_format' );

        if ( ! empty( get_post_meta( $row['ID'], 'end_date', true ) ) ) {
            $appointment_time = sprintf(
                "from %s to %s",
                date_i18n( $time_format, get_post_meta( $row['ID'], 'start_date', true ) ),
                date_i18n( $time_format, get_post_meta( $row['ID'], 'end_date', true ) )
            );
        } else {
            $appointment_time = sprintf(
                "(All day)"
            );
        }

        array_push( $leadArray, $appointment_time );

        if ( empty( get_post_meta( $row['ID'], 'order_id', true ) ) ) {
            array_push( $leadArray, 'Free Booking' );
        } else {
            $order = wc_get_order( get_post_meta( $row['ID'], 'order_id', true ) );
            array_push( $leadArray, $order->get_status() );
        }

        if ( strtolower( $row['post_status'] ) === strtolower( 'eb-pending' ) ) {
            array_push( $leadArray, 'Pending' );
        } elseif ( strtolower( $row['post_status'] ) === strtolower( 'eb-approve' ) ) {
            array_push( $leadArray, 'Approve' );
        } elseif ( strtolower( $row['post_status'] ) === strtolower( 'eb-cancel' ) ) {
            array_push( $leadArray, 'cancel' );
        }

        if ( strtolower( get_option( 'abs_enable_zoom' ) ) === strtolower( 'yes'  ) ) {
            array_push( $leadArray, get_post_meta( $row['ID'], 'zoom_join_link', true ) );
            array_push( $leadArray, get_post_meta( $row['ID'], 'zoom_password', true ) );
        }



        $options_custom_fields = get_option( 'abs_custom_fields' );

        if ( $options_custom_fields ) {
            foreach ( $options_custom_fields as $single ) {
                $name = str_replace( ' ', '_', strtolower( $single['label'] ) );
                $value = get_post_meta( $row['ID'], 'cmf_' . $name, true );
                array_push( $leadArray, $value );
            }

        }
        return $leadArray;
    }
}