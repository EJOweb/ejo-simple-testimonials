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

		//* Fetch carousel option
		$carousel = isset( $instance['carousel'] ) ? TRUE : NULL;

		//* Fetch 'how many testimonials should be shown' option
		$number = isset( $instance['number'] ) ? $instance['number'] : '1';

		//* Fetch character limit
		$character_limit = isset( $instance['character_limit'] ) ? $instance['character_limit'] : NULL;

		//* Store link/button
		$button['show'] = isset( $instance['button']['show'] ) ? TRUE : NULL;
		$button['link_text'] = isset( $instance['button']['link_text'] ) ? strip_tags( $instance['button']['link_text'] ) : '';
		$button['linked_page'] = isset( $instance['button']['linked_page'] ) ? $instance['button']['linked_page'] : NULL;

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title']; ?>

		<div class="testimonials-wrap">
			<ul class="testimonials <?php if ($carousel) echo 'carousel'; ?>"><?php

				if ( !$testimonials ) {
					printf( '<li>%s</li>', 'No testimonials available' );
					return;
				}

				//* Randomize testimonials
				shuffle($testimonials);

				//* Make sure given 'number' does not exceed total count of testimonials
				$number = (sizeof($testimonials) < $number) ? sizeof($testimonials) : $number;

				//* Loop through testimonials
				for( $i=0; $i<$number; $i++) {				

					$testimonial = $testimonials[$i]; ?>

					<li class="testimonial">
						<?php
						if (!empty($testimonial['title']))
							printf( '<h4 class="%s">%s</h4>', 'testimonial-title', stripslashes($testimonial['title']) );

						if (!empty($testimonial['caption']))
							printf( '<span class="%s">%s</span>', 'testimonial-caption', stripslashes($testimonial['caption']) );

						if (!empty($testimonial['content'])) {
							$testimonial_content = stripslashes($testimonial['content']);

							//* Limit number of characters
							if (isset($character_limit) && strlen($testimonial_content) > $character_limit) 
								$testimonial_content = substr($testimonial_content, 0, $character_limit) . '...';

							printf( '<div class="%s"><blockquote class="%s">%s</blockquote></div>', 'quote-wrap', 'testimonial-quote', $testimonial_content );
						}
						?>				
					</li>
				<?php
				}
				?>
			</ul>

		<?php if ($button['show']) ?>
			<a href="<?php echo get_the_permalink($button['linked_page']); ?>" class="button"><?php echo $button['link_text']; ?></a>
		
		</div>

		<?php echo $args['after_widget'];
	}

	//* Update a particular instance.
	function update( $new_instance, $old_instance ) 
	{
		//* Store new title
		$instance['title'] = isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';

		//* Store new carousel option [on/off]
		$instance['carousel'] = isset( $new_instance['carousel'] ) ? TRUE : NULL;

		//* Store new number of testimonials
		$instance['number'] = isset( $new_instance['number'] ) ? $new_instance['number'] : '1';

		//* Store new character_limit
		$instance['character_limit'] = isset( $new_instance['character_limit'] ) ? absint( $new_instance['character_limit'] ) : '';

		//* Store link/button
		$instance['button']['show'] = isset( $new_instance['button']['show'] ) ? TRUE : NULL;
		$instance['button']['link_text'] = isset( $new_instance['button']['link_text'] ) ? strip_tags( $new_instance['button']['link_text'] ) : '';
		$instance['button']['linked_page'] = isset( $new_instance['button']['linked_page'] ) ? $new_instance['button']['linked_page'] : NULL;

		//* Save
		return $instance;
	}

	//* Echo the settings update form.
	function form( $instance ) 
	{
		//* Fetch title
		$title = isset( $instance['title'] ) ? $instance['title'] : '';

		//* Fetch carousel option
		$carousel = isset( $instance['carousel'] ) ? TRUE : NULL;

		//* Fetch 'how many testimonials should be shown' option
		$number = isset( $instance['number'] ) ? $instance['number'] : '1';

		//* Fetch character limit
		$character_limit = isset( $instance['character_limit'] ) ? $instance['character_limit'] : '';

		//* Fetch button settings
		$button['show'] = isset( $instance['button']['show'] ) ? TRUE : NULL; 
		$button['link_text'] = isset( $instance['button']['link_text'] ) ? $instance['button']['link_text'] : '';
		$button['linked_page'] = isset( $instance['button']['linked_page'] ) ? $instance['button']['linked_page'] : '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('carousel'); ?>"><?php _e('Carousel:') ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id('carousel'); ?>" name="<?php echo $this->get_field_name('carousel'); ?>" <?php checked($carousel); ?> />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Aantal referenties:') ?></label>
			<select id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" >
				<option value="1" <?php selected( $number, '1' ); ?> >1</option>
				<option value="2" <?php selected( $number, '2' ); ?> >2</option>
				<option value="3" <?php selected( $number, '3' ); ?> >3</option>
				<option value="4" <?php selected( $number, '4' ); ?> >4</option>
				<option value="5" <?php selected( $number, '5' ); ?> >5</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('character_limit'); ?>"><?php _e('Character limit:') ?></label>
			<input type="text" id="<?php echo $this->get_field_id('character_limit'); ?>" name="<?php echo $this->get_field_name('character_limit'); ?>" value="<?php echo $character_limit; ?>" size="3" />
		</p>

		<hr>

		<p>
			<label for="<?php echo $this->get_field_id('button'); ?>"><?php _e('Link naar referentie-pagina tonen: ') ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>[show]" <?php checked($button['show']); ?> />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Link Tekst:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('button'); ?>[link_text]" value="<?php echo $button['link_text']; ?>" />
		</p>
		<p>
			<label>Referentie-pagina:</label>
			<select name="<?php echo $this->get_field_name('button'); ?>[linked_page]">
				<?php $this->page_select_options($button['linked_page']); ?>
			</select>
		</p>

		<?php
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