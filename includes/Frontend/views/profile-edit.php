<div class="easy-dashboard-wrap">
    <div class="easy-dash-sidebar">
        <div class="easy-menu-area">
            <ul class="easy-dashboard-menu">
                <?php
                $action = sanitize_text_field( isset( $_GET['action'] ) ? $_GET['action']:'' );

                $active_class = '';
                if ( empty( $action ) ) {
                    $active_class = 'active';
                }

                foreach ( abs_user_profile_menu() as $key => $row ) {
                    if ( $action == $key ) {
                        $active_class = 'active';
                    }
                    ?>
                    <li class="<?php echo esc_attr( $active_class ); ?>">
                        <a href="<?php echo add_query_arg( 'action', $key, get_permalink() );?>"><i class="<?php echo esc_attr( $row[0] ); ?>"></i><span><?php echo esc_html( $row[1] ); ?></span></a>
                    </li>
                    <?php
                    $active_class = "";
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="easy-dashboard-content">
        <div class="easy-alert easy-alert-name">
            <?php
            global $current_user;
            wp_get_current_user();
            ?>
            <strong><?php esc_html_e( 'Welcome back,', 'appointment-booking' ); ?></strong> <?php echo esc_html( $current_user->user_login ); ?>
        </div>
        <div class="easy-content-area">
            <h4 class="easy-title"> <?php esc_html_e( 'Profile Edit', 'appointment-booking' ); ?></h4>
            <div class="easy-form-box">
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
                    <input type="hidden" name="action" value="abs_user_profile_edit">
                    <?php
                    wp_nonce_field( 'abs_user_profile_edit' );
                    ?>
                    <div class="easy-form-group">
                        <label for="first_name"><?php echo esc_attr__( 'First Name', 'appointment-booking' ); ?></label>
                        <input type="text" class="easy-form-control" name="first_name" id="first_name" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'first_name', true ) ); ?>">
                    </div>
                    <div class="easy-form-group">
                        <label for="last_name"><?php echo esc_attr__( 'Last Name', 'appointment-booking' ); ?></label>
                        <input type="text" class="easy-form-control" name="last_name" id="last_name" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'last_name', true ) ); ?>">
                    </div>
                    <div class="easy-form-group">
                        <label for="email"><?php echo esc_attr__( 'E-mail', 'appointment-booking' ); ?></label>
                        <input type="text" class="easy-form-control" name="email" id="email" value="<?php echo esc_attr( $current_user->user_email); ?>">
                    </div>
                    <div class="easy-form-group">
                        <label for="number"><?php echo esc_attr__( 'Mobile Number', 'appointment-booking' ); ?></label>
                        <input type="text" class="easy-form-control" name="mobile_number" id="number" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'phone_number', true ) ); ?>">
                    </div>
                    <div class="easy-form-group">
                        <label for="email"><?php echo esc_attr__( 'Change Password', 'appointment-booking' ); ?></label>
                        <input type="text" name="change_password" class="easy-form-control" id="email" value="">
                    </div>
                    <div class="easy-form-group">
                        <input type="submit" class="easy-form-button" value="<?php echo esc_attr__( 'submit', 'appointment-booking' ); ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>