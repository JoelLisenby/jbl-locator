<?php

namespace JBLLocator;
use WP_Query;

class ShortCode {
	
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
		
		add_shortcode( 'jbllocator', array( $this, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
	}
	
	public function shortcode( $atts ) {
		
		ob_start();
?>
<div class="jbllocator">
<input type="text" id="jbllocatorsearch" name="jbllocatorsearch" value="" placeholder="Enter Your Location" />
<div id="jbllocatormap"></div>
</div><!-- .locator -->
<?php
		return ob_get_clean();
		
	}
	
	public function getPosts() {
		
		$args = array( 'post_type' => 'specialist', 'posts_per_page' => -1 );
		$loop = new WP_Query( $args );
		$specialists = array();

		while ( $loop->have_posts() ) {
			$loop->the_post();
			$meta = get_post_meta( get_the_ID () );
			
			$specialists[] = array(
				'title' => get_the_title(),
				'content' => get_the_content(),
				'lat' => !empty($meta['lat'][0]) ? $meta['lat'][0] : '',
				'lng' => !empty($meta['lng'][0]) ? $meta['lng'][0] : '',
				'address' => !empty($meta['address'][0]) ? $meta['address'][0] : '',
				'phone' => !empty($meta['phone'][0]) ? $meta['phone'][0] : '',
				'email' => !empty($meta['email'][0]) ? $meta['email'][0] : '',
				'website' => !empty($meta['website'][0]) ? $meta['website'][0] : ''
			);
		}
		
		wp_reset_query();
		
		return $specialists;
	}
	
	public function enqueue_scripts() {
		
		wp_enqueue_script( 'google-maps-api', '//maps.google.com/maps/api/js?key='. $this->options['api_key'] .'&libraries=places', null, false, true );
		wp_register_script( 'jbllocator',  plugins_url( 'jbl-locator.js', __FILE__ ), array( 'jquery' ), false, true );
		wp_localize_script( 'jbllocator', 'posts_json', $this->getPosts() );
		wp_enqueue_script( 'jbllocator' );
		wp_enqueue_style( 'jbllocator', plugins_url( 'jbl-locator.css', __FILE__ ) );
		
	}
	
}

?>