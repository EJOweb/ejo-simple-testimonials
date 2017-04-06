<?php

/* EJO Simple Testimonials Aggregate Widget Class
--------------------------------------------- */
class EJO_Simple_Testimonials_Aggregate_Widget extends WP_Widget {

	//* Holds widget settings defaults, populated in constructor.
	protected $defaults;

	private $slug;

	//* Constructor. Set the default widget options and create widget.
	function __construct() 
	{
		$widget_title = __( 'Simple Testimonials Aggregate', 'ejo-simple-testimonials' );

		$widget_ops = array(
			'classname'   => 'simple-testimonials-aggregate-widget',
			'description' => __( 'Shows the testimonials aggregate', 'ejo-simple-testimonials' ),
		);

		$control_ops = array(
			'id_base' => 'simple-testimonials-aggregate-widget'
		);

		parent::__construct( 'simple-testimonials-aggregate-widget', $widget_title, $widget_ops, $control_ops );
	}

	//* Echo the widget content.
	function widget( $args, $instance ) 
	{
		/* Create default widget values */
		$defaults = array(
			'title'					=> '',
			'text'					=> '',
			'button_show' 			=> FALSE,
			'button_link_text' 		=> '',
			'button_linked_page'	=> ''
		);

		/* Parse stored variables with defaults */
		$instance = wp_parse_args( $instance, $defaults );

		/**
 		 * Check if to load widget-template from theme. Otherwise continue with default widget output
 		 */

 		if ( class_exists( 'EJO_Widget_Template_Loader' ) && EJO_Widget_Template_Loader::load_template( $args, $instance, $this ) )
 			return;

 		/**
 		 * Proceed with default Widget output
 		 */ 		

		// This filter is documented in wp-includes/default-widgets.php
		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		// Disable button when there isn't a linked page, otherwise leave the same
		$instance['button_show'] = ( '' == $instance['button_linked_page'] ) ? FALSE : $instance['button_show'];

		echo $args['before_widget'];

		if ( $instance['title'] ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title']; 
		}

		if ( $instance['text'] ) {
			echo apply_filters( 'the_content', $instance['text'] ); 
		}

		EJO_Simple_Testimonials::testimonials_aggregate();
		?>

		<?php if ($instance['button_show']) : ?>

			<a href="<?= get_the_permalink($instance['button_linked_page']); ?>" class="button"><?= $instance['button_link_text']; ?></a>

		<?php endif; // Show button ?>
		
		<?php echo $args['after_widget'];
	}

	//* Echo the settings update form.
	function form( $instance ) 
	{
		/* Create default widget values */
		$defaults = array(
			'title'					=> '',
			'text'					=> '',
			'button_show' 			=> FALSE,
			'button_link_text' 		=> '',
			'button_linked_page'	=> ''
		);

		/* Parse stored variables with defaults */
		$instance = wp_parse_args( $instance, $defaults );

		?>
		<p>
			<label for="<?= $this->get_field_id('title'); ?>">Title:</label>
			<input type="text" class="widefat" id="<?= $this->get_field_id('title'); ?>" name="<?= $this->get_field_name('title'); ?>" value="<?= esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?= $this->get_field_id('text'); ?>">Text:</label>
			<textarea class="widefat" id="<?= $this->get_field_id('text'); ?>" name="<?= $this->get_field_name('text'); ?>"><?= esc_attr($instance['text']); ?></textarea>
		</p>
		<p>
			<strong>Referentiepagina</strong>
		</p>
		<p>
			<label for="<?= $this->get_field_id('button_show'); ?>">
				<input type="checkbox" id="<?= $this->get_field_id('button_show'); ?>" name="<?= $this->get_field_name('button_show'); ?>" value="show" <?php checked($instance['button_show']); ?> />
				Activeer link
			</label>
		</p>
		<p>
			<label for="<?= $this->get_field_id('button_link_text'); ?>">Link Tekst:</label>
			<input type="text" class="widefat" id="<?= $this->get_field_id('button_link_text'); ?>" name="<?= $this->get_field_name('button_link_text'); ?>" value="<?= esc_attr($instance['button_link_text']); ?>" />
		</p>
		<p>
			<label>Link pagina:</label>
			<select name="<?= $this->get_field_name('button_linked_page'); ?>" class="widefat">
				<?php $this->page_select_options($instance['button_linked_page']); ?>
			</select>
		</p>

		<?php
	}

	//* Update a particular instance.
	function update( $new_instance, $old_instance ) 
	{
		//* Store new title
		$instance['title'] = 		(! empty( $new_instance['title'] )) ? strip_tags($new_instance['title']) : '';

		//* Store new text
		$instance['text'] = 		(! empty( $new_instance['text'] )) ? strip_tags($new_instance['text']) : '';

		//* Store link/button
		$instance['button_show'] = 			(! empty( $new_instance['button_show'] )) ? TRUE : FALSE;
		$instance['button_link_text'] = 	(! empty( $new_instance['button_link_text'] )) ? strip_tags( $new_instance['button_link_text'] ) : 'All testimonials';
		$instance['button_linked_page'] = 	(! empty( $new_instance['button_linked_page'] )) ? absint( $new_instance['button_linked_page'] ) : FALSE;

		//* Save
		return $instance;
	}
}
?>