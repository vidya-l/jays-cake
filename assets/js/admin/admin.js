/* global ajaxurl, jays_cake_ux */
( function ( wp, $ ) {
    'use strict';

    if ( !wp ) {
        return;
    }

    $( function () {
        // Dismiss notice
        $( document ).on( 'click', '.jays-cake-notice .notice-dismiss', function () {
            $.ajax( {
                type: 'POST',
                url: ajaxurl,
                data: { nonce: jays_cake_ux.nonce, action: 'jays_cake_dismiss_notice' },
                dataType: 'json'
            } );
        } );
    } );

    $( function () {
        $( document ).on( 'click', '.jays-cake-install-now', function ( event ) {
            var $button = $( event.target );

            if ( $button.hasClass( 'activate-now' ) ) {
                return true;
            }

            event.preventDefault();

            if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
                return;
            }

            if ( wp.updates.shouldRequestFilesystemCredentials && !wp.updates.ajaxLocked ) {
                wp.updates.requestFilesystemCredentials( event );

                $( document ).on( 'credential-modal-cancel', function () {
                    var $message = $( '.jays-cake-install-now.updating-message' );

                    $message
                        .removeClass( 'updating-message' )
                        .text( wp.updates.l10n.installNow );

                    wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
                } );
            }

            wp.updates.installPlugin( {
                slug: $button.data( 'slug' )
            } );
        } );
    } );
} )( window.wp, jQuery );