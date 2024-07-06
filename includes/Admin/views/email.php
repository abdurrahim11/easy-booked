<div class="wrap">
    <h1><?php esc_html_e( 'Easy Booked Settings', 'appointment-booking'); ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting' ) ); ?>" class="nav-tab"><?php esc_html_e( 'General', 'ams-wc-amazon' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting&action=zoom' ) )?>" class="nav-tab"><?php esc_html_e( 'Zoom', 'ams-wc-amazon' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting&action=email' ) )?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'Email', 'ams-wc-amazon' ); ?></a>
    </h2>
    <br />
    <h3><?php esc_html_e( 'You can use following variables in your templates:', 'appointment-booking' ); ?></h3>
    <?php
    foreach ( abs_get_appointment_variable_list() as $row ) {
        ?>
        <code>%<?php echo esc_html( $row ); ?>%</code>
        <?php
    }
    ?>
    <h2><?php esc_html_e( 'Customer Appointment Reminder', 'ams-wc-amazon' ); ?></h2>
    <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Enable/Disable', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <label for="">
                            <input <?php checked( get_option( 'abs_enable_email_appointment_reminder' ), 'yes' ) ?>
                                name="enable_email_appointment_reminder" type="checkbox" value="yes">
                            <?php esc_html_e( 'Enable this email notification', 'appointment-booking' ); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Reminder Time', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <select name="email_reminder_time">
                        <?php
                        foreach ( abs_get_time_list() as $key => $value ) {
                            ?>
                            <option <?php selected( get_option( 'abs_email_reminder_time' ), $key ); ?>
                                    value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description">
                        <?php esc_html_e( 'Note:  To make this option work,  you would need to setup cron to run from the server level using the following command:', 'appointment-booking' ); ?>
                        <code>
                            */5 * * * * wget -qO- <?php echo esc_html( site_url( '/wp-cron.php' ) ); ?> &>/dev/null
                        </code>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Content', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <?php
                    $contain = '<p style="text-align: left;">Just a friendly reminder that you have an appointment coming up soon! Here the appointment information:</p>
                                <p style="text-align: left;">Time: %time%</p>
                                <p style="text-align: left;">Date: %date%</p>';
                    $settings = array(
                        'teeny'         => true,
                        'textarea_rows' => 15,
                        'tabindex'      => 1,
                        "media_buttons" => false,
                        "textarea_rows" => 8,
                        "tabindex"      => 4

                    );
                    wp_editor( wp_kses_post( get_option( 'abs_appointment_reminder_contain', $contain ) ), 'appointment_reminder_contain', $settings );
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
        <h2><?php esc_html_e( 'Appointment Confirmation', 'appointment-booking' ); ?></h2>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Enable/Disable', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <label for="">
                            <input <?php checked( get_option( 'abs_enable_appointment_confirmation' ), 'yes' ) ?>
                                    name="enable_appointment_confirmation" type="checkbox" value="yes">
                            <?php esc_html_e( 'Enable this email notification', 'appointment-booking' ); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Content', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <?php
                    $appointment_confirmation_contain = '<p style="text-align: left;">Your appointment requested has been approved. Here your appointment information::</p>
                                    <p style="text-align: left;">Time: %time%</p>
                                    <p style="text-align: left;">Date: %date%</p>';
                    wp_editor( wp_kses_post( get_option( 'abs_appointment_confirmation_contain', $appointment_confirmation_contain ) ), 'appointment_confirmation_contain', $settings );
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
        <h2><?php esc_html_e( 'Appointment Approval', 'appointment-booking' ); ?></h2>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Enable/Disable', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <label for="">
                            <input <?php checked( get_option( 'abs_enable_appointment_approval' ), 'yes' ) ?>
                                    name="enable_appointment_approval" type="checkbox" value="yes">
                            <?php esc_html_e( 'Enable this email notification', 'appointment-booking' ); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Content', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <?php
                    $appointment_approval_contain = '<p style="text-align: left;">The appointment you requested has been approved! Here your appointment information:</p>
                                <p style="text-align: left;">Time: %time%</p>
                                <p style="text-align: left;">Date: %date%</p>';
                    wp_editor( wp_kses_post( get_option( 'abs_appointment_approval_contain', $appointment_approval_contain ) ), 'appointment_approval_contain', $settings );
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
        <input type="hidden" name="action" value="abs_email">
        <?php wp_nonce_field( "abs_email", 'nonce' ) ?>
        <?php submit_button( esc_html__( "Save Settings", "appointment-booking" ), "primary", "general-setting-submit" ); ?>
    </form>
</div>