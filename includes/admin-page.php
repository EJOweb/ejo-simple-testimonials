<div class='wrap' style="max-width:960px;">
	<h2>Simple Testimonials</h2>

	<?php
		if ( isset($_POST['submit']) ) {
			ejo_save_simple_testimonials($_POST['testimonials']);
		}

		$testimonials = get_option( '_ejo_simple_testimonials' );
		$testimonials = (!empty($testimonials)) ? $testimonials : array();
	?>

	<!-- Referentie Clone -->
	<table style="display:none;">
		<tbody>
			<?php admin_show_simple_testimonial(); ?>
		</tbody>
	</table>

	<form action="admin.php?page=<?php echo EJO_Simple_Testimonials::$slug; ?>" method="post">
		<p>
			<?php submit_button( 'Wijzigingen opslaan', 'primary', 'submit', false ); ?>
			<a href="javascript:void(0)" class="button add_testimonial">Referentie toevoegen</a>
		</p>

		<table class="form-table wp-list-table widefat testimonials-table">
			<tbody>
<?php
				foreach ($testimonials as $position => $testimonial) :
					admin_show_simple_testimonial( $position, $testimonial );
				endforeach;
?>
			</tbody>
		</table>
		<p>
			<?php submit_button( 'Wijzigingen opslaan', 'primary', 'submit', false ); ?>
			<a href="javascript:void(0)" class="button add_testimonial">Referentie toevoegen</a>
		</p>
	</form>

	<hr/>
	<h2 class="title">Extra informatie</h2>
	<p>
		Gebruik de shortcode [simple_testimonials] om alle testimonials te tonen op een pagina.
	</p>

</div>

<?php 
/**
 * Save the testimonials data to WordPress options table
 *
 * @param: array with testimonials
 * @return: none
 */
function ejo_save_simple_testimonials($testimonials = array())
{
	/* TODO: got to use Nonces */

	/* Save the order of the testimonials (by resetting the keys) */
	$testimonials = array_values($testimonials);

	/* Debugging */
	/* ?><pre><?= var_dump($testimonials); ?></pre><?php */

	/* Saving */
	update_option( '_ejo_simple_testimonials', $testimonials );
	echo '<div id="message" class="updated"><p><strong>De testimonials zijn opgeslagen.</strong></p></div>';
}

/**
 * Show the testimonial data
 *
 * @param: position (int) | testimonials (array)
 * @return: none
 */
function admin_show_simple_testimonial( $position = 0, $testimonial = array() )
{
	/* Define the class of the container */
	$row_class           = empty($testimonial) ? 'clone' : 'testimonial';

	/* Create default testimonial values */
	$default_testimonial = array(
		'author_name'	=> '',
		'author_info' 	=> '',
		'image'			=> '',
		'content' 		=> '',
		'rating' 		=> '',
		'date' 			=> '',
	);

	/* Process the testimonial values */
	$testimonial = wp_parse_args($testimonial, $default_testimonial);

	/* Handle backwards compatibility */
	$testimonial['author_name'] = isset($testimonial['title']) ? $testimonial['title'] : $testimonial['author_name'];
	$testimonial['author_info'] = isset($testimonial['caption']) ? $testimonial['caption'] : $testimonial['author_info'];

	?>
	<pre><?= var_dump($testimonial); ?></pre>

	<tr class="<?php echo $row_class; ?>">
		<td width="40">
			<span class="id"><?php echo $position; ?></span>
			<div class="move-testimonial dashicons-before dashicons-sort"><br/></div>
		</td>
		<td width="360">
			<div>
				<label><?= __('Author name', 'ejo-simple-testimonials'); ?></label>
				<input type="text" class="testimonial-author-name" name="testimonials[<?php echo $position; ?>][author_name]" value="<?php echo $testimonial['author_name']; ?>" placeholder="<?= __('Author name', 'ejo-simple-testimonials'); ?>">
			</div>
			<div>
				<label><?= __('Extra Info', 'ejo-simple-testimonials'); ?></label>
				<input type="text" class="testimonial-author-info" name="testimonials[<?php echo $position; ?>][author_info]" value="<?php echo $testimonial['author_info']; ?>" placeholder="<?= __('Location, company...', 'ejo-simple-testimonials'); ?>">
			</div>
		</td>
		<td>
			<div>
				<label><?= __('Testimonial', 'ejo-simple-testimonials'); ?></label>
				<textarea class="testimonial-content" name="testimonials[<?php echo $position; ?>][content]" placeholder="<?= __('Testimonial', 'ejo-simple-testimonials'); ?>"><?php echo $testimonial['content']; ?></textarea>
			</div>
			<div>
				<label><?= __('Rating', 'ejo-simple-testimonials'); ?></label>
				...
			</div>
			<div>
				<label><?= __('Date', 'ejo-simple-testimonials'); ?></label>
				<input type="text" class="testimonial-date" name="testimonials[<?php echo $position; ?>][date]" value="<?php echo $testimonial['date']; ?>" placeholder="<?= __('Date', 'ejo-simple-testimonials'); ?>">
			</div>
		</td>
		<td width="40">
			<div class="remove-testimonial dashicons-before dashicons-dismiss"><br/></div>
		</td>
	</tr>

	<?php
}