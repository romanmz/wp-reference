<?php
/*
==================================================
HTTP API
==================================================
https://codex.wordpress.org/HTTP_API
https://developer.wordpress.org/plugins/http-api/

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

RESPONSE CODES
2xx		Request was successful
3xx		Request was redirected to another URL
4xx		Request failed due to client error. Usually invalid authentication or missing data
5xx		Request failed due to a server error. Commonly missing or misconfigured configuration files
