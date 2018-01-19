<?php
/*
==================================================
HEARTBEAT API
==================================================
https://developer.wordpress.org/plugins/javascript/heartbeat-api/

JQUERY EVENTS
$(document).on( 'heartbeat-send', function(){} );
$(document).on( 'heartbeat-tick', function(){} );

FILTERS
heartbeat_received


EXAMPLE:
------------------------------
Checks the server for updates every 15-60 seconds via AJAX

1. Add extra fields to be sent to the server via the 'heartbeat-send' jquery event
jQuery(document).on( 'heartbeat-send', function(event, data){
    data.myplugin_customfield = 'some_data';
});

2. Check request in the server and add aditional response data with the 'heartbeat_received' WordPress filter
add_filter('heartbeat_received', 'myplugin_receive_heartbeat', 10, 2);
function myplugin_receive_heartbeat($response, $data) {
    
    // Do nothing if we didn't receive our new specific request
    if( empty( $data['myplugin_customfield'] ) ) {
        return $response;
    }
    
    // Prepare the response to our request and add it
    $received_data = $data['myplugin_customfield'];
    $response['myplugin_customfield_hashed'] = sha1( $received_data );
    return $response;
}

3. Process received data in the frontend via the 'heartbeat-tick' jquery event
jQuery(document).on( 'heartbeat-tick', function(event, data){
    if( !data.myplugin_customfield_hashed ) {
        return;
    }
    alert( 'The hash is '+data.myplugin_customfield_hashed );
});

*. You don't necessarily have to use all 3 steps, depends on what exactly you're trying to achieve
