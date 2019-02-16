<?php
/*
==================================================
REWRITE API [pending]
==================================================
https://codex.wordpress.org/Rewrite_API

FUNCTIONS
add_rewrite_tag()
add_rewrite_rule()
add_rewrite_endpoint()
add_permastruct()
add_feed()
flush_rules()
flush_rewrite_rules()
generate_rewrite_rules()

FILTERS
root_rewrite_rules
post_rewrite_rules
page_rewrite_rules
date_rewrite_rules
search_rewrite_rules
comments_rewrite_rules
author_rewrite_rules
rewrite_rules_array
{$permastruct}_rewrite_rules

ACTIONS
generate_rewrite_rules

ENDPOINT MASKS
EP_NONE
EP_PERMALINK		On permalinks (doesn't include pages)
EP_ATTACHMENT		On post attachments
EP_ROOT				On front page
EP_COMMENTS			On comments pagination
EP_SEARCH			On search results
EP_PAGES			On pages
EP_DATE				On date archives
EP_YEAR				On year archives
EP_MONTH			On month archives
EP_DAY				On day archives
EP_CATEGORIES		On category archives
EP_TAGS				On tag archives
EP_AUTHORS			On author archives
EP_ALL_ARCHIVES		On all archives. equals = EP_DATE | EP_YEAR | EP_MONTH | EP_DAY | EP_CATEGORIES | EP_TAGS | EP_AUTHORS
EP_ALL				Everywhere. equals = EP_PERMALINK | EP_ATTACHMENT | EP_ROOT | EP_COMMENTS | EP_SEARCH | EP_PAGES | EP_ALL_ARCHIVES

EXAMPLES:
*/


// ENDPOINTS EXAMPLE: Registering a new endpoint
// ------------------------------

// 1. Register new endpoint, its info will become available as a query var, you can customize the var name or disable it with the 3rd argument
add_action( 'init', 'register_new_endpoint' );
function register_new_endpoint() {
	add_rewrite_endpoint( 'thank-you', EP_PERMALINK | EP_PAGES, 'thanks' );
}

// x. Detect the endpoint and use it as a query var
add_filter( 'the_content', 'detect_new_endpoint' );
function detect_new_endpoint( $content ) {
	global $wp_query;
	$is_in_endpoint = isset( $wp_query->query_vars['thanks'] );
	$endpoint_value = get_query_var( 'thanks' );
	return $content;
}


// RULES AND QUERY VAR EXAMPLES: Manually creating an endpoint for a custom post type archives
// ------------------------------

// 1. Register new query var
add_filter( 'query_vars', 'archives_endpoint_query_var' );
function archives_endpoint_query_var( $vars ) {
	$vars[] = 'company';
	return $vars;
}

// 2. Register new rewrite rule
add_action( 'init', 'archives_endpoint_rewrite_rule' );
function archives_endpoint_rewrite_rule() {
	add_rewrite_rule( 'movies/companies/([^/]+)/?$', 'index.php?post_type=movie&company=$matches[1]', 'top' );
}

// x. Select another template file for the endpoint
add_filter( 'template_include', 'archives_endpoint_select_template' );
function archives_endpoint_select_template( $template ) {
	if( get_query_var( 'post_type' ) == 'movie' && get_query_var( 'company' ) ) {
		return get_template_directory().'/index.php';
	}
	return $template;
}

// x. Filter list of posts
add_action( 'pre_get_posts', 'archives_endpoint_filter_posts' );
function archives_endpoint_filter_posts( $query ) {
	if( !$query->is_main_query() ) return;
	if( !$query->is_post_type_archive( 'movie' ) ) return;
	$company_id = absint( get_query_var( 'company' ) );
	if( $company_id ) {
		$query->set( 'author', $company_id );
	}
}


// INSPECTING THE RULES ARRAY
// ------------------------------
add_filter( 'rewrite_rules_array', 'check_rewrite_rules_array', 100 );
function check_rewrite_rules_array( $rules ) {
	global $wp_rewrite;
	/*
	FILTERING RULES:
	Post type:		post_type=$post_type || $post_type=
	Feeds:			feed=
	Embeds:			embed=true
	Paged:			paged=
	Comments:		cpage=
	REST:			rest_route=
	Trackback:		tb=1
	Pages:			&page=
	*/
	return $rules;
}
