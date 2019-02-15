<?php
/*
==================================================
AJAX SUBMISSION HANDLERS
==================================================
https://developer.wordpress.org/plugins/javascript/ajax/

REQUEST URL
ajaxurl                             // JS variable available in all admin screens, not available on the frontend
admin_url( 'admin-ajax.php' )       // PHP function, returns the url where the ajax requests need to be sent

PROPERTIES TO BE SENT VIA AJAX
url                                 // The AJAX request must be sent to admin-ajax.php
request.action                      // The data must include the name of the action to trigger
request._ajax_nonce                 // You can use any key to send the nonce value to the server, but if you name it '_ajax_nonce' you can take advantage of the 'check_ajax_referer' function

AJAX ACTIONS
wp_ajax_{$action}                   // for logged in users
wp_ajax_nopriv_{$action}            // for non logged in users

RESPONSES
wp_doing_ajax()                     // Checks if the current script is an AJAX request
new WP_Ajax_Response                // Helper class to properly format, output, and die an xml response for AJAX requests
wp_send_json()                      // Similar but for JSON responses
wp_send_json_success()              // Same but formats the response to indicate that it was a successful request
wp_send_json_error()                // Same but for failed requests



==================================================
POST SUBMISSION HANDLERS
==================================================
https://premium.wpmudev.org/blog/handling-form-submissions/

REQUEST URL
admin_url( 'admin-post.php' )       // PHP function, returns the url where the post requests need to be sent

POST ACTIONS
admin_post                          // Submissions to admin-post.php with no 'action' (logged in users)
admin_post_nopriv                   // Submissions to admin-post.php with no 'action' (non logged in users)
admin_post_{$action}                // Only submissions for the requested 'action' (logged in users)
admin_post_nopriv_{$action}         // Only submissions for the requested 'action' (non logged in users)

RESPONSES
wp_die()                            // Kill the script if validation fails
wp_redirect(); exit                 // If validation passes, redirect users back to the relevant page
