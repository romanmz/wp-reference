<?php
/*
==================================================
CUSTOM TAXONOMIES
==================================================
https://codex.wordpress.org/Taxonomies

REGISTRATION
register_taxonomy()
unregister_taxonomy()
register_taxonomy_for_object_type()
unregister_taxonomy_for_object_type()

TAXONOMIES INFO
get_taxonomies()
get_taxonomy()
taxonomy_exists()
is_taxonomy_hierarchical()
wp_count_terms()

TERMS INFO
get_terms()
get_term()
get_term_by()
term_exists()
get_ancestors()
get_term_children()
term_is_ancestor_of()
get_term_field()
get_term_link()

TERM META
register_term_meta()
unregister_term_meta()
add_term_meta()
get_term_meta()
update_term_meta()
delete_term_meta()

TERM PROGRAMMATIC
wp_insert_term()
wp_update_term()
wp_delete_term()
wp_set_object_terms()
wp_add_object_terms()
wp_remove_object_terms()
wp_delete_object_term_relationships()

POST_TYPE > TAXONOMIES
# get_object_taxonomies()
get_post_taxonomies()
get_attachment_taxonomies()
is_object_in_taxonomy()

POST > TERMS
# is_object_in_term()
has_term()
# wp_get_object_terms()
get_the_terms()

TERM > POSTS
get_objects_in_term()

TEMPLATE TAGS
is_tax()
term_description()
get_the_taxonomies()
the_taxonomies()
get_the_term_list()
the_terms()

TAXONOMY SETTINGS:
*/
add_action( 'init', 'register_genre_taxonomy', 0 );
function register_genre_taxonomy() {
	
	// Name and labels
	$taxonomy       = 'genre'; // max 32 characters, all lowercase and no spaces
	$singular_name  = 'Genre';
	$plural_name    = 'Genres';
	$plural_lc      = strtolower( $plural_name );
	$url_slug       = 'genres';
	
	// Prepare labels
	$labels = [
		'name'                       => $plural_name,
		'singular_name'              => $singular_name,
		'search_items'               => "Search {$plural_name}",
		'popular_items'              => "Popular {$plural_name}",
		'all_items'                  => "All {$plural_name}",
		'parent_item'                => "Parent {$singular_name}",
		'parent_item_colon'          => "Parent {$singular_name}:",
		'edit_item'                  => "Edit {$singular_name}",
		'view_item'                  => "View {$singular_name}",
		'update_item'                => "Update {$singular_name}",
		'add_new_item'               => "Add New {$singular_name}",
		'new_item_name'              => "New {$singular_name} Name",
		'separate_items_with_commas' => "Separate {$plural_lc} with commas",
		'add_or_remove_items'        => "Add or remove {$plural_lc}",
		'choose_from_most_used'      => "Choose from the most used {$plural_lc}",
		'not_found'                  => "No {$plural_lc} found.",
		'no_terms'                   => "No {$plural_lc}",
		'items_list_navigation'      => "$plural_name list navigation",
		'items_list'                 => "$plural_name list",
		'most_used'                  => "Most Used",
		'back_to_items'              => "&larr; Back to $plural_name",
		'menu_name'                  => $plural_name,
	];
	
	// Configure settings
	$args = [
		'labels'              => $labels,
		'description'         => '',
		'public'              => true,					// (bool) Sets some internal wp settings, and defines the default values for the following options. default == true
			'publicly_queryable'        => true,		// (bool) Enables the taxonomy term archives pages. default == $public
				'query_var'             => true,		// (bool|string) Enables using the taxonomy name as url var to load a single term archives page (?$taxonomy=$term_slug). default == $taxonomy
														//               also enables get_query_var( $taxonomy ) -> $term_slug
														//               NOTE: only works if publicly_queryable == true
														//               if you pass a string it will be used as the query_var instead of $taxonomy
			'show_in_nav_menus'         => true,		// (bool) Adds the list of terms for this taxonomy in the menu editing screen. default == $public
			'show_ui'                   => true,		// (bool) Adds the admin section for this taxonomy in the backend. default == $public
				'show_in_menu'          => true,		// (bool) Adds links to this taxonomy on the admin menu under each relevant post type. default == $show_ui
				'show_tagcloud'         => true,		// (bool) Makes the taxonomy available in the Tag Cloud Widget. default == $show_ui
				'show_in_quick_edit'    => true,		// (bool) Makes the taxonomy available in the quick/bulk edit panels. default == $show_ui
		'show_admin_column'   => true,					// (bool) Adds a column for the taxonomy on the admin tables for the relevant post types. default == false
		'hierarchical'        => true,					// Enables parent-child relationships between taxonomy terms. default == false
		'capabilities'        => [						// (array) Defines the capabilities rules for this taxonomy
			'manage_terms' => 'manage_genres',			// (string) Capability type to grant access to the taxonomy terms edit screen. default == 'manage_categories'
			'edit_terms'   => 'edit_genres',			// (string) Capability type to allow creating and editing taxonomy terms. default == 'manage_categories'
			'delete_terms' => 'delete_genres',			// (string) Capability type to allow deleting existing taxonomy terms. default == 'manage_categories'
			'assign_terms' => 'assign_genres',			// (string) Capability type to allow assigning taxonomy terms to posts. default == 'edit_posts'
		],
		'show_in_rest'        => true,					// (bool) Makes the taxonomy available on the REST API (under the 'wp/v2' namespace). default == false
		'rest_base'           => $url_slug,				// (string) Defines the base url to access the taxonomy on the REST API. default == $taxonomy
		'rewrite'             => [						// (bool|array)	Disable or enable rewrite rules (with their default values), or pass an array with custom settings. default == true
			'slug'            => $url_slug,				// (string) Url slug used for taxonomy term archives page. default == $taxonomy
			'with_front'      => true,					// (bool) Whether or not to prepend any custom permalink structure defined in the site settings. default == true
			'hierarchical'    => false,					// (bool) Whether or not to add the parent taxonomies to the url of child term archives pages. default == false
			// 'ep_mask'         => EP_NONE,			// (const) ???
		],
		// 'update_count_callback' => null,				// (callable) Callback function that runs every time the term count is updated. default == null
		// 'meta_box_cb'           => null,				// (callable) Callback function to override the default meta box for this taxonomy. default == null
		// 'rest_controller_class' => '',				// (string) REST API Controller class name. default == 'WP_REST_Terms_Controller'
	];
	register_taxonomy( $taxonomy, ['post'], $args );
	
}
