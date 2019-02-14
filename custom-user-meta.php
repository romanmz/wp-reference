<?php
/*
==================================================
CUSTOM USER META
==================================================
https://developer.wordpress.org/plugins/users/working-with-user-metadata/

HELPER CLASS:
*/
class UserMetaSection {
	
	
	// Properties
	// ------------------------------
	public $section_id;
	public $section_label;
	public $fields = [];
	
	
	// Constructor
	// ------------------------------
	public function __construct( $config=[] ) {
		
		// Store config settings
		$this->section_id    = $config['section_id'];
		$this->section_label = $config['section_label'];
		$render_func = is_callable( $config['section_render_func'] ) ? $config['section_render_func'] : [$this, 'render_section'];
		
		// Render section
		add_action( 'user_new_form', $render_func );     // when creating a new user
		add_action( 'edit_user_profile', $render_func ); // when editing own profile
		add_action( 'show_user_profile', $render_func ); // when editing someone else's profile
		
		// Save user data
		add_action( 'user_register', [$this, 'save_user_data'] );            // when creating a new user
		add_action( 'personal_options_update', [$this, 'save_user_data'] );  // when editing own profile
		add_action( 'edit_user_profile_update', [$this, 'save_user_data'] ); // when editing someone else's profile
	}
	
	
	// Register meta fields
	// ------------------------------
	public function add_field( $config=[] ) {
		
		// Exit early if callbacks are not valid
		if( !is_callable( $config['label_render_func'] ) ) return;
		if( !is_callable( $config['field_render_func'] ) ) return;
		if( !is_callable( $config['field_save_func'] ) ) return;
		
		// Add field
		$field = new stdClass;
		$field->render_label = $config['label_render_func'];
		$field->render_field = $config['field_render_func'];
		$field->save_data = $config['field_save_func'];
		$this->fields[] = $field;
	}
	
	
	// Render section
	// ------------------------------
	public function render_section( $user=false ) {
		
		// Exit early if there are no fields to display
		if( empty( $this->fields ) ) return;
		
		// Output label
		if( $this->section_label ) {
			printf( '<h2>%s</h2>', $this->section_label );
		}
		
		// Output section
		?>
		<table class="form-table" id="<?php echo $this->section_id ?>">
			<?php foreach( $this->fields as $field ) : ?>
				<tr class="form-field">
					<th scope="row">
						<?php call_user_func( $field->render_label, $user ) ?>
					</th>
					<td>
						<?php call_user_func( $field->render_field, $user ) ?>
					</td>
				</tr>
			<?php endforeach ?>
		</table>
		<?php
	}
	
	
	// Save user data
	// ------------------------------
	public function save_user_data( $user_id ) {
		if( !current_user_can( 'edit_user', $user_id ) ) {
			return;
		}
		foreach( $this->fields as $field ) {
			call_user_func( $field->save_data, $user_id );
		}
	}
}
/*

SAMPLE USE:
*/
class UserMetaSectionSocialMedia {
	
	
	// Settings
	// ------------------------------
	private $section_id    = 'social-media';
	private $section_label = 'Social Media';
	private $field_meta    = 'instagram_handle';
	private $field_name    = 'instagram_handle';
	private $field_id      = 'instagram_handle';
	private $field_label   = 'Instagram Handle';
	
	
	// Register section and fields
	// ------------------------------
	public function __construct() {
		$section = new UserMetaSection([
			'section_id'    => $this->section_id,
			'section_label' => $this->section_label,
			'section_render_func' => false,
		]);
		$section->add_field([
			'label_render_func' => [$this, 'render_label'],
			'field_render_func' => [$this, 'render_field'],
			'field_save_func'   => [$this, 'save_field'],
		]);
	}
	
	
	// Callbacks
	// ------------------------------
	public function render_label() {
		?>
		<label for="<?php echo $this->field_id ?>"><?php echo $this->field_label ?></label>
		<?php
	}
	public function render_field( $user=false ) {
		$current_value = is_object( $user ) ? get_user_meta( $user->ID, $this->field_meta, true ) : false;
		?>
		<input type="text" name="<?php echo $this->field_name ?>" id="<?php echo $this->field_id ?>" value="<?php esc_attr_e( $current_value ) ?>" class="regular-text">
		<p class="description">Lorem ipsum dolor sit amet.</p>
		<?php
	}
	public function save_field( $user_id ) {
		$field_value = !empty( $_POST[ $this->field_name ] ) ? $_POST[ $this->field_name ] : '';
		$field_value = sanitize_text_field( $field_value );
		update_user_meta( $user_id, $this->field_meta, $field_value );
	}
	
	
}
new UserMetaSectionSocialMedia();
