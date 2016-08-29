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
	function widget( $args, $instance ) 
	{
		//* Check if Widget Template Loader exists and try to load template
 		if ( class_exists( 'EJO_Widget_Template_Loader' ) && EJO_Widget_Template_Loader::load_template( $args, $instance, $this ) )
 			return;

		//* Get testimonials
		$testimonials = EJO_Simple_Testimonials::get_testimonials();

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		//* Fetch carousel option
		$carousel = empty( $instance['carousel'] ) ? FALSE : TRUE;

		//* Fetch 'how many testimonials should be shown' option
		$number = empty( $instance['number'] ) ? '1' : $instance['number'];

		//* Fetch character limit
		$character_limit = empty( $instance['character_limit'] ) ? 0 : $instance['character_limit'];

		//* Store link/button
		$button['show'] = empty( $instance['button']['show'] ) ? FALSE : TRUE;
		$button['link_text'] = empty( $instance['button']['link_text'] ) ? '' : strip_tags( $instance['button']['link_text'] );
		$button['linked_page'] = empty( $instance['button']['linked_page'] ) ? '' : $instance['button']['linked_page'];

		echo $args['before_widget'];

		if ( $title )
			echo $args['before_title'] . $title . $args['after_title']; 

		/* Insert carousel script if active */
		if ($carousel) : ?>

			<script type="text/javascript">
				jQuery(document).ready(function($){

					/*------------------------------------------------------------------
					Testimonial caroussel
					-------------------------------------------------------------------*/
					$('.testimonials.carousel .testimonial:gt(0)').hide();
					
					setInterval(
						function(){
							$('.testimonials.carousel .testimonial:first-child').fadeOut( 600, function() {
								$(this).next('.testimonial').fadeIn( 600 );
								$(this).appendTo('.testimonials.carousel');
							}).end();
						},
						5000
					);

				});
			</script>

		<?php endif; // End carousel check ?>

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
							if ($character_limit && strlen($testimonial_content) > $character_limit) 
								$testimonial_content = substr($testimonial_content, 0, $character_limit) . '...';

							printf( '<div class="%s"><blockquote class="%s">%s</blockquote></div>', 'quote-wrap', 'testimonial-quote', $testimonial_content );
						}
						?>				
					</li>
				<?php
				}
				?>
			</ul>

		<?php if ($button['show'] && $button['linked_page']) : ?>
			<a href="<?php echo get_the_permalink($button['linked_page']); ?>" class="button"><?php echo $button['link_text']; ?></a>
		<?php endif; // Show button ?>
		
		</div>

		<?php echo $args['after_widget'];
	}

	//* Update a particular instance.
	function update( $new_instance, $old_instance ) 
	{
		//* Store new title
		$instance['title'] = empty( $new_instance['title'] ) ? '' : strip_tags( $new_instance['title'] );

		//* Store new carousel option [on/off]
		$instance['carousel'] = empty( $new_instance['carousel'] ) ? FALSE : TRUE;

		//* Store new number of testimonials
		$instance['number'] = empty( $new_instance['number'] ) ? '1' : $new_instance['number'];

		//* Store new character_limit
		$instance['character_limit'] = empty( $new_instance['character_limit'] ) ? 0 : absint( $new_instance['character_limit'] );

		//* Store link/button
		$instance['button']['show'] = empty( $new_instance['button']['show'] ) ? FALSE : TRUE;
		$instance['button']['link_text'] = empty( $new_instance['button']['link_text'] ) ? '' : strip_tags( $new_instance['button']['link_text'] );
		$instance['button']['linked_page'] = empty( $new_instance['button']['linked_page'] ) ? '' : $new_instance['button']['linked_page'];

		//* Save
		return $instance;
	}

	//* Echo the settings update form.
	function form( $instance ) 
	{
		//* Fetch title
		$title = empty( $instance['title'] ) ? '' : $instance['title'];

		//* Fetch carousel option
		$carousel = empty( $instance['carousel'] ) ? FALSE : TRUE;

		//* Fetch 'how many testimonials should be shown' option
		$number = empty( $instance['number'] ) ? '1' : $instance['number'];

		//* Fetch character limit
		$character_limit = empty( $instance['character_limit'] ) ? 0 : $instance['character_limit'];

		//* Fetch button settings
		$button['show'] = empty( $instance['button']['show'] ) ? FALSE : TRUE; 
		$button['link_text'] = empty( $instance['button']['link_text'] ) ? '' : $instance['button']['link_text'];
		$button['linked_page'] = empty( $instance['button']['linked_page'] ) ? '' : $instance['button']['linked_page'];

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<strong>Carousel</strong>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('carousel'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('carousel'); ?>" name="<?php echo $this->get_field_name('carousel'); ?>" <?php checked($carousel); ?> />
				Inschakelen
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>">Aantal referenties:</label>
			<select id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" class="widefat">
				<option value="1" <?php selected( $number, '1' ); ?> >1</option>
				<option value="2" <?php selected( $number, '2' ); ?> >2</option>
				<option value="3" <?php selected( $number, '3' ); ?> >3</option>
				<option value="4" <?php selected( $number, '4' ); ?> >4</option>
				<option value="5" <?php selected( $number, '5' ); ?> >5</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('character_limit'); ?>">
				Character limit: 
				<input type="text" id="<?php echo $this->get_field_id('character_limit'); ?>" name="<?php echo $this->get_field_name('character_limit'); ?>" value="<?php echo $character_limit; ?>" placeholder="200" class="widefat" />
			</label>
		</p>
		<p>
			<strong>Referentiepagina</strong>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('button'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>[show]" value="show" <?php checked($button['show']); ?> />
				Toon link naar referentie-pagina
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link_text'); ?>">Link Tekst:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('button'); ?>[link_text]" value="<?php echo $button['link_text']; ?>" />
		</p>
		<p>
			<label>Referentie-pagina:</label>
			<select name="<?php echo $this->get_field_name('button'); ?>[linked_page]" class="widefat">
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