<?php
/*
==================================================
ADMIN TABLES
==================================================
https://make.wordpress.org/docs/plugin-developer-handbook/10-plugin-components/custom-list-table-columns/

HELPER CLASS:
*/
class AdminTableColumn {
	
	
	// Properties
	// ------------------------------
	public $post_type;
	public $column_name;
	public $column_label;
	public $column_render_func;
	public $nonce_action;
	public $nonce_field;
	public $quickedit_render_func;
	public $quickedit_save_func;
	public $bulkedit_action;
	
	
	// Constructor
	// ------------------------------
	public function __construct( $config=[] ) {
		
		// Prepare settings
		$this->post_type    = $config['post_type'];
		$this->column_name  = $config['column_name'];
		$this->column_label = $config['column_label'];
		$this->column_render_func = $config['column_render_func'];			// comes first in the markup
		$this->nonce_action = 'quickedit_'.$this->column_name;
		$this->nonce_field = $this->column_name.'_nonce';
		$this->quickedit_render_func = $config['quickedit_render_func'];	// comes later in the markup
		$this->quickedit_save_func = $config['quickedit_save_func'];
		$this->bulkedit_action = 'bulkedit-'.$this->post_type.'-'.$this->column_name;
		
		// Register hooks
		// column
		add_filter( 'manage_'.$this->post_type.'_posts_columns', [$this, 'register_column'] );
		add_action( 'manage_'.$this->post_type.'_posts_custom_column', [$this, 'output_column'], 10, 2 );
		// quick edit
		add_action( 'admin_enqueue_scripts', [$this, 'load_admin_script'] );
		add_action( 'quick_edit_custom_box', [$this, 'output_quick_edit_box'], 10, 2 );
		add_action( 'save_post', [$this, 'save_data'] );
		// bulk edit
		add_action( 'bulk_edit_custom_box', [$this, 'output_quick_edit_box'], 10, 2 );
		add_action( 'wp_ajax_'.$this->bulkedit_action, [$this, 'save_bulk_data'] );
	}
	
	
	// Display table column
	// ------------------------------
	public function register_column( $columns ) {
		$columns[ $this->column_name ] = $this->column_label;
		return $columns;
	}
	public function output_column( $column_name, $post_id ) {
		if( $column_name !== $this->column_name || !is_callable( $this->column_render_func ) ) {
			return;
		}
		call_user_func( $this->column_render_func, $column_name, $post_id );
	}
	
	
	// Load javascript for quick/bulk edit
	// ------------------------------
	public function load_admin_script( $hook ) {
		if( $hook !== 'edit.php' || get_query_var( 'post_type' ) !== $this->post_type ) {
			return;
		}
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'inline-edit-post' );
	}
	public function output_js() {
		
		// Output only once
		static $already_displayed = false;
		if( $already_displayed ) {
			return;
		}
		$already_displayed = true;
		
		?>
		<script>
			jQuery(document).ready(function($){
				
				// Quick edit box
				var editFunc = inlineEditPost.edit;
				inlineEditPost.edit = function( id ) {
					editFunc.apply( this, arguments );
					
					// get id of post being edited
					let postId = typeof( id ) == 'object' ? parseInt( this.getId( id ) ) : 0;
					if( postId < 1 ) return;
					let postRow = $('#post-'+postId);
					let editRow = $('#edit-'+postId);
					
					// loop through table columns with attached field data
					postRow.find('[data-quickedit-field][data-quickedit-value]').each(function(){
						let field_name = $(this).data( 'quickedit-field' );
						let field_value = $(this).data( 'quickedit-value' );
						
						// select the form field that is linked to the column data
						let form_field = editRow.find( ':input, meter, progress, output' ).filter( '[name="'+field_name+'"]' );
						if( !form_field.length ) return;
						
						// fill form field with the current table column data
						if( Array.isArray( field_value ) && form_field.is( 'select' ) ) {
							$.each( field_value, function(i, val){
								form_field.find( 'option[value="'+val+'"]' ).prop( 'selected', true );
							});
						} else if( form_field.is( ':checkbox, :radio' ) && !Array.isArray( field_value ) ) {
							// need to be exact match?
							form_field.filter( '[value="'+field_value+'"]' ).prop( 'checked', true );
						} else {
							form_field.val( field_value );
						}
					});
				};
				
				// Bulk edit box
				$(document).on( 'click', '#bulk_edit', function(e){
					let editRow = $('#bulk-edit');
					
					// get ids of posts being edited
					let postIds = editRow.find( '#bulk-titles' ).children().map(function(){ return $(this).attr('id').replace(/^(ttle)/i, '') }).get();
					
					// prepare data to be sent with ajax
					let ajaxData = editRow.find(':input, meter, progress, output').serializeArray();
					ajaxData.push({name: 'post_ids', value: postIds});
					ajaxData.push({name: 'is_bulk_edit', value: true});
					
					// send ajax request
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						async: false,
						cache: false,
						data: ajaxData,
						complete: function( response, status ) {},
					});
				});
				
			});
		</script>
		<?php
	}
	
	
	// Display quick edit box
	// ------------------------------
	public function output_quick_edit_box( $column_name, $post_type ) {
		
		// Guard conditions
		if( $post_type !== $this->post_type || $column_name !== $this->column_name ) {
			return;
		}
		if( !is_callable( $this->quickedit_render_func ) ) {
			return;
		}
		
		// Output js
		$this->output_js();
		
		// Add action name for bulk edit ajax
		if( current_action() === 'bulk_edit_custom_box' ) {
			?>
			<input type="hidden" name="action" value="<?php echo $this->bulkedit_action ?>">
			<?php
		}
		
		// Output markup
		?>
		<fieldset class="inline-edit-col-right inline-edit--type-<?php echo $this->post_type ?> inline-edit--column-<?php echo $this->column_name ?>">
			<div class="inline-edit-col">
				<div class="inline-edit-group wp-clearfix">
					<?php wp_nonce_field( $this->nonce_action, $this->nonce_field ) ?>
					<label>
						<span class="title" style="line-height: 1.5"><?php echo $this->column_label ?></span>
						<span class="input-text-wrap">
							<?php call_user_func( $this->quickedit_render_func, $column_name, $post_type ) ?>
						</span>
					</label>
				</div>
			</div>
		</fieldset>
		<?php
	}
	
	
	// Save data (quick edit)
	// ------------------------------
	public function save_data( $post_id, $is_bulk_edit=false ) {
		
		// Check post type, post meta, and permissions
		if(
			get_post_type( $post_id ) !== $this->post_type
			|| !current_user_can( 'edit_post', $post_id )
		) {
			return;
		}
		
		// Verify nonce
		$nonce_received = isset( $_POST[ $this->nonce_field ] ) ? $_POST[ $this->nonce_field ] : false;
		if( !wp_verify_nonce( $nonce_received, $this->nonce_action ) ) {
			return;
		}
		
		// Save data
		if( is_callable( $this->quickedit_save_func ) ) {
			call_user_func( $this->quickedit_save_func, $post_id, $is_bulk_edit );
		}
	}
	
	
	// Save data (bulk edit)
	// ------------------------------
	public function save_bulk_data() {
		
		// Check received data
		if( empty( $_POST['post_ids'] ) ) {
			wp_send_json_error( ['message'=>'No post ids received.'] );
		}
		
		// Validate array of integers
		$post_ids = $_POST['post_ids'];
		if( !is_array( $post_ids ) ) {
			$post_ids = explode( ",", $post_ids );
		}
		array_walk( $post_ids, 'absint' );
		$post_ids = array_filter( $post_ids );
		if( empty( $post_ids ) ) {
			wp_send_json_error( ['message'=>'Invalid post ids received.'] );
		}
		
		// Trigger individual action for each post id
		$is_bulk_edit = !empty( $_POST['is_bulk_edit'] );
		foreach( $post_ids as $post_id ) {
			$this->save_data( $post_id, $is_bulk_edit );
		}
		wp_send_json_success( ['message'=>count( $post_ids ).' post(s) updated'] );
	}
}
/*

SAMPLE USE:
*/
class AdminTableColumnPostRating {
	
	
	// Helper properties and methods
	// ------------------------------
	private $field_meta = 'rating';
	private $field_name = 'post_rating';
	
	private function sanitize_rating( $number ) {
		$number = max( 0, min( 5, absint( $number ) ) );
		return $number ? $number : '';
	}
	private function get_post_rating( $post_id ) {
		return $this->sanitize_rating( get_post_meta( $post_id, $this->field_meta, true ) );
	}
	
	
	// Register table column
	// ------------------------------
	public function __construct() {
		new AdminTableColumn([
			'post_type'    => 'post',
			'column_name'  => 'rating',
			'column_label' => 'Rating',
			'column_render_func'    => [$this, 'render_column'],
			'quickedit_render_func' => [$this, 'render_quickedit_field'],
			'quickedit_save_func'   => [$this, 'quickedit_save'],
		]);
	}
	
	
	// Callbacks
	// ------------------------------
	public function render_column( $column_name, $post_id ) {
		$rating = $this->get_post_rating( $post_id );
		// 'data-quickedit-field' and 'data-quickedit-value' attributes are required to automatically bind them to the correct form field on the edit form
		?>
		<div data-quickedit-field="<?php echo $this->field_name ?>" data-quickedit-value="<?php echo esc_attr( $rating ) ?>"><?php echo $rating ?></div>
		<?php
	}
	public function render_quickedit_field( $column_name, $post_type ) {
		// 'name' attribute must match the 'data-quickedit-field' attribute on the column value to enable automatic binding
		?>
		<input type="number" min="0" max="5" name="<?php echo $this->field_name ?>">
		<?php
	}
	public function quickedit_save( $post_id, $is_bulk_edit ) {
		if( !empty( $_POST[ $this->field_name ] ) ) {
			$sanitized_value = $this->sanitize_rating( $_POST[ $this->field_name ] );
			update_post_meta( $post_id, $this->field_meta, $sanitized_value );
		} elseif( !$is_bulk_edit ) {
			// delete post meta only when using the quick edit box on a single post, not when doing bulk edit
			delete_post_meta( $post_id, $this->field_meta );
		}
	}
	
	
}
new AdminTableColumnPostRating();
