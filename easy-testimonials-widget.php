<?php

/* Keurmerken Widget Class
--------------------------------------------- */
class Keurmerken_Widget extends WP_Widget {

	//* Holds widget settings defaults, populated in constructor.
	protected $defaults;

	//* Constructor. Set the default widget options and create widget.
	function __construct() 
	{
		$this->defaults = array(
			'title'                   => 'Keurmerken Widget',
		);

		$widget_ops = array(
			'classname'   => 'keurmerken-widget',
			'description' => __( 'Keurmerken Widget', 'ejo' ),
		);

		$control_ops = array(
			'id_base' => 'keurmerken-widget',
			'width'   => 505,
			'height'  => 350,
		);

		parent::__construct( 'keurmerken-widget', __( 'Keurmerken Widget', 'ejo' ), $widget_ops, $control_ops );
	}

	//* Echo the widget content.
	function widget( $args, $instance ) {

		//* Get keurmerken-data
		$keurmerken = get_option( 'theme_options_keurmerken' );
		$keurmerken = ($keurmerken !== false) ? $keurmerken : array();

		/** This filter is documented in wp-includes/default-widgets.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		echo '<div class="widget-content">';
		echo '<ul class="keurmerken">';
		foreach ($keurmerken as $keurmerk) {

			echo '<li class="keurmerk">';

			if (!empty($keurmerk['link']))
				echo '<a href="'. $keurmerk['link'] .'">';
			
			echo wp_get_attachment_image( $keurmerk['image_id'], 'keurmerk' );

			if (!empty($keurmerk['link']))
				echo '</a>';

			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';

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