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
		$widget_title = __( 'Simple Testimonials', 'ejo-simple-testimonials' );

		$widget_ops = array(
			'classname'   => 'simple-testimonials-widget',
			'description' => __( 'Shows a random testimonial', 'ejo-simple-testimonials' ),
		);

		$control_ops = array(
			'id_base' => 'simple-testimonials-widget'
		);

		parent::__construct( 'simple-testimonials-widget', $widget_title, $widget_ops, $control_ops );
	}

	//* Echo the widget content.
	function widget( $args, $instance ) 
	{
		/* Create default widget values */
		$defaults = array(
			'title'					=> '',
			'carousel' 				=> FALSE,
			'max_number'			=> '1',
			'char_limit'			=> '0',
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

		// Get testimonials
		$testimonials = EJO_Simple_Testimonials::get_testimonials();

		// Randomize array
		shuffle($testimonials);

		// Continue with only the maximum number of testimonials
		$testimonials = array_slice($testimonials, 0, $instance['max_number']);

		// This filter is documented in wp-includes/default-widgets.php
		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		// Disable carousel when there aren't multiple testimonials, otherwise leave the same
		$instance['carousel'] = ( count($testimonials) < 2 ) ? FALSE : $instance['carousel'];

		// Disable button when there isn't a linked page, otherwise leave the same
		$instance['button_show'] = ( '' == $instance['button_linked_page'] ) ? FALSE : $instance['button_show'];

		echo $args['before_widget'];

		if ( $instance['title'] ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title']; 
		}

		/* Insert carousel script if active */
		if ($instance['carousel']) : ?>

			<script type="text/javascript">
				jQuery(document).ready(function($){

					/*------------------------------------------------------------------
					Testimonial caroussel
					-------------------------------------------------------------------*/
					$('.simple-testimonials.carousel .simple-testimonial:gt(0)').hide();
					
					setInterval(
						function(){
							$('.simple-testimonials.carousel .simple-testimonial:first-child').fadeOut( 600, function() {
								$(this).next('.simple-testimonial').fadeIn( 600 );
								$(this).appendTo('.simple-testimonials.carousel');
							}).end();
						},
						5000
					);

				});
			</script>

		<?php endif; // End carousel check ?>

		<div class="simple-testimonials <?php if ($instance['carousel']) { echo 'carousel'; } ?>">

			<?php if ( !empty($testimonials) ) : ?>

				<?php foreach ($testimonials as $testimonial) : //* Loop through testimonials ?>

					<?php EJO_Simple_Testimonials::the_testimonial($testimonial, $instance['char_limit']); ?>

				<?php endforeach; ?>

			<?php else : ?>

				<p>No testimonials available</p>
				
			<?php endif; ?>
			
		</div>

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
			'carousel' 				=> FALSE,
			'max_number'			=> '1',
			'char_limit'			=> '0',
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
			<strong>Carousel</strong>
		</p>
		<p>
			<label for="<?= $this->get_field_id('carousel'); ?>">
				<input type="checkbox" id="<?= $this->get_field_id('carousel'); ?>" name="<?= $this->get_field_name('carousel'); ?>" <?php checked($instance['carousel']); ?> />
				Inschakelen
			</label>
		</p>
		<p>
			<label for="<?= $this->get_field_id('max_number'); ?>">Aantal referenties:</label>
			<select id="<?= $this->get_field_id('max_number'); ?>" name="<?= $this->get_field_name('max_number'); ?>" class="widefat">
				<option value="1" <?php selected( $instance['max_number'], '1' ); ?> >1</option>
				<option value="2" <?php selected( $instance['max_number'], '2' ); ?> >2</option>
				<option value="3" <?php selected( $instance['max_number'], '3' ); ?> >3</option>
				<option value="4" <?php selected( $instance['max_number'], '4' ); ?> >4</option>
				<option value="5" <?php selected( $instance['max_number'], '5' ); ?> >5</option>
				<option value="6" <?php selected( $instance['max_number'], '6' ); ?> >6</option>
				<option value="7" <?php selected( $instance['max_number'], '7' ); ?> >7</option>
				<option value="8" <?php selected( $instance['max_number'], '8' ); ?> >8</option>
				<option value="9" <?php selected( $instance['max_number'], '9' ); ?> >9</option>
				<option value="10" <?php selected( $instance['max_number'], '10' ); ?> >10</option>
			</select>
		</p>
		<p>
			<label for="<?= $this->get_field_id('char_limit'); ?>">
				Character limit: 
				<input type="text" id="<?= $this->get_field_id('char_limit'); ?>" name="<?= $this->get_field_name('char_limit'); ?>" value="<?= $instance['char_limit']; ?>" placeholder="200" class="widefat" />
			</label>
		</p>
		<p>
			<strong>Referentiepagina</strong>
		</p>
		<p>
			<label for="<?= $this->get_field_id('button_show'); ?>">
				<input type="checkbox" id="<?= $this->get_field_id('button_show'); ?>" name="<?= $this->get_field_name('button_show'); ?>" value="show" <?php checked($instance['button_show']); ?> />
				Toon link naar referentie-pagina
			</label>
		</p>
		<p>
			<label for="<?= $this->get_field_id('button_link_text'); ?>">Link Tekst:</label>
			<input type="text" class="widefat" id="<?= $this->get_field_id('button_link_text'); ?>" name="<?= $this->get_field_name('button_link_text'); ?>" value="<?= esc_attr($instance['button_link_text']); ?>" />
		</p>
		<p>
			<label>Referentie-pagina:</label>
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

		//* Store new carousel option [on/off]
		$instance['carousel'] = 	(! empty( $new_instance['carousel'] )) ? TRUE : FALSE;

		//* Store new number of testimonials
		$instance['max_number'] = 	(! empty( $new_instance['max_number'] )) ? absint( $new_instance['max_number'] ) : '1';

		//* Store new char_limit
		$instance['char_limit'] =	(! empty( $new_instance['char_limit'] )) ? absint( $new_instance['char_limit'] ) : '0';

		//* Store link/button
		$instance['button_show'] = 			(! empty( $new_instance['button_show'] )) ? TRUE : FALSE;
		$instance['button_link_text'] = 	(! empty( $new_instance['button_link_text'] )) ? strip_tags( $new_instance['button_link_text'] ) : 'All testimonials';
		$instance['button_linked_page'] = 	(! empty( $new_instance['button_linked_page'] )) ? absint( $new_instance['button_linked_page'] ) : FALSE;

		//* Save
		return $instance;
	}

	public function page_select_options($field_value, $all_pages = '')
	{
		if (empty($all_pages)) 
			$all_pages = get_pages();

		foreach ($all_pages as $page) {
			$selected = selected($field_value, $page->ID, false);
			echo "<option value='".$page->ID."' ".$selected.">".$page->post_title."</option>";
		}
	}
}
?>