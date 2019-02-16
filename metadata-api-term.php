<?php
/*
==================================================
METADATA API - CUSTOM TERM META
==================================================
https://codex.wordpress.org/Metadata_API

TERM META
add_term_meta()
delete_term_meta()
get_term_meta()
update_term_meta()

HELPER CLASS:
*/
class TermMetaField {
	
	
	// Properties
	// ------------------------------
	public $taxonomy;
	public $name;
	public $id;
	public $label;
	public $render_func;
	public $sanitize_func;
	public $value;
	
	
	// Constructor
	// ------------------------------
	public function __construct( $config=[] ) {
		
		// Store config settings
		$this->taxonomy      = $config['taxonomy'];
		$this->name          = $config['name'];
		$this->id            = $config['id'];
		$this->label         = $config['label'];
		$this->render_func   = $config['render_func'];
		$this->sanitize_func = $config['sanitize_func'];
		$this->value = '';
		
		// Render field
		add_action( $this->taxonomy.'_add_form_fields', [$this, 'render_new_field'] );
		add_action( $this->taxonomy.'_edit_form_fields', [$this, 'render_edit_field'], 10, 2 );
		
		// Save user data
		add_action( 'edited_'.$this->taxonomy, [$this, 'save_user_data'], 10, 2 );
		add_action( 'create_'.$this->taxonomy, [$this, 'save_user_data'], 10, 2 );
	}
	
	
	// Render field
	// ------------------------------
	public function render_new_field( $taxonomy ) {
		?>
		<div class="form-field term-<?php echo $this->id ?>-wrap">
			<label for="<?php echo $this->id ?>"><?php echo $this->label ?></label>
			<?php call_user_func( $this->render_func, $taxonomy, $this ) ?>
		</div>
		<?php
	}
	public function render_edit_field( $term, $taxonomy ) {
		$this->value = get_term_meta( $term->term_id, $this->name, true );
		?>
		<tr class="form-field term-<?php echo $this->id ?>-wrap">
			<th scope="row">
				<label for="<?php echo $this->id ?>"><?php echo $this->label ?></label>
			</th>
			<td>
				<?php call_user_func( $this->render_func, $taxonomy, $this ) ?>
			</td>
		</tr>
		<?php
	}
	
	
	// Save user data
	// ------------------------------
	public function save_user_data( $term_id, $term_taxonomy_id ) {
		$new_value = !empty( $_POST[ $this->name ] ) ? $_POST[ $this->name ] : '';
		$new_value = call_user_func( $this->sanitize_func, $new_value );
		if( $new_value ) {
			update_term_meta( $term_id, $this->name, $new_value );
		} else {
			delete_term_meta( $term_id, $this->name );
		}
	}
}
/*

SAMPLE USE:
*/
class TermMetaBackgroundColour {
	
	
	// Constructor
	// ------------------------------
	public function __construct() {
		new TermMetaField([
			'taxonomy'      => 'category',
			'name'          => 'background_color',
			'id'            => 'background_color',
			'label'         => 'Background Colour',
			'render_func'   => [$this, 'render_field'],
			'sanitize_func' => 'sanitize_title',
		]);
	}
	
	
	// Render fields
	// ------------------------------
	public function render_field( $taxonomy, $field ) {
		?>
		<input type="text" name="<?php echo $field->name ?>" id="<?php echo $field->id ?>" value="<?php echo $field->value ?>">
		<p class="description">Lorem ipsum dolor sit amet</p>
		<?php
	}
	
	
}
new TermMetaBackgroundColour();
