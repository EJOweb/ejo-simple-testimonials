<div class='wrap' style="max-width:960px;">
	<h2>Simple Testimonials</h2>

	<?php

		//* options record met een lijst attachment id's en bijbehorende urls

		//* Nonces shizzle

		if ( isset($_POST['submit']) ) {

			//* Create temporary array of send testimonials
			$tmp_testimonials = (isset($_POST['testimonials'])) ? $_POST['testimonials'] : array();

			//* Iterativly stack testimonials
			$testimonials = array();
			foreach ($tmp_testimonials as $position => $testimonial) {
				$testimonials[] = $testimonial;
			}

			update_option( '_ejo_simple_testimonials', $testimonials );
			echo '<div id="message" class="updated"><p><strong>De testimonials zijn opgeslagen.</strong></p></div>';
			// echo '<pre>';print_r($testimonials);echo '</pre>';
		}

		$testimonials = get_option( '_ejo_simple_testimonials' );
		$testimonials = (!empty($testimonials)) ? $testimonials : array();
		// echo '<pre>';print_r($testimonials);echo '</pre>';
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
				write_log($testimonials);
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

</div>

<?php
function admin_show_simple_testimonial( $position = 0, $testimonial = array() )
{
	//* Variables
	$row_class           = ($testimonial) ? 'testimonial' : 'clone';
	$testimonial_title   = ($testimonial) ? stripslashes($testimonial['title']) : '';
	$testimonial_content = ($testimonial) ? stripslashes($testimonial['content']) : '';
	$testimonial_caption = ($testimonial) ? stripslashes($testimonial['caption']) : '';
	?>

	<tr class="<?php echo $row_class; ?>">
		<td width="40">
			<span class="id"><?php echo $position; ?></span>
			<div class="move-testimonial dashicons-before dashicons-sort"><br/></div>
		</td>
		<td width="180">
			<label>Titel</label>
			<input type="text" class="testimonial-title" name="testimonials[<?php echo $position; ?>][title]" value="<?php echo $testimonial_title; ?>" placeholder="Titel">
		</td>
		<td>
			<label>Inhoud</label>
			<textarea class="testimonial-content" name="testimonials[<?php echo $position; ?>][content]" placeholder="Referentie"><?php echo $testimonial_content; ?></textarea>
		</td>
		<td width="200">
			<label>Bijschrift</label>
			<input type="text" class="testimonial-caption" name="testimonials[<?php echo $position; ?>][caption]" value="<?php echo $testimonial_caption; ?>" placeholder="Info...">
		</td>
		<td width="40">
			<div class="remove-testimonial dashicons-before dashicons-dismiss"><br/></div>
		</td>
	</tr>

	<?php
}