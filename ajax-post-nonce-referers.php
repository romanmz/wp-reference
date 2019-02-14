<?php
/*
==================================================
AJAX AND POST SUBMISSION HANDLERS
==================================================

ADMIN URLS
ajaxurl                             // JS variable available in all admin screens, not available on the frontend
admin_url( 'admin-ajax.php' )       // PHP function
admin_url( 'admin-post.php' )       // PHP function

PROPERTIES TO BE SENT VIA AJAX
url                                 // The AJAX request must be sent to admin-ajax.php
request.action                      // The data must include the name of the action to trigger
request._ajax_nonce                 // You can use any key to send the nonce back to the server for verification, but if you name it '_ajax_nonce' you can take advantage of the 'check_ajax_referer' function

AJAX ACTIONS
wp_ajax_{$action}                   // for logged in users
wp_ajax_nopriv_{$action}            // for non logged in users

POST ACTIONS
admin_post                          // All submissions to admin-post.php (logged in users)
admin_post_nopriv                   // All submissions to admin-post.php (non logged in users)
admin_post_{$action}                // Only submissions for the requested 'action' (logged in users)
admin_post_nopriv_{$action}         // Only submissions for the requested 'action' (non logged in users)

AJAX RESPONSES
wp_doing_ajax()                     // Checks if the current script is an AJAX request
new WP_Ajax_Response                // Helper class to properly format, output, and die an xml response for AJAX requests
wp_send_json()                      // Similar but for JSON responses
wp_send_json_success()              // Same but formats the response to indicate that it was a successful request
wp_send_json_error()                // Same but for failed requests


==================================================
NONCES
==================================================
! WordPress nonces are only refreshed every 24 hours, if you need more serious security, implement a custom nonce system that actually creates a new nonce after every request

SIMPLE CREATION AND VERIFICATION
wp_create_nonce( $nonce_action=-1 ) -> $nonce_value     // For better securtiy, the action name should be as specific as possible, for example to delete the post with id of '5': 'delete-post-5'
wp_verify_nonce( $nonce_value, $nonce_action=-1 )       // Verifies the received nonce. Returns 0 if it's invalid, 1 if it's valid and 0-12 hours old, and '2' if it's valid and 12-24 hours old

SHORTCUTS
wp_nonce_field(                                         // Also creates a nonce but it automatically generates and outputs a hidden <input> field with for it
    $nonce_action = -1,
    $field_name = '_wpnonce',                           // The 'name' attribute to use for the nonce value
    $add_referer = true,                                // Whether or not to also include a hidden field to pass the current url as the referer page
    $echo = true                                        // Whether to immediately output the markup or just return it as a string
)
check_ajax_referer(                                     // Shortcut that looks up the nonce value in the request based on the given key, calls 'wp_verify_nonce' and dies if it's not valid
    $nonce_action = -1,
    $field_name = '_ajax_nonce|_wpnonce',               // Key to use to search for the nonce on $_REQUEST, must match the $field_name used to send the nonce
    $die_on_fail = true                                 // Whether or not the program should end if the nonce fails verification
)


==================================================
REFERER CHECKS
==================================================

FUNCTIONS
wp_referer_field( $echo=true )                          // Outputs a hidden <input> with the url of the current page named as '_wp_http_referer'
wp_get_referer()                                        // Checks the passed referer from a request's '_wp_http_referer' key if it exists, otherwise it checks the $_SERVER data
                                                        // Returns the referer URL only if it's from a white-listed host, and if the url is not the same as the current page, otherwise is 'false'
wp_validate_redirect( $url, $default='' )               // Checks if a url belongs to our white-listed set of hosts, returns $default if it's not
wp_safe_redirect( $location, $code=302 )                // Redirects to a new location, but only if it's from the white-listed hosts
wp_redirect( $location, $code=302 )                     // Redirects to any new location

FILTERS
allowed_redirect_hosts                                  // Edits the list of white-listed hosts for redirections
