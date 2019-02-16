<?php
/*
==================================================
SETTINGS API
==================================================
https://codex.wordpress.org/Settings_API
https://developer.wordpress.org/plugins/settings/settings-api/

REGISTER SETTINGS
register_setting(
	$page_name,						// built-in settings pages like 'general', 'reading', 'writing', etc…, or a custom page slug returned from add_options_page()
	$option_name,					// name of the option in the database
	$args = [
		'type'              => (string),
		'description'       => (string),
		'sanitize_callback' => (callable),
		'show_in_rest'      => (bool)
		'default'           => (mixed),
	]
)
unregister_setting(
	$page_name,
	$option_name
)

ADD SECTIONS AND FIELDS
add_settings_section(
	$section_name,
	$section_label,
	$section_callback,
	$page_name
)
add_settings_field(
	$field_name,
	$field_label,
	$field_callback,
	$page_name,
	$section_name = 'default',
	$args = [
		'label_for' => (string),
		'class'     => (string),
	]
)

FORM RENDERING
settings_fields(
	$page_name
)
do_settings_sections(
	$page_name
)
do_settings_fields(
	$page_name,
	$section_name
)
submit_button(
	$text = 'Save Changes',
	$type = 'primary',					// 'primary'|'small'|'large'|'other'
	$name = 'submit',
	$wrap = true,
	$other_attributes = []
)

ERRORS
add_settings_error(
	$option_name,
	$error_name,
	$error_message,
	$error_type = 'error'				// 'error'|'updated'|'notice-info'
)
get_settings_errors(
	$option_name = '',
	$sanitize = false
)
settings_errors(
	$option_name = '',
	$sanitize = false,
	$hide_on_update = false
)
