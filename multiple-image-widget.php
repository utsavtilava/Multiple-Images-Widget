<?php
/*
Plugin Name: Multiple Images Widget
Plugin URI: #
Description: Add Multiple Images For Your Sidebar
Version: 1.0
Author: Utsav Tilava
Author URI: https://profiles.wordpress.org/utsav72640
Text Domain: multiple-images-widget

Released under the GPL v.2, http://www.gnu.org/copyleft/gpl.html

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
/**
 * @author		https://profiles.wordpress.org/utsav72640
 * @copyright	Copyright (c) 2018, Clean to Shine
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @version	 	1.0
 */

//avoid direct calls to this file
if ( !defined( 'ABSPATH' ) ) { exit; }

if (!class_exists('miw_widget_multiple_images')) {
	class miw_widget_multiple_images extends WP_Widget{

		// Construct
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'miw_load_plugin_textdomain' ) );
			parent::__construct( 'miw_widget_multiple_images', esc_attr__( 'Multiple Images Widget', 'multiple-images-widget' ),

			array(
				'description' => esc_attr__( 'Displays an Multiple Images Widget.', 'multiple-images-widget' ),
				'mime_type'   => 'image',
			) );

			add_action( 'admin_enqueue_scripts', array( $this, 'miw_widget_multiple_image_script' ));
		}

		/* Load plugin textdomain. */
	    public function miw_load_plugin_textdomain() {
	      load_plugin_textdomain( 'multiple-images-widget', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
	    }

		// enqueue miw script
		public function miw_widget_multiple_image_script() {
		    wp_enqueue_script( 'image', plugin_dir_url( __FILE__ ). 'js/amiw-widget.js', array( 'jquery' ), '1.0.0', false );
			wp_enqueue_script( 'image' );
		}

		// add widget code
		public function widget( $args, $instance ) {
			$title 					= !empty( $instance['title'] ) ? $instance['title'] : '';
			$widget_image 			= !empty( $instance['widget_image'] ) ? $instance['widget_image'] : array();
			$widget_image_link 		= !empty( $instance['widget_image_link'] ) ? $instance['widget_image_link'] : array();
			$widget_link_traget		= !empty( $instance['widget_link_traget'] ) ? $instance['widget_link_traget'] : array();
			

			ob_start();
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
			  echo $args['before_title'] . $title . $args['after_title'];
			}

			if($widget_image){ 
			   	echo '<div class="widget-payment-icon">';
				   	foreach( $instance['widget_image'] as $key => $value ){ 
						$widget_link_traget = isset(($instance['widget_link_traget'][$key])) && ($instance['widget_link_traget'][$key] == 'newtabopen' ) ? 'target="_blank"' : '';
    						
						// display the image
						$widget_alt_image = isset($value)? ' alt="image icon"' : '';
						if (!empty($value)) {
							if(!empty($instance['widget_image_link'][$key])){
								echo '<a '.$widget_link_traget.' href="'.$instance['widget_image_link'][$key].'">';
							}
				      	 		
				      	 		echo '<img src="'.esc_url($value).'" class="widget-upload-image" '.$widget_alt_image.'>';
				      	 	if( !empty($instance['widget_image_link'][$key]) ){
				       			echo '</a>';
				       		}
						}
					 }
			    echo '</div>';
			} 

			echo $args['after_widget'];
			ob_end_flush();
		}

		//add form code
		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'widget_image' => array( '' ), 'widget_image_link' => array( '' ), 'widget_link_traget' => array( '' ) ) );

			$title 					= !empty( $instance['title'] ) ? $instance['title'] : '';
			$widget_image 			= !empty( $instance['widget_image'] ) ? $instance['widget_image'] : array();
			$widget_image_link 		= !empty( $instance['widget_image_link'] ) ? $instance['widget_image_link'] : 'current';
			$widget_link_traget     = !empty( $instance['widget_link_traget'] ) ? $instance['widget_link_traget'] : array();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'multiple-images-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
			<?php if( !empty( $instance['widget_image'] ) ) { ?>
				<div class="widget-image-wrap">
					<ul class="widget-image-clone">
						<?php
						$count = count($instance['widget_image']);
						foreach( $instance['widget_image'] as $key => $value ) {
							$image_add_class = ($key == 0) ? ' widget-image-hold-clone' : '';
							$image_link 		= !empty( $widget_image_link[$key] ) ? $widget_image_link[$key] : '';
							$image_open_newtab  = !empty( $widget_link_traget[$key] ) ? $widget_link_traget[$key] : '';
							$disable_attr = ( $count == 1 && $key == 0 ) ? ' disabled="disabled"' : '';
						?>
						<li class="image-section<?php echo $image_add_class;?>">
								<p>
									<label for="<?php echo $this->get_field_id( 'widget_image_link' ); ?>"><?php esc_html_e( 'Image Link:', 'multiple-images-widget'); ?></label>
									<input class="widefat" placeholder="http://" id="<?php echo $this->get_field_id( 'widget_image_link' ); ?>" name="<?php echo $this->get_field_name( 'widget_image_link' ); ?>[]" type="text" value="<?php echo esc_url( $image_link ) ?>">
								</p>
								<p>
									<label for="<?php echo $this->get_field_id( 'widget_link_traget' ); ?>"><?php esc_html_e( 'Open Link in New Tab:', 'multiple-images-widget'); ?></label><br/>
									<select class="widefat" id="<?php echo $this->get_field_id( 'widget_link_traget' ); ?>" name="<?php echo $this->get_field_name('widget_link_traget'); ?>[]">
										<option <?php selected( 'current', $image_open_newtab ) ?> value="current" ><?php echo esc_html_e('Current Window', 'multiple-images-widget'); ?></option>
										<option <?php selected( 'newtabopen', $image_open_newtab ) ?> value="newtabopen" ><?php echo esc_html_e('New Window', 'multiple-images-widget'); ?></option>
									</select>
								</p>
								<p>
									<label for="<?php echo $this->get_field_id( 'widget_image' ); ?>"><?php esc_html_e('Upload Your Image:', 'multiple-images-widget'); ?></label>
									<input class="widefat" id="customurl <?php echo $this->get_field_id( 'widget_image' ); ?>" name="<?php echo $this->get_field_name( 'widget_image' ); ?>[]" type="text" value="<?php echo $value ?>" />
									<button id="img-upload" class="widget-upload-image button button-primary">Upload Your Image</button>
									<a href="javascript:void(0);" id="rmvbutton" <?php echo  $disable_attr; ?> class="widget-remove-image button"><?php esc_html_e('Remove This Section', 'multiple-images-widget'); ?></a>
								</p>
							</li>
						<?php } ?>
					</ul>
					<a href="javascript:void(0);" class="widget-new-image button"><?php esc_html_e('Add New Image Section', 
					'multiple-images-widget'); ?></a>
				</div>
			<?php } ?>
			<?php
		}

		//add update code
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['widget_image'] = ( ! empty( $new_instance['widget_image'] ) ) ? $new_instance['widget_image'] : array();
			$instance['widget_image_link'] = ( ! empty( $new_instance['widget_image_link'] ) ) ? $new_instance['widget_image_link'] : array();
			$instance['widget_link_traget'] = ( ! empty( $new_instance['widget_link_traget'] ) ) ? $new_instance['widget_link_traget'] : array();
			return $instance;
		}// end of class 
	}
	$miw_widget_multiple_images = new miw_widget_multiple_images();
}// end of class_exists

// Register and load the widget
if ( ! function_exists( 'miw_widget_multiple_images' ) ) {
	function miw_widget_multiple_images() {
    	register_widget( 'miw_widget_multiple_images' );
	}
	add_action( 'widgets_init', 'miw_widget_multiple_images' );
}
