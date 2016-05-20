<?php 

namespace JBLLocator;

class PostType {
	
	public $defaults;
	public $options;
	public $option_name;

	public function __construct() {
		
		$this->defaults = array(
			'pt_slug' => 'location',
			'pt_name' => 'Locations',
			'pt_name_singular' => 'Location',
			'api_key' => ''
		);
		
		$this->option_name = 'jbllocator_options';
        $this->options = get_option( $this->option_name, $this->defaults );
		
		add_action( 'init', array( $this, 'init' ) );
		
	}
	
	public function init() {
		
		$labels = array(
			'name'			=> __( $this->options['pt_name'], 'jbllocator' ),
			'singular_name'	=> __( $this->options['pt_name_singular'], 'jbllocator' )
		);

		$args = array(
			'has_archive'	=> true,
			'labels'		=> $labels,
			'public'		=> true,
			'rewrite'		=> array( 'slug' => $this->options['pt_slug'] ),
			'supports'		=> array( 'title', 'editor', 'thumbnail' )
		);
		
		register_post_type( 'specialist', $args );
		
	}
	
}

?>