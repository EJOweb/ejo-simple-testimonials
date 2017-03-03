<div class='wrap' style="max-width:960px;">
	<h2>Simple Testimonials</h2>

	<?php

		//* options record met een lijst attachment id's en bijbehorende urls

		//* Nonces shizzle

		if ( isset($_POST['submit']) ) {

			/* Create temporary array of send testimonials */
			$testimonials = (isset($_POST['testimonials'])) ? $_POST['testimonials'] : array();

			/* To save the order of the testimonials, reset the keys */
			$testimonials = array_values($testimonials);

			/* ?><pre><?= var_dump($testimonials); ?></pre><?php */

			/* Saving */
			update_option( '_ejo_simple_testimonials', $testimonials );
			echo '<div id="message" class="updated"><p><strong>De testimonials zijn opgeslagen.</strong></p></div>';
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
function admin_show_simple_testimonial( $position = 0, $testimonial = array() )
{
	/* Define the class of the container */
	$row_class           = empty($testimonial) ? 'clone' : 'testimonial';

	/* Create default testimonial values */
	$default_testimonial = array(
		'author'	=> array(
			'name' 		=> '',
			'location' 	=> '',
			'jobtitle' 	=> '',
			'company' 	=> '',
		),
		'content' 	=> '',
		'rating' 	=> '',
		'date' 		=> '',
	);

	/* Process the testimonial values */
	$testimonial = wp_parse_args($testimonial, $default_testimonial);

	/* Handle backwards compatibility */
	$testimonial['author']['name'] = isset($testimonial['title']) ? $testimonial['title'] : $testimonial['author']['name'];
	$testimonial['author']['location'] = isset($testimonial['caption']) ? $testimonial['caption'] : $testimonial['author']['location'];

	?>
	<pre><?= var_dump($testimonial); ?></pre>
	

	<tr class="<?php echo $row_class; ?>">
		<td width="40">
			<span class="id"><?php echo $position; ?></span>
			<div class="move-testimonial dashicons-before dashicons-sort"><br/></div>
		</td>
		<td width="360">
			<div>
				<label>Naam</label>
				<input type="text" class="testimonial-author-name" name="testimonials[<?php echo $position; ?>][author][name]" value="<?php echo $testimonial['author']['name']; ?>" placeholder="Naam">
			</div>
			<div>
				<label>Locatie</label>
				<input type="text" class="testimonial-author-location" name="testimonials[<?php echo $position; ?>][author][location]" value="<?php echo $testimonial['author']['location']; ?>" placeholder="Locatie">
			</div>
			<div>
				<label>Functietitel</label>
				<input type="text" class="testimonial-author-jobtitle" name="testimonials[<?php echo $position; ?>][author][jobtitle]" value="<?php echo $testimonial['author']['jobtitle']; ?>" placeholder="Functietitel">
			</div>
			<div>
				<label>Bedrijf</label>
				<input type="text" class="testimonial-author-company" name="testimonials[<?php echo $position; ?>][author][company]" value="<?php echo $testimonial['author']['company']; ?>" placeholder="Bedrijf">
			</div>
		</td>
		<td>
			<div>
				<label>Referentie</label>
				<textarea class="testimonial-content" name="testimonials[<?php echo $position; ?>][content]" placeholder="Referentie"><?php echo $testimonial['content']; ?></textarea>
			</div>
			<div>
				<label>Rating</label>
				...
			</div>
			<div>
				<label>Datum</label>
				<input type="text" class="testimonial-date" name="testimonials[<?php echo $position; ?>][date]" value="<?php echo $testimonial['date']; ?>" placeholder="Datum">
			</div>
		</td>
		<td width="40">
			<div class="remove-testimonial dashicons-before dashicons-dismiss"><br/></div>
		</td>
	</tr>

	<?php
}