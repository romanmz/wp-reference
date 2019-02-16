<?php
/*
==================================================
META BOXES
==================================================
https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/

FUNCTIONS
add_meta_box(
	$metabox_id,			// (*string)
	$metabox_title,			// (*string)
	$metabox_render_func,	// (*callable) Echoes the contents of the metabox
	$screen_ids,			// (string|WP_Screen|array) The screens that will show this metabox. 'post_type', 'link', 'comment', $screen_id, WP_Screen. default == null (current screen)
	$metabox_context,		// (string) Location within the screen to use for this metabox. 'normal', 'side', 'advanced'. default == 'advanced'
	$metabox_priority,		// (string) Render priority within the context. 'default', 'high', 'low'. default == 'default'
	$metabox_render_args	// (array) Extra settings to be passed to the callback function (as the 2nd argument)
)
remove_meta_box(
	$metabox_id,
	$screen_ids,
	$metabox_context
)

FILTERS
add_meta_boxes
add_meta_boxes_{$post_type}

HELPER CLASS:
*/
class MetaBoxHelper {
	
	
	// Properties
	// ------------------------------
	public $metabox_id;
	public $metabox_label;
	public $screen_ids;
	public $metabox_context;
	public $metabox_priority;
	private $nonce_action;
	private $nonce_field;
	public $fields;
	
	
	// Constructor
	// ------------------------------
	public function __construct( $config=[] ) {
		
		// Store config settings
		$this->metabox_id       = $config['metabox_id'];
		$this->metabox_label    = $config['metabox_label'];
		$this->screen_ids       = (array) $config['screen_ids'];
		$this->metabox_context  = $config['metabox_context'];
		$this->metabox_priority = $config['metabox_priority'];
		
		// Extra settings
		$this->nonce_action = 'metabox_'.$this->metabox_id;
		$this->nonce_field  = 'metabox_'.$this->metabox_id.'_nonce';
		
		// Register hooks
		add_action( 'add_meta_boxes', [$this, 'register_meta_box'] );
		add_action( 'save_post', [$this, 'save_post_data'] );
	}
	
	
	// Register fields
	// ------------------------------
	public function add_field( $field_config=[] ) {
		$this->fields[] = (object) $field_config;
	}
	
	
	// Register and render meta box
	// ------------------------------
	public function register_meta_box( $post ) {
		if( empty( $this->fields ) ) return;
		add_meta_box( $this->metabox_id, $this->metabox_label, [$this, 'render_meta_box'], $this->screen_ids, $this->metabox_context, $this->metabox_priority );
	}
	public function render_meta_box( $post, $args=[] ) {
		wp_nonce_field( $this->nonce_action, $this->nonce_field, true, true );
		foreach( $this->fields as $field ) {
			echo '<div class="field-box '.$field->id.'-box">';
			call_user_func( $field->render_func, $post, $field );
			echo '</div>';
		}
	}
	
	
	// Save post data
	// ------------------------------
	public function save_post_data( $post_id ) {
		
		// Validate request
		if( empty( $this->fields ) ) return;
		if( !current_user_can( 'edit_post', $post_id ) ) return;
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if( !isset( $_POST[ $this->nonce_field ] ) ) return;
		if( !wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce_action ) ) return;
		
		// Save data
		foreach( $this->fields as $field ) {
			call_user_func( $field->save_func, $post_id, $field );
		}
	}
}
/*

SAMPLE USE:
*/
class MetaBoxSocialMedia {
	
	
	// Construct
	// ------------------------------
	public function __construct() {
		$metabox = new MetaBoxHelper([
			// required
			'metabox_id' => 'social_media',
			'metabox_label' => 'Social Media',
			'screen_ids' => ['location', 'page'],
			'metabox_context' => 'side',
			'metabox_priority' => 'high',
		]);
		
		// Add twitter field
		$metabox->add_field([
			// required
			'id' => 'twitter_handle',
			'render_func' => [$this, 'render_field'],
			'save_func' => [$this, 'save_field'],
			// optional
			'name' => 'twitter_handle',
			'label' => 'Twitter Handle',
		]);
		
		// Add instagram field
		$metabox->add_field([
			'id' => 'instagram_handle',
			'render_func' => [$this, 'render_field'],
			'save_func' => [$this, 'save_field'],
			// optional
			'name' => 'instagram_handle',
			'label' => 'Instagram Handle',
		]);
	}
	
	
	// Callbacks
	// ------------------------------
	public function render_field( $post, $field ) {
		$field_value = get_post_meta( $post->ID, $field->name, true );
		printf( '
			<label for="%3$s">%2$s</label>
			<input type="text" id="%3$s" name="%1$s" value="%4$s" size="25">',
			$field->name,
			$field->label,
			$field->id,
			esc_attr( $field_value )
		);
	}
	public function save_field( $post_id, $field ) {
		$new_value = !empty( $_POST[ $field->name ] ) ? $_POST[ $field->name ] : '';
		$new_value = sanitize_title( $new_value );
		if( $new_value ) {
			update_post_meta( $post_id, $field->name, $new_value );
		} else {
			delete_post_meta( $post_id, $field->name );
		}
	}
	
	
}
new MetaBoxSocialMedia();
