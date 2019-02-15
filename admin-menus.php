<?php
/*
==================================================
ADMIN MENUS
==================================================
https://codex.wordpress.org/Administration_Menus
https://developer.wordpress.org/themes/functionality/administration-menus/
https://developer.wordpress.org/plugins/administration-menus/

TOP LEVEL PAGES
add_menu_page(
	$page_title,
	$menu_title,
	$capability,
	$page_id,
	$function = '',
	$icon_url = '',
	$position = null
)
remove_menu_page(
	$page_id
)

SUBPAGES
add_submenu_page(
	$parent_id,
	$page_title,
	$menu_title,
	$capability,
	$page_id,
	$function = ''
)
remove_submenu_page(
	$parent_id,
	$page_id
)

DEFAULT PAGES
add_dashboard_page()
add_posts_page()
add_media_page()
add_links_page()
add_pages_page()
add_comments_page()
add_theme_page()
add_plugins_page()
add_users_page()
add_management_page()
add_options_page()
