<?php
/*
==================================================
WP MAIL / PHPMAILER
==================================================
https://github.com/PHPMailer/PHPMailer/

FUNCTIONS
wp_mail( $to, $subject, $message, $headers, $attachments )

FILTERS
wp_mail
wp_mail_from
wp_mail_from_name
wp_mail_content_type
wp_mail_charset

ACTIONS
phpmailer_init
wp_mail_failed

EXAMPLES:
*/


// Enable SMTP
// ------------------------------
add_action( 'phpmailer_init', 'phpmailer_enable_smtp' );
function phpmailer_enable_smtp( $phpmailer ) {
	
	// Basics
	$phpmailer->isSMTP();
	$phpmailer->SMTPDebug = 2;
	$phpmailer->Host = 'senderhost.com';
	$phpmailer->Port = 25;
	$phpmailer->SMTPSecure = 'ssl';
	
	// Authentication
	$phpmailer->SMTPAuth = true;
	$phpmailer->Username = 'sender@email.com';
	$phpmailer->Password = 'senderpassword';
	
	// Sender info
	$phpmailer->From = "you@yourdomail.com";
	$phpmailer->FromName = "Your Name";
	
}


// Catch errors for debugging
// ------------------------------
add_action( 'wp_mail_failed', 'wp_mail_catch_errors' );
function wp_mail_catch_errors( $wp_error ) {
	// print_r( $wp_error );
	// die();
}
