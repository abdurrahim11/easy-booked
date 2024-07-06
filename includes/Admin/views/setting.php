<div class="wrap">
    <h1><?php esc_html_e( 'Easy Booked Settings', 'appointment-booking'); ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting' ) ); ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'General', 'ams-wc-amazon' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting&action=zoom' ) ); ?>" class="nav-tab"><?php esc_html_e( 'Zoom', 'ams-wc-amazon' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=setting&action=email' ) ); ?>" class="nav-tab"><?php esc_html_e( 'Email', 'ams-wc-amazon' ); ?></a>
    </h2>
    <br />
    <h2><?php esc_html_e( 'Registered Booking', 'appointment-booking'); ?></h2>
    <p><?php esc_html_e( 'In this setting apply for registered booking', 'appointment-booking'); ?></p>
    <hr />
    <form  action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
        <table class="form-table" role="presentation"
        <tbody>
        <tr>
            <th scope="row">
                <label><?php esc_html_e( 'After Register Redirect', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <select name="abs[after_register_redirect]">
                    <?php
                    foreach ( get_pages() as $row ) {
                        ?>
                        <option <?php selected( $this->get_options( 'after_register_redirect' ), $row->ID  ); ?> value="<?php echo esc_attr(  $row->ID ); ?>"><?php echo esc_html( $row->post_title ); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php esc_html_e( 'Login Redirect', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <select name="abs[login_redirect]">
                    <?php
                    foreach ( get_pages() as $row ) {
                        ?>
                        <option <?php selected( $this->get_options( 'login_redirect' ), $row->ID  )?> value="<?php echo esc_attr(  $row->ID ); ?>"><?php echo esc_html( $row->post_title ); ?></option>
                        <?php
                    }
                    ?>
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php esc_html_e( 'Logout Redirect', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <select name="abs[logout_redirect]">
                    <option value="0"><?php esc_html_e( 'Default Login Page', 'appointment-booking' ); ?></option>
                    <?php
                    foreach ( get_pages() as $row ) {
                        ?>
                        <option <?php selected( $this->get_options( 'logout_redirect' ), $row->ID  ); ?> value="<?php echo esc_attr(  $row->ID ); ?>"><?php echo esc_html( $row->post_title ); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        </tbody>
        </table>
        <h2><?php esc_html_e( 'WooCommerce', 'appointment-booking'); ?></h2>
        <p><?php esc_html_e( 'In this setting apply for paid booking', 'appointment-booking'); ?></p>
        <hr/>
        <table class="form-table" role="presentation"
        <tbody>
        <tr>
            <th scope="row">
                <label><?php esc_html_e( 'Payment redirect after booking', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <select name="abs[paid_redirect]">
                    <option <?php  selected( $this->get_options( 'paid_redirect' ), 'checkout_page', true ); ?> value="checkout_page"><?php esc_html_e( 'Checkout Page (default)', 'appointment-booking' ); ?></option>
                    <option <?php  selected( $this->get_options( 'paid_redirect' ), 'cart_page', true ); ?> value="cart_page"><?php esc_html_e( 'Cart Page', 'appointment-booking' ); ?></option>
                </select>
                <p class="description">
                    <?php esc_html_e( 'Note: It will work paid  appointment and you can choose to have booked redirect to the checkout page (default) or the cart page instead.', 'appointment-booking' ); ?>
                </p>
            </td>
        </tr>
        </tbody>
        </table>
        <?php
        do_action( 'easy_booked_general_setting', $this );
        ?>
        <input type="hidden" name="action" value="abs_setting">
        <?php wp_nonce_field("abs_setting")?>
        <?php submit_button(esc_html__("Save Settings","appointment-booking"),"primary","general-setting-submit");?>
    </form>
</div>