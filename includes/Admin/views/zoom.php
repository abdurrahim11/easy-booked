<div class="wrap">
    <h1><?php esc_html_e( 'Easy Booked Settings', 'appointment-booking'); ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting' ) ); ?>" class="nav-tab"><?php esc_html_e( 'General', 'ams-wc-amazon' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting&action=zoom' ) ); ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'Zoom', 'ams-wc-amazon' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting&action=email' ) ); ?>" class="nav-tab"><?php esc_html_e( 'Email', 'ams-wc-amazon' ); ?></a>
    </h2>
    <br />
    <h2><?php esc_html_e( 'Zoom Settings', 'appointment-booking'); ?></h2>
    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Enable/Disable', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <label for="">
                            <input <?php checked( get_option( 'abs_enable_zoom' ), 'yes' ) ?>
                                name="enable_zoom" type="checkbox" value="yes">
                            <?php esc_html_e( 'Enable zoom', 'appointment-booking' ); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Zoom Api Key', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <input name="zoom_api_key" type="text" value="<?php echo esc_attr( get_option( 'abs_zoom_api_key' ) ); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Zoom Secret key', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <input name="zoom_secret_key" type="text" value="<?php echo esc_attr( get_option( 'abs_zoom_secret_key' ) ); ?>" class="regular-text">
                    <?php
                    echo sprintf(
                        ' <p>%s<a href="https://marketplace.zoom.us/develop/create">%s</a>. %s</p>',
                        esc_html__( 'For getting started, go to the Zoom Developer Dashboard and create a ', 'appointment-booking' ),
                        esc_html__( 'new app', 'appointment-booking' ),
                        esc_html__( 'Choose JWT as the app type and copy the Zoom API key and secret.', 'appointment-booking' )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Default Duration Zoom', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <input name="duration_zoom" type="text" value="<?php echo esc_attr( get_option( 'duration_zoom', 30 ) ); ?>" class="regular-text">
                    <p class="description">
                        <?php esc_html_e( 'Note: This will only work if you do not set any end time in the Time Slots', 'appointment-booking' ); ?>
                     </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Default Meeting Password', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <input name="zoom_password" type="text" value="<?php echo esc_attr( get_option( 'zoom_password', 123456 ) ); ?>" class="regular-text">
                </td>
            </tr>
            </tbody>
        </table>
        <input type="hidden" name="action" value="abs_zoom">
        <?php wp_nonce_field( "abs_zoom", 'nonce' ) ?>
        <div class="abs-zoom-status">
        </div>
        <p class="submit">
            <input type="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'appointment-booking' ); ?>">
            <a href="#" class="button button-primary abs-check-api-connection"><?php esc_html_e( 'Check API Connection', 'appointment-booking' ); ?></a>
        </p>
    </form>
</div>