<?php
/*
==================================================
SETTINGS API
==================================================
https://codex.wordpress.org/Settings_API

REGISTER SETTINGS
register_setting(
	$option_group,						// ! the documentation refers to this variable as 'option_group', but I'm 99% sure it's actually supposed to be the $page_id
	$option_id,
	$args = [
		'type' => '',					// ???
		'description' => '',			// ???
		'sanitize_callback' => '',
		'show_in_rest' => '',			// ???
		'default' => '',
	]
)
unregister_setting(
	$option_group,
	$option_id
)

ADD SECTIONS AND FIELDS
add_settings_section(
	$section_id,
	$section_title,
	$callback,
	$page_id
)
add_settings_field(
	$field_id,
	$field_title,
	$callback,
	$page_id,
	$section_id = 'default',
	$args = [
		'label_for' => '',
		'class' => '',
	]
)

FORM RENDERING
settings_fields(
	$option_group
)
do_settings_sections(
	$page_id
)
do_settings_fields(
	$page_id,
	$section_id
)
submit_button(
	$text = 'Save Changes',
	$type = 'primary',					// 'primary'|'small'|'large'
	$name = 'submit',
	$wrap = true,
	$other_attributes = null
)

ERRORS
add_settings_error(
	$option_id,
	$code,
	$message,
	$type = 'error'
)
get_settings_errors(
	$option_id = '',
	$sanitize = false
)
settings_errors(
	$option_id = '',
	$sanitize = false,
	$hide_on_update = false
)
