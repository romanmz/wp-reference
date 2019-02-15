<?php
/*
==================================================
CUSTOM POST TYPES
==================================================
https://codex.wordpress.org/Custom_Post_Types
https://developer.wordpress.org/plugins/post-types/

REGISTRATION
register_post_type()
unregister_post_type()
add_post_type_support()
remove_post_type_support()

POST TYPES INFO
get_post_types()
get_post_types_by_support()
get_post_type_object()
post_type_exists()
is_post_type_hierarchical()
is_post_type_viewable()
get_all_post_type_supports()
post_type_supports()

INDIVIDUAL POSTS
get_post_type()
set_post_type()

TEMPLATE TAGS
is_post_type_archive()
post_type_archive_title()
get_the_post_type_description()

POST TYPE SETTINGS:
*/
add_action( 'init', 'register_movie_post_type', 0 );
function register_movie_post_type() {
	
	// Name and labels
	$post_type      = 'movie'; // max 20 characters, all lowercase and no spaces
	$singular_name  = 'Movie';
	$plural_name    = 'Movies';
	$singular_lc    = strtolower( $singular_name );
	$plural_lc      = strtolower( $plural_name );
	$url_slug       = 'movies';
	
	// Prepare labels
	$labels = [
		'name'                     => $plural_name,
		'singular_name'            => $singular_name,
		'add_new'                  => "Add New",
		'add_new_item'             => "Add New $singular_name",
		'edit_item'                => "Edit $singular_name",
		'new_item'                 => "New $singular_name",
		'view_item'                => "View $singular_name",
		'view_items'               => "View $plural_name",
		'search_items'             => "Search $plural_name",
		'not_found'                => "No $plural_lc found.",
		'not_found_in_trash'       => "No $plural_lc found in Trash.",
		'parent_item_colon'        => "Parent $singular_name:",
		'all_items'                => "All $plural_name",
		'archives'                 => "$singular_name Archives",
		'attributes'               => "$singular_name Attributes",
		'insert_into_item'         => "Insert into $singular_lc",
		'uploaded_to_this_item'    => "Uploaded to this $singular_lc",
		'featured_image'           => "Featured Image",
		'set_featured_image'       => "Set featured image",
		'remove_featured_image'    => "Remove featured image",
		'use_featured_image'       => "Use as featured image",
		'filter_items_list'        => "Filter $plural_lc list",
		'items_list_navigation'    => "$plural_name list navigation",
		'items_list'               => "$plural_name list",
		'item_published'           => "$singular_name published.",
		'item_published_privately' => "$singular_name published privately.",
		'item_reverted_to_draft'   => "$singular_name reverted to draft.",
		'item_scheduled'           => "$singular_name scheduled.",
		'item_updated'             => "$singular_name updated.",
		'name_admin_bar'           => $singular_name,
		'menu_name'                => $plural_name,
	];
	
	// Configure settings
	$args = [
		'labels'              => $labels,
		'description'         => '',
		'public'              => true,					// (bool) Sets some internal wp settings, and defines the default values for the following options. default == false
			'exclude_from_search'       => false,		// (bool) Hides the posts on the search AND taxonomy pages. default != $public
			'publicly_queryable'        => true,		// (bool) Shows the posts on the archives and single pages. default == $public
				'query_var'             => true,		// (bool|string) Enables using the post type name as url var to load a single post (?$post_type=$post_slug). default == $public
														//               also enables get_query_var( $post_type ) -> $post_slug
														//               NOTE: only works if publicly_queryable == true
														//               if you pass a string it will be used as the query_var instead of $post_type
			'show_in_nav_menus'         => true,		// (bool) Adds the list of posts for this post type in the menu editing screen. default == $public
			'show_ui'                   => true,		// (bool) Adds the admin section for this post type in the backend. default == $public
				'show_in_menu'          => true,		// (bool|string) Adds the links on the admin menu for this post type. default == $show_ui
														//               if you pass a string the links will be added as children of the passed section, e.g: 'edit.php?post_type=parent_type'
					'show_in_admin_bar' => true,		// (bool) Adds a link to create a new post of this type in top admin bar. default == $show_in_menu
					'menu_position'     => 20,			// (int)  Defines the position of the links in the admin menu. default == 25
														//        2 - below Dashboard
														//        4 - below Posts
														//        10 - below Media
														//        20 - below Pages
														//        25 - below Comments
														//        59 - below Appearance
														//        65 - below Plugins
														//        70 - below Users
														//        75 - below Tools
														//        80 - below Settings
		'menu_icon'           => 'dashicons-video-alt',	// (string) Adds a menu icon. Can be a base64-encoded SVG, a 'dashicon' name: https://developer.wordpress.org/resource/dashicons/, or 'none'
		'hierarchical'        => false,					// Enables parent-child relationships between posts. default == false
		'supports'            => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats'],
		'taxonomies'          => [],					// (array) Use 'category', 'post_tag', or custom taxonomies
		'has_archive'         => $url_slug,				// (bool|string) Enable the archives page using $post_type as the url slug, or pass a string to define a custom one. default == false
		'can_export'          => true,					// (bool) Allows posts of this type to be available on the export tool. default == true
		'delete_with_user'    => null,					// (bool|null) If true you'll have the option to delete or reassign posts of this type when deleting a user. default == null
														//             if false, then the posts will always remain
														//             if null, then the behaviour will depend on whether or not the post type supports 'author'
		// !! if you define custom capabilities remember to assign them to the administrator role
		'capability_type'     => ['movie', 'movies'],	// (string|array) Pass 'post' or 'page' to make this post type use the same capability rules as them. default == 'post'
														//                pass a custom string or an array with two strings to automatically build custom (basic) capability rules
		'map_meta_cap'        => true,					// (bool) If set to true, then the full set of capability rules will be created based on 'capability_type'. default == false
		'show_in_rest'        => true,					// (bool) Makes the post type available on the REST API (under the 'wp/v2' namespace). default = false
		'rest_base'           => $url_slug,				// (string) Defines the base url to access the post type on the REST API. default == $post_type
		'rewrite'             => [						// (bool|array)	Disable or enable rewrite rules (with their default values), or pass an array with custom settings. default == true
			'slug'            => $url_slug,				// (string) Url slug used for single posts. default == $post_type
			'with_front'      => true,					// (bool) Whether or not to prepend any custom permalink structure defined in the site settings. default == true
			'feeds'           => true,					// (bool) Whether or not to create an rss feed for this post type, e.g. '/$has_archive/feed/'. default == $has_archive
			'pages'           => true,					// (bool) Whether or not to enable pagination on archives pages, e.g. '/$has_archive/page/2/'. default == true
			// 'ep_mask'         => EP_PERMALINK,		// (const) ???
		],
		// 'register_meta_box_cb'  => null,				// (callable) Callback function to register custom meta boxes for this post type. default == null
		// 'capabilities'          => [],				// (array) Manually define the array of custom capabilities (instead of using capability_type and map_meta_cap)
		// 'rest_controller_class' => '',				// (string) REST API Controller class name. default == 'WP_REST_Posts_Controller'
	];
	register_post_type( $post_type, $args );
	
}
