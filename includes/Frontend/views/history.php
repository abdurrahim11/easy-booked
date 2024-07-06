<div class="easy-dashboard-wrap">
    <div class="easy-dash-sidebar">
        <div class="easy-menu-area">
            <ul class="easy-dashboard-menu">
                <?php
                $action = sanitize_text_field( $_GET['action'] );

                foreach ( abs_user_profile_menu() as $key => $row ) {
                    $active_class = '';

                    if ( empty( $action ) ) {
                        $active_class = 'active';
                    } else {
                        if ( $action == $key ) {
                            $active_class = 'active';
                        }
                    }
                    ?>
                    <li class="<?php echo esc_attr( $active_class ); ?>">
                        <a href="<?php echo add_query_arg( 'action', $key, get_permalink() );?>"><i class="<?php echo esc_attr( $row[0] ); ?>"></i><span><?php echo esc_html( $row[1] ); ?></span></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="easy-dashboard-content">
        <div class="easy-alert easy-alert-name">
            <?php
            global $current_user;
            wp_get_current_user() ;
            ?>
            <strong><?php esc_html_e( 'Welcome back,', 'appointment-booking' ); ?></strong> <?php echo esc_html( $current_user->user_login ); ?>
        </div>
        <?php
        $time = strtotime( date_i18n( 'Y-m-d H:i:s' ) );

        $user = wp_get_current_user();

        if ( in_array( 'administrator', $user->roles ) || in_array( 'easy_booked_staff_member', $user->roles ) ) {
            $query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}posts WHERE post_status IN ( 'eb-complete', 'eb-approve' )
                AND post_type = %s ORDER BY id DESC",
                'easy-appointments'
            );
        } else {
            $query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}posts WHERE post_status IN ( 'eb-complete', 'eb-approve', 'eb-cancel' ) 
                AND post_author = %s AND post_type = 'easy-appointments' ORDER BY id DESC",
               get_current_user_id()
            );
        }

        $results = $wpdb->get_results( $query );
        ?>
        <div class="easy-content-area">
            <h4 class="easy-title">(<?php echo esc_html( $wpdb->num_rows ); ?>) <?php esc_html_e( ' Appointment History', 'appointment-booking' ); ?></h4>
        </div>
        <table class="abs-appointment-list">
            <thead>
            <tr>
                <th><?php esc_html_e( 'Name', 'appointment-booking' ); ?></th>
                <th><?php esc_html_e( 'Phone', 'appointment-booking' ); ?></th>
                <th><?php esc_html_e( 'Appointment  Time Slot', 'appointment-booking' ); ?></th>
                <th><?php esc_html_e( 'Status', 'appointment-booking' ); ?></th>
                <?php
                if ( strtolower( get_option( 'abs_enable_zoom' ) ) === strtolower( 'yes'  ) ) {
                    ?>
                    <th><?php esc_html_e( 'Zoom join link', 'appointment-booking' ); ?></th>
                    <th><?php esc_html_e( 'Zoom password', 'appointment-booking' ); ?></th>
                    <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $time_format = get_option( 'time_format' );

            foreach ( $results as $row ) {
                $date = date_i18n( 'M d, Y', get_post_meta( $row->ID , 'start_date', true ) );
                ?>
                <tr>
                    <td data-label="<?php esc_attr_e( 'Name:', 'appointment-booking' ); ?>">
                        <?php echo esc_html( $row->post_title );?>
                    </td>
                    <td data-label="<?php esc_attr_e( 'Phone:', 'appointment-booking' ); ?>">
                        <?php echo esc_html( get_post_meta( $row->ID, 'phone', true ) ); ?>
                    </td>
                    <td data-label="<?php esc_attr_e( 'Appointment  Time Slot:', 'appointment-booking' ); ?>">
                        <time>
                            <?php
                            if( ! empty( get_post_meta( $row->ID, 'end_date', true ) ) ) {
                                echo sprintf(
                                    "from %s to %s on %s",
                                    date_i18n( $time_format, get_post_meta( $row->ID, 'start_date', true ) ),
                                    date_i18n( $time_format, get_post_meta( $row->ID, 'end_date', true ) ) ,
                                    $date
                                );
                            } else {
                                echo sprintf(
                                    "%s (All day)",
                                    $date
                                );
                            }
                            ?>
                        </time>
                    </td>
                    <td data-label="<?php esc_attr_e( 'Status:', 'appointment-booking' ); ?>">
                        <?php
                        $appointment_type = empty( get_post_meta( $row->ID, 'order_id', true ) ) ? 'Free' : 'Paid';
                        if ( strtolower( $row->post_status  ) === strtolower( 'eb-pending' ) ) {
                            esc_html_e( 'Pending (Type:' . $appointment_type . ')', 'appointment-booking' );
                        } elseif ( strtolower( $row->post_status ) === strtolower( 'eb-approve' ) ) {
                            esc_html_e( 'Approve (Type:' . $appointment_type . ')', 'appointment-booking' );
                        } elseif ( strtolower( $row->post_status ) === strtolower( 'eb-complete' ) ) {
                            esc_html_e( 'Complete (Type:' . $appointment_type . ')', 'appointment-booking' );
                        }
                        ?>
                    </td>
                    <?php
                    if ( strtolower( get_option( 'abs_enable_zoom' ) ) === strtolower( 'yes'  ) ) {
                        ?>
                        <td data-label="<?php esc_attr_e( 'Zoom join link:', 'appointment-booking' ); ?>">
                            <?php
                            if ( ! empty( get_post_meta( $row->ID, 'zoom_join_link', true ) ) ) {
                                ?>
                                <a href="<?php echo esc_attr( get_post_meta( $row->ID, 'zoom_join_link', true ) );?>" class="abs-zoom" target="_blank" >Join</a>
                                <?php
                            }
                            ?>
                        </td>
                        <td data-label="<?php esc_attr_e( 'Zoom password:', 'appointment-booking' ); ?>">
                            <?php echo esc_html( get_post_meta( $row->ID, 'zoom_password', true ) );?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>