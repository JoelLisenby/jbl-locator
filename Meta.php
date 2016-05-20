<?php 

namespace JBLLocator;

class Meta {
	
	public $fields;
	public $meta;
	
	public function __construct() {
		
		$this->fields = array(
			array( 'name' => 'lat', 'label' => 'Latitude', 'type' => 'text' ),
			array( 'name' => 'lng', 'label' => 'Longitude', 'type' => 'text' ),
			array( 'name' => 'address', 'label' => 'Address', 'type' => 'textarea' ),
			array( 'name' => 'phone', 'label' => 'Phone', 'type' => 'text' ),
			array( 'name' => 'email', 'label' => 'Email', 'type' => 'text' ),
			array( 'name' => 'website', 'label' => 'Website', 'type' => 'text' )
		);
		
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		
	}
	
	public function add_meta_boxes() {
		
		add_meta_box(
    		'jbllocator-meta-box',
    		__( 'Location Information', 'jbllocator' ),
    		array( $this, 'display_meta_boxes' ),
    		'specialist', 
    		'normal',
    		'high'
    	);
		
	}
	
	public function display_meta_boxes( $post ) {
		
		$this->meta = get_post_custom( $post->ID );
		
		wp_nonce_field( 'jbllocator_meta_box_nonce', 'jbllocator_meta_box_nonce' );
		
		foreach( $this->fields as $field ) {
		
			switch( $field['type'] ) {
				case 'textarea':
					echo '<p class="field textarea '. $field['name'] .'">';
					echo '<label for="'. $field['name'] .'">'. __( $field['label'], 'jbllocator' ) .'</label><br />';
					echo '<textarea name="'. $field['name'] .'" id="'. $field['name'] .'">'. ( !empty($this->meta[$field['name']][0]) ? $this->meta[$field['name']][0] : '' ) .'</textarea>';
					echo '</p>';
					break;
				default:
					echo '<p class="field text '. $field['name'] .'">';
					echo '<label for="'. $field['name'] .'">'. __( $field['label'], 'jbllocator' ) .'</label><br />';
					echo '<input type="text" name="'. $field['name'] .'" id="'. $field['name'] .'" value="'. ( !empty($this->meta[$field['name']][0]) ? $this->meta[$field['name']][0] : '' ) .'" />';
					echo '</p>';
					break;
			}
		
		}
		
	}
	
	public function save_post( $post_id ) {
		
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if( !isset( $_POST['jbllocator_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['jbllocator_meta_box_nonce'], 'jbllocator_meta_box_nonce' ) ) return;
		if( !current_user_can( 'edit_post' ) ) return;
		
		foreach( $this->fields as $field ) {
			
			update_post_meta( $post_id, $field['name'], $_POST[$field['name']] );
			
		}
		
	}
	
}

?>