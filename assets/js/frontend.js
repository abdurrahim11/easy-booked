;
(function ( $ ) {
    /* Book calendar */
    function abs_booked_calendar() {
        $( '.abs-page-calendar-button' ).on( 'click', function ( event ) {
            event.preventDefault();
            var calendar_id = $( this ).attr( 'data-calendar-id' );
            $( '.abs-booked-calendar-' + calendar_id + ' .abs-month-name' ).hide();
            $( '.abs-booked-calendar-' + calendar_id + ' .abs-load-time' ).show();
            var date = $( this ).attr( 'data-goto' );
            abs_book_calendar_load( date, calendar_id );
        } );
    }

    abs_booked_calendar();

    /* Calendar Load */
    function abs_book_calendar_load( date, calendar_id ) {
        $.ajax( {
            type: 'POST',
            url: abs_data.ajax_url,
            data: {
                action: 'book_calendar_load',
                date: date,
                calendar_id: calendar_id
            },
            success: function ( html ) {
                $( '.abs-booked-calendar-' + calendar_id ).parent().html( html );
                $( '.abs-month-name' ).show();
                abs_booked_calendar();
                window.abs_adjust_calendar_boxes();
                abs_disable_enable_button();
                abs_date_appointments();
                abs_tooltip();
            }
        } );
    }

    //Disable and Enable date click
    function abs_disable_enable_button() {
        $( '.abs-date' ).on( 'click', function () {
            if ( $( this ).hasClass( 'abs-time-slots-active' ) ) {
                return true;
            } else {
                return false;
            }
        } );
    }

    abs_disable_enable_button();

    // Function to adjust calendar sizing
    window.abs_adjust_calendar_boxes = function () {
        $( '.abs-booked-calendar' ).each( function () {
            var table_width = $( this ).find( 'tbody' ).width();
            var windowWidth = $( window ).width();
            var boxesWidth = $( this ).find( 'tbody tr.abs-week td' ).width();
            var width = boxesWidth * 7;
            abs_responsive_css( width, table_width );
            var calendarHeight = $( this ).height();
            boxesHeight = boxesWidth * 1;
            console.log( boxesHeight );
            $( this ).find( 'tbody tr.abs-week td' ).height( boxesHeight );
            $( this ).find( 'tbody tr.abs-week td .abs-date' ).css( 'line-height', boxesHeight + 'px' );
            $( this ).find( 'tbody tr.abs-week td .abs-date .number' ).css( 'line-height', boxesHeight + 'px' );

        } );
    }

    window.abs_adjust_calendar_boxes();

    $( window ).on( 'resize', function () {
        window.abs_adjust_calendar_boxes();
    } );

    // Tooltip Control area
    function abs_tooltip() {
        $( '.abs-tooltip' ).tooltipster( {
            theme: 'tooltipster-light',
            animation: 'grow',
            speed: 200,
            delay: 50,
            offsetY: 5
        } );
    }

    abs_tooltip();

    // Custom responsive
    function abs_responsive_css( width, boxes_box ) {
        if ( width > 1 ) {
            if ( width <= 579 ) {
                $( ".abs-time-solat-list h2" ).css( "font-size", "22px" );
                $( ".abs-time-solat-list strong" ).css( "display", "block" );
            } else if ( width >= 579 ) {
                $( ".abs-time-solat-list h2" ).css( "font-size", "27px" );
                $( ".abs-time-solat-list strong" ).css( "display", "" );
            }

            if ( width <= 470 ) {
                $( ".abs-bookme-timeslot-left" ).css( "width", "100%" );
                $( ".abs-bookme-timeslot-right" ).css( {"width": "100%", "text-align": "center"} );
            } else if ( width >= 470 ) {
                $( ".abs-bookme-timeslot-left" ).css( "width", "" );
                $( ".abs-bookme-timeslot-right" ).css( {"width": "", "text-align": ""} );
            }


        }
    }

    /* Date appointments */
    function abs_date_appointments() {
        // Booking date select
        var abs_date = '';
        $( '.abs-data' ).on( 'click', function () {
            if ( $( '.abs-active' ).length ) {
                $( '.abs-active' ).removeClass( 'abs-active' );
                $( '.abs-booked-area' ).remove();
            }

            if ( ! $( this ).hasClass( "abs-active" ) ) {
                if ( $( this ).attr( 'data-date' ) != abs_date ) {
                    abs_date = $( this ).attr( 'data-date' );
                    $( this ).addClass( 'abs-active' );
                    $( this ).parent().after( "<tr class='abs-booked-area abs-booked-area-loading'><td colspan='7'><img src='" + abs_data.plugin_images_url + "loading.gif' alt='Loading'></td></tr>" );
                    var date = $( this ).attr( 'data-date' );
                    var calendar_id = $( this ).attr( 'data-calendar-id' );
                    abs_get_appointments( date, calendar_id );
                } else {
                    abs_date = '';
                }
            }
        } );
    }

    abs_date_appointments();

    // Get all Appointments
    function abs_get_appointments( date, calendar_id ) {
        $.ajax( {
            type: 'POST',
            url: abs_data.ajax_url,
            data: {
                action: 'abs_get_appointments',
                date: date,
                calendar_id: calendar_id,
            },
            success: function ( html ) {
                $( '.abs-booked-area td' ).html( html );
                $( '.abs-booked-area-loading' ).removeClass( 'abs-booked-area-loading' );
                window.abs_adjust_calendar_boxes();
                abs_pop_booked_form();
            }
        } );
    }

    abs_pop_booked_form();
    // Show pop up booked form
    function abs_pop_booked_form() {
        $( '.abs-bookme-timeslot-button' ).on( 'click', function ( event ) {
            event.preventDefault();
            var calendar_id = $( this ).attr( 'data-calendar-id' );
            var selector_pop_up = $( this ).closest( '.abs-booked-calendar-area' ).find( '.abs-book-form-' + calendar_id + '.abs-booked-modal' );
            $( selector_pop_up ).show();
            $( selector_pop_up ).addClass( 'abs-loading-book-form' );
            var slot_id = $( this ).attr( 'data-slot-id' );
            var slot_type = $( this ).attr( 'data-slot-type' );
            var date = $( this ).attr( 'data-slot-date' );
            get_booked_form_contain( slot_id, slot_type, date, calendar_id, this );
        } );
        abs_modal_pop_up_close();
    }

    // Pop up modal close
    function abs_modal_pop_up_close() {
        $( '.abs-modal-close' ).on( 'click', function () {
            $( '.abs-booked-modal' ).hide();
        } );

        $( '.abs-book-form-cancel-btn' ).on( 'click', function ( event ) {
            event.preventDefault();
            $( '.abs-booked-modal' ).hide();
        } );

        $( window ).click( function ( event ) {
            if ( $( event.target ).hasClass( 'abs-booked-modal' ) ) {
                $( '.abs-booked-modal' ).hide();
            }
        } );
    }

    // get booking form modal contain
    function get_booked_form_contain( slot_id, slot_type, date, calendar_id, book_button = '' ) {
        var selector = $( book_button ).closest( '.abs-booked-calendar-area' ).find( '.abs-book-form-' + calendar_id + ' .abs-booked-modal-content' );
        $.ajax( {
            type: 'POST',
            url: abs_data.ajax_url,
            data: {
                action: 'booked_form_contain',
                slot_id: slot_id,
                slot_type: slot_type,
                date: date,
            },
            success: function ( html ) {
                $( '.abs-booked-modal-content' ).html( '' );
                $( selector ).html( html );
                $( '.abs-loading-book-form' ).removeClass( 'abs-loading-book-form' );
                abs_book_login();
                abs_book_form_phone();
                abs_book_form_submit()
            }
        } );

    }

    // Login for Appointment Booking
    function abs_book_login() {
        $( '.abs-book-login-area' ).on( 'submit', function ( event ) {
            event.preventDefault();
            abs_book_form_spinner();
            var email = $( '.abs-book-email' ).val();
            var password = $( '.abs-book-password' ).val();

            $.ajax( {
                type: 'POST',
                url: abs_data.ajax_url,
                data: {
                    action: 'abs_user_login',
                    email: email,
                    password: password,
                    rememberme: 'yes',
                    nonce: abs_data.abs_user_login
                },
                success: function ( data ) {
                    if ( data.status == 'error' ) {
                        $( '.abs-book-success-massage' ).html( '<p class="abs-book-error"> ' + data.massage + ' </p>' );
                        $( '.abs-ladda-button' ).removeAttr( 'data-loading' );
                    }

                    if ( data.status == 'success' ) {
                        var calendar_id = $( '.abs-book-login-button' ).attr( 'data-calendar-id' );
                        var slot_id = $( '.abs-book-login-button' ).attr( 'data-slot-id' );
                        var slot_type = $( '.abs-book-login-button' ).attr( 'data-slot-type' );
                        var date = $( '.abs-book-login-button' ).attr( 'data-slot-date' );
                        get_booked_form_contain( slot_id, slot_type, date, calendar_id );
                    }
                }

            } );
        } );
    }

    // Book Form phone number filed
    function abs_book_form_phone() {
        abs_modal_pop_up_close();
        var input = document.querySelector( ".abs-book-phone" );

        if ( input !== null ) {
            window.intlTelInput( input, {
                initialCountry: "auto",
                hiddenInput: "abs_book_phone",
                geoIpLookup: function ( callback ) {
                    $.get( 'https://ipinfo.io', function () {
                    }, "jsonp" ).always( function ( resp ) {
                        var countryCode = (resp && resp.country) ? resp.country : "us";
                        callback( countryCode );
                    } );
                },
                utilsScript: abs_data.abs_plugin_url + "assets/js/wc-utils.js",
            } );
        }
    }

    // Book Form submit
    function abs_book_form_submit() {
        $( '.abs-book-form-area' ).on( 'submit', function ( event ) {
            event.preventDefault();
            abs_book_form_spinner();
            var book_name = $( '.abs_book_name' ).val()
            var phone = $( 'input[name=abs_book_phone]' ).val()
            var book_email = $( '.abs-book-email' ).val()
            var slot_type = $( '.abs-book-form-btn-success' ).attr( 'data-slot-type' );
            var slots_id = $( '.abs-book-form-btn-success' ).attr( 'data-slot-id' );
            var date = $( '.abs-book-form-btn-success' ).attr( 'data-slot-date' );
            var custom_filed = $( '.abs-custom-field' ).serializeArray();

            $.ajax( {
                type: 'POST',
                url: abs_data.ajax_url,
                data: {
                    action: 'abs_booked_appointment',
                    book_name: book_name,
                    phone: phone,
                    book_email: book_email,
                    slot_type: slot_type,
                    slots_id: slots_id,
                    date: date,
                    custom_filed: custom_filed,
                },
                success: function ( data ) {
                    if ( data.status === 'success' ) {
                        var date = $( '.abs-booked-calendar' ).attr( 'data-date' );
                        $( '.abs-ladda-button' ).removeAttr( 'data-loading' );
                        $( '.abs-book-form-area' ).remove();
                        $( '.abs-booked-form-time-solat' ).after( data.html );
                        $( '.abs-booked-form-time-solat' ).remove();

                        abs_add_to_calendar();
                        $( '.abs-modal-close' ).on( 'click', function () {
                            abs_book_calendar_load( date, data.calendar_id );
                        } );

                        $( window ).click( function ( event ) {
                            if ( $( event.target ).hasClass( 'abs-booked-modal' ) ) {
                                abs_book_calendar_load( date, data.calendar_id );
                            }
                        } );

                    }

                    if ( data.status === 'failed' ) {
                        $( '.abs-book-success-massage' ).html( '<p class="abs-book-error"> ' + data.massage + ' </p>' );
                        $( '.abs-ladda-button' ).removeAttr( 'data-loading' );
                    }

                    if ( data.status === 'redirect' ) {
                        window.location = data.url;
                    }
                }
            } );

        } );
    }

    // Book from spinner
    function abs_book_form_spinner() {
        if ( $( '.abs-ladda-button' ).attr( 'data-loading' ) === 'string' ) {
            $( '.abs-ladda-button' ).removeAttr( 'data-loading' );
        } else {
            $( '.abs-ladda-button' ).attr( 'data-loading', '' );
        }
    }

    // Register user phone number
    var selection = document.querySelector( ".abs-user-phone-number" ) !== null;
    if ( selection ) {
        var input = document.querySelector( ".abs-user-phone-number" );
        window.intlTelInput( input, {
            initialCountry: "auto",
            hiddenInput: "abs_phone_number",
            geoIpLookup: function ( callback ) {
                $.get( 'https://ipinfo.io', function () {
                }, "jsonp" ).always( function ( resp ) {
                    var countryCode = (resp && resp.country) ? resp.country : "us";
                    callback( countryCode );
                } );
            },
            utilsScript: abs_data.abs_plugin_url + "assets/js/wc-utils.js",
        } );
    }

    /**
     * Register User
     */
    $( '.abs-register-form' ).on( 'submit', function () {
        event.preventDefault();
        $( '.abs-register-button' ).prop( "disabled", true );

        if ( $( '.abs-register-button' ).attr( 'data-loading' ) === 'string' ) {
            $( '.abs-register-button' ).removeAttr( 'data-loading' );
        } else {
            $( '.abs-register-button' ).attr( 'data-loading', '' );
        }

        var full_name = $( '.abs-input-full-name' ).val();
        var username = $( '.abs-input-username' ).val();
        var email = $( '.abs-input-email' ).val();
        var phone = $( 'input[name=abs_phone_number]' ).val();
        var password = $( '.abs-input-password' ).val();

        $.ajax( {
            type: 'POST',
            url: abs_data.ajax_url,
            data: {
                action: 'abs_registration_user',
                full_name: full_name,
                username: username,
                email: email,
                phone_number: phone,
                password: password,
                nonce: abs_data.abs_user_register,
            },
            success: function ( data ) {
                if ( data.status === 'error' ) {
                    $( '.abs-error' ).show();
                    $( '.abs-error' ).html( data.massage );
                    $( '.abs-register-button' ).removeAttr( 'data-loading' );
                    $( '.abs-register-button' ).removeAttr( "disabled" );
                } else if ( data.status === 'redirect' ) {
                    window.location = data.location;
                }
            }
        } );
    } )

    /**
     * Login user
     */
    $( '.abs-login-form' ).on( 'submit', function () {
        event.preventDefault();

        var rememberme = '';
        if ( $( '.abs-rememberme' ).prop( "checked" ) == true ) {
            rememberme = $( '.abs-rememberme' ).val();
        }

        var email = $( '.abs-login-input-email' ).val();
        var password = $( '.abs-login-input-password' ).val();

        $.ajax( {
            type: 'POST',
            url: abs_data.ajax_url,
            data: {
                action: 'abs_user_login',
                email: email,
                password: password,
                rememberme: rememberme,
                nonce: abs_data.abs_user_login,
            },
            success: function ( data ) {
                if ( data.status === 'error' ) {
                    $( '.abs-login-error' ).show();
                    $( '.abs-login-error' ).html( data.massage );
                    $( '.abs-register-button' ).removeAttr( 'data-loading' );
                    $( '.abs-register-button' ).removeAttr( "disabled" );
                } else if ( data.status === 'success' ) {
                    window.location = data.location;
                }
            }
        } );

    } );

    // Pop up modal close
    function abs_pop_up_close() {
        $( '.abs-pop-up-close' ).on( 'click', function () {
            $( '.abs-pop-up' ).hide();
        } );

        $( window ).click( function ( event ) {
            if ( $( event.target ).hasClass( 'abs-pop-up' ) ) {
                $( '.abs-pop-up' ).hide();
            }
        } );
    }

    abs_pop_up_close();

    // Open pop up
    $( '.abs-pop-up-button' ).on( 'click', function () {
        $( '.abs-pop-up' ).show();
        window.abs_adjust_calendar_boxes();
    } );

})( jQuery );