<?php
/*
==================================================
HTTP API
==================================================
https://codex.wordpress.org/HTTP_API

PERFORM REQUESTS
wp_remote_get()                             // GET request
wp_remote_post()                            // POST request
wp_remote_head()                            // HEAD request (returns all data except the body)
wp_remote_request()                         // For custom requests (PUT, DELETE, etc…)

READ RESULTS
wp_remote_retrieve_body()                   // Retrieve just the body
wp_remote_retrieve_header()                 // Retrieve a single HTTP header
wp_remote_retrieve_headers()                // Retrieve all headers
wp_remote_retrieve_response_code()          // Retrieve the response code
wp_remote_retrieve_response_message()       // Retrieve the response message
