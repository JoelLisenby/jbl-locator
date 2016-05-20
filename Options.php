<?php

namespace JBLLocator;

class Options {
	
	public $defaults;
	public $options;
	public $option_name;
	public $fields;
	
	public function __construct() {
		
		$this->defaults = array(
			'pt_slug' => 'location',
			'pt_name' => 'Locations',
			'pt_name_singular' => 'Location',
			'api_key' => ''
		);
		
		$this->option_name = 'jbllocator_options';
        $this->options = get_option( $this->option_name, $this->defaults );
		
		$this->fields = array (
			array( 'name' => 'pt_slug', 'label' => 'Post Type Slug', 'type' => 'text' ),
			array( 'name' => 'pt_name', 'label' => 'Post Type Name', 'type' => 'text' ),
			array( 'name' => 'pt_name_singular', 'label' => 'Post Type Singular Name', 'type' => 'text' ),
			array( 'name' => 'api_key', 'label' => 'Google Maps API Key', 'type' => 'text' )
		);
		
		add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		
	}
	
	public function add_admin_page() {
		
		add_menu_page(
			'Locator Map',
			'Locator Map',
			'manage_options',
			'jbllocator-options',
			array( $this, 'create_admin_page' )
		);
		
	}
	
	public function create_admin_page() {
		
?>
<h1>Locator Map</h1>

<p>Add the map to pages or posts with the following shortcode: <strong>[jbllocator]</strong></p>

<form method="post" action="options.php">
<?php

settings_fields( 'jbllocator_options_group' );   
do_settings_sections( 'jbllocator-options' );
submit_button();

?>
</form>
<?php

	}
	
	public function page_init() {
		
		register_setting(
			$this->option_name .'_group',
			$this->option_name,
			array( $this, 'sanitize' )
		);
		
		add_settings_section(
			'jbllocator_section',
			'Options',
			array( $this, 'print_section_info' ),
			'jbllocator-options'
        );
		
		foreach($this->fields as $field) {
			add_settings_field(
				$field['name'],
				$field['label'],
				array( $this, 'field_callback_'. $field['name'] ),
				'jbllocator-options',
				'jbllocator_section'
			);
		}
		
	}
	
	public function sanitize( $input ) {
		
		$new_input = array();
		
		foreach( $input as $name => $value ) {
			switch( $name ) {
				default:
					$new_input[$name] = sanitize_text_field( $value );
					break;
			}
		}
		
        return $new_input;
		
	}
	
	public function print_section_info() {
		
    }
	
	public function field_callback_pt_slug() {
		$this->field_text('pt_slug');
	}
	
	public function field_callback_pt_name() {
		$this->field_text('pt_name');
	}
	
	public function field_callback_pt_name_singular() {
		$this->field_text('pt_name_singular');
	}
	
	public function field_callback_api_key() {
		$this->field_text('api_key');
	}
	
	public function field_text( $id ) {
		printf(
			'<input type="text" id="'. $id .'" name="'. $this->option_name .'['. $id .']" value="%s" />',
			isset( $this->options[$id] ) ? esc_attr( $this->options[$id]) : ''
		);
	}
	
	public function admin_notices() {
		
		if( empty( $this->options['api_key'] ) && empty( get_option('jbllocator-warning-dismissed') ) ) {
?>
<div class="notice notice-warning jbllocator-warning">
<p><?php _e( 'You must enter your Google Maps API key in "Locator Map" settings for maps to work.', 'jbllocator-warning' ); ?></p>
</div>
<?php
		}
		
	}
	
}

?>