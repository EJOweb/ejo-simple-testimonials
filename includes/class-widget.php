<?php

/* EJO Simple Testimonials Widget Class
--------------------------------------------- */
class EJO_Simple_Testimonials_Widget extends WP_Widget {

	//* Holds widget settings defaults, populated in constructor.
	protected $defaults;

	private $slug;

	//* Constructor. Set the default widget options and create widget.
	function __construct() 
	{
		$widget_title = __( 'Simple Testimonials', EJO_Simple_Testimonials::$slug );

		$widget_ops = array(
			'classname'   => 'simple-testimonials',
			'description' => __( 'Shows a random testimonial', EJO_Simple_Testimonials::$slug ),
		);

		$control_ops = array(
			'id_base' => 'ejo-simple-testimonials-widget'
		);

		parent::__construct( 'ejo-simple-testimonials-widget', $widget_title, $widget_ops, $control_ops );
	}

	//* Echo the widget content.
	function widget( $args, $instance ) {

		//* Get testimonials
		$testimonials = EJO_Simple_Testimonials::get_testimonials();

		/** This filter is documented in wp-includes/default-widgets.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		echo '<ul class="testimonials">';

			if ( !$testimonials ) {
				printf( '<li>%s</li>', 'No testimonials available' );
				return;
			}

			//* Get random testimonial
			$testimonial = $testimonials[ array_rand($testimonials) ];

			echo '<li class="testimonial">';

			printf( '<h4>%s</h4>', stripslashes($testimonial['title']) );
			printf( '<blockquote>%s</blockquote>', stripslashes($testimonial['content']) );
			printf( '<span>%s</span>', stripslashes($testimonial['caption']) );
			
			echo '</li>';

		echo '</ul>';

		echo $args['after_widget'];
	}

	//* Update a particular instance.
	function update( $new_instance, $old_instance ) {

		$new_instance['title'] = strip_tags( $new_instance['title'] );

		return $new_instance;
	}

	//* Echo the settings update form.
	function form( $instance ) {

		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<?php
	}
}
?>