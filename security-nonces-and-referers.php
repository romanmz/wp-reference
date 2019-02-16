<?php
/*
==================================================
SECURITY - NONCES
==================================================
https://developer.wordpress.org/themes/theme-security/using-nonces/
https://developer.wordpress.org/plugins/security/nonces/

! WordPress nonces are only refreshed every 24 hours, if you need more serious security, implement a custom nonce system that actually creates a new nonce after every request

SIMPLE CREATION AND VERIFICATION
wp_create_nonce( $nonce_action=-1 ) -> $nonce_value     // For better securtiy, the action name should be as specific as possible, for example to delete the post with id of '5': 'delete-post-5'
wp_verify_nonce( $nonce_value, $nonce_action=-1 )       // Verifies the received nonce. Returns 0 if it's invalid, 1 if it's valid and 0-12 hours old, and '2' if it's valid and 12-24 hours old

SHORTCUTS
wp_nonce_url(                                           // Creates a nonce and appends it to the provided url
	$url,
	$nonce_action = -1,
	$field_name = '_wpnonce'
)
wp_nonce_field(                                         // Creates a nonce and generates and outputs a hidden <input> field with its value
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
check_admin_referer(                                    // Succeeds if the nonce is valid, or if it fails but it's a generic action (-1) that's coming from the site's own admin section
	$nonce_action = -1,
	$field_name = '_wpnonce'
)



==================================================
SECURITY - REFERERS
==================================================

SIMPLE CREATION AND VERIFICATION
wp_referer_field( $echo=true )                          // Outputs a hidden <input> with the url of the current page named as '_wp_http_referer'
wp_get_raw_referer()                                    // Checks and returns the referer from a request's '_wp_http_referer' key if it exists, otherwise it checks the $_SERVER data
wp_get_referer()                                        // Same but succeeds only if the URL is from a white-listed host, and if it's not the same as the current page, otherwise returns 'false'

WHITELISTED SAFE HOSTS
wp_validate_redirect( $url, $default='' )               // Checks if a url belongs to our white-listed set of hosts, returns $default if it's not
filter: allowed_redirect_hosts                          // Edits the list of white-listed hosts for redirections

REDIRECTING USERS
wp_redirect( $location, $code=302 )                     // Redirects to any new location
wp_safe_redirect( $location, $code=302 )                // Redirects to a new location, but only if it's from the white-listed hosts
