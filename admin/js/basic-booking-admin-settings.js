/**
 * Created by chroes on 05-09-2016.
 */
"use strict";


/**
 * Updates the maximum number of days a booking can last
 */
/*function update_max_booking_count() {
    var max_count = jQuery( '#max_booking_count_new' );
    if( max_count.val() > 0 ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_update_max_booking_count',
            _ajax_nonce: ajax_object.valid_menu,
            max_booking_count: max_count.val()
        }, function( data ) {
             data = JSON.parse( data );
            if( data[ 'error' ] == 'undefined' ) {
                alert( 'Maks antal dage skal et tal som er et eller større.')
            }
            else {
                jQuery( '#max_booking_count_current' ).val( data );
                max_count.val( '' );
            }
        });
    }
    else {
        max_count.val( 1 );
        alert( "Minimumsværdien for Maks antal dage er 1.");
    }
}*/

/**
 * Resets the maximum number of days a booking can last
 */
/*function reset_max_booking_count() {
    jQuery.post( ajax_object.ajax_url, {
        action: 'basic_booking_admin_reset_max_booking_count',
        _ajax_nonce: ajax_object.valid_menu
    }, function( data ) {
        data = JSON.parse( data );
        jQuery( '#max_booking_count_current' ).val( data );
        jQuery( '#max_booking_count_new' ).val( '' );
    });
}*/

/**
 * Updates the email sent out to customers when an order is made
 */
function update_auto_email() {
    var confirm_string = 'Dette vil overskrive indholdet af bestillings e-mailen.\n\n' +
        'Vil du forsætte?';
    if( confirm( confirm_string ) ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_update_auto_email',
            _ajax_nonce: ajax_object.valid_menu,
            response_email_new: jQuery( '#response_email_new' ).val()
        }, function( data ) {
            jQuery( '#response_email_old' ).val( data );
            jQuery( '#response_email_new' ).val('');
        });
    }
}

/**
 * Resets the email sent out to customers when an order is made
 */
function reset_auto_email() {
    var confirm_string = 'Dette vil sætte indholdet af bestillings e-mailen til det som den havde ved installationen af pluginet.\n\n' +
        'Vil du forsætte?';
    if( confirm( confirm_string ) ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_reset_auto_email',
            _ajax_nonce: ajax_object.valid_menu
        }, function( data ) {
            jQuery( '#response_email_old' ).val( data );
            jQuery( '#response_email_new' ).val( '' );
        });
    }
}

/**
 * Updates the email sent out to customers when an order is confirmed
 */
function update_confirmation_email() {
    var confirm_string = 'Dette vil overskrive indholdet af bekræftnings e-mailen.\n\n' +
        'Vil du forsætte?';
    if( confirm( confirm_string ) ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_update_confirmation_email',
            _ajax_nonce: ajax_object.valid_menu,
            confirmation_email_new: jQuery( '#confirmation_email_new' ).val()
        }, function( data ) {
            jQuery( '#confirmation_email_old' ).val( data );
            jQuery( '#confirmation_email_new' ).val('');
        });
    }
}

/**
 * Resets the email sent out to customers when an order is confirmed
 */
function reset_confirmation_email() {
    var confirm_string = 'Dette vil sætte indholdet af bekræftnings e-mailen til det som den havde ved installationen af pluginet.\n\n' +
        'Vil du forsætte?';
    if( confirm( confirm_string ) ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_reset_confirmation_email',
            _ajax_nonce: ajax_object.valid_menu
        }, function( data ) {
            jQuery( '#confirmation_email_old' ).val( data );
            jQuery( '#confirmation_email_new' ).val('');
        });
    }
}

/**
 * Updates the email sent out to customers when an order is rejected
 */
function update_rejection_email() {
    var confirm_string = 'Dette vil overskrive indholdet af afvisnings e-mailen.\n\n' +
        'Vil du forsætte?';
    if( confirm( confirm_string ) ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_update_rejection_email',
            _ajax_nonce: ajax_object.valid_menu,
            rejection_email_new: jQuery( '#rejection_email_new' ).val()
        }, function( data ) {
            jQuery( '#rejection_email_old' ).val( data );
            jQuery( '#rejection_email_new' ).val('');
        });
    }
}

/**
 * Resets the email sent out to customers when an order is rejected
 */
function reset_rejection_email() {
    var confirm_string = 'Dette vil sætte indholdet af afvisnings e-mailen til det som den havde ved installationen af pluginet.\n\n' +
        'Vil du forsætte?';
    if( confirm( confirm_string ) ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_reset_rejection_email',
            _ajax_nonce: ajax_object.valid_menu
        }, function( data ) {
            jQuery( '#rejection_email_old' ).val( data );
            jQuery( '#rejection_email_new' ).val('');
        });
    }
}

/**
 * Initializes the page
 */
jQuery().ready( function() {
    /*jQuery.post( ajax_object.ajax_url, {
        action: 'basic_booking_admin_get_max_booking_count',
        _ajax_nonce: ajax_object.valid_menu
    }, function( data ) {
        data = JSON.parse( data );
        jQuery( '#max_booking_count_current' ).val( data );
    });*/
    jQuery.post( ajax_object.ajax_url, {
        action: 'basic_booking_admin_load_auto_email',
        _ajax_nonce: ajax_object.valid_menu
    }, function( data ) {
        jQuery( '#response_email_old' ).val( data );
    });
    jQuery.post( ajax_object.ajax_url, {
        action: 'basic_booking_admin_load_confirmation_email',
        _ajax_nonce: ajax_object.valid_menu
    }, function( data ) {
        jQuery( '#confirmation_email_old' ).val( data );
    });
    jQuery.post( ajax_object.ajax_url, {
        action: 'basic_booking_admin_load_rejection_email',
        _ajax_nonce: ajax_object.valid_menu
    }, function( data ) {
        jQuery( '#rejection_email_old' ).val( data );
    });
} );