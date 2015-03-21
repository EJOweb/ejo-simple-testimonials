<div class='wrap' style="max-width:960px;">
	<h2>Referenties</h2>

	<?php

		//* options record met een lijst attachment id's en bijbehorende urls

		//* Nonces shizzle

		if ( isset($_POST['submit']) ) {

			//* Create temporary array of send referenties
			$tmp_referenties = (isset($_POST['referenties'])) ? $_POST['referenties'] : array();

			//* Iterativly stack referenties
			$referenties = array();
			foreach ($tmp_referenties as $position => $referentie) {
				$referenties[] = $referentie;
			}

			update_option( 'theme_options_referenties', $referenties );
			echo '<div id="message" class="updated"><p><strong>De referenties zijn opgeslagen.</strong></p></div>';
			// echo '<pre>';print_r($referenties);echo '</pre>';
		}

		$referenties = get_option( 'theme_options_referenties' );
		$referenties = ($referenties !== false) ? $referenties : array();
		$referentie_count = count($referenties);
		// echo '<pre>';print_r($referenties);echo '</pre>';
	?>

	<!-- Referentie Clone -->
	<table style="display:none;">
		<tr class="referentie-clone">
			<td width="40">
				<div class="move-referentie dashicons-before dashicons-sort"><br/></div>
			</td>
			<td width="180">
				<label>Titel</label>
				<input type="text" class="referentie-title" name="referenties[<?php echo $referentie_count; ?>][title]" value="" placeholder="Titel">
			</td>
			<td>
				<label>Inhoud</label>
				<textarea class="referentie-content" name="referenties[<?php echo $referentie_count; ?>][content]" placeholder="Referentie"></textarea>
			</td>
			<td width="200">
				<label>Bijschrift</label>
				<input type="text" class="referentie-caption" name="referenties[<?php echo $referentie_count; ?>][caption]" value="" placeholder="Info...">
			</td>
			<td width="40">
				<div class="remove-referentie dashicons-before dashicons-dismiss"><br/></div>
			</td>
		</tr>
	</table>

	<form action="admin.php?page=referenties" method="post">
		<!-- <input id="_wpnonce" type="hidden" value="" name="_wpnonce"> -->
		<p>
			<input id="" class="button button-primary" type="submit" value="Wijzigingen opslaan" name="submit">
			<a href="" class="button">Reset</a>
			<a id="add_referentie" href="javascript:void(0)" class="button">Referentie toevoegen</a>
		</p>

		<table class="form-table wp-list-table widefat referenties-table">
			<tbody>
<?php
				foreach ($referenties as $position => $referentie) {
?>
					<tr class="referentie">
						<td width="40">
							<div class="move-referentie dashicons-before dashicons-sort"><br/></div>
						</td>
						<td width="180">
							<label>Titel</label>
							<input type="text" class="referentie-title" name="referenties[<?php echo $position; ?>][title]" value="<?php echo stripslashes($referentie['title']); ?>" placeholder="Titel">
						</td>
						<td>
							<label>Inhoud</label>
							<textarea class="referentie-content" name="referenties[<?php echo $position; ?>][content]" placeholder="Referentie"><?php echo stripslashes($referentie['content']); ?></textarea>
						</td>
						<td width="200">
							<label>Bijschrift</label>
							<input type="text" class="referentie-caption" name="referenties[<?php echo $position; ?>][caption]" value="<?php echo stripslashes($referentie['caption']); ?>" placeholder="Info...">
						</td>
						<td width="40">
							<div class="remove-referentie dashicons-before dashicons-dismiss"><br/></div>
						</td>
					</tr>
<?php
				}
?>
			</tbody>
		</table>
		<?php submit_button( 'Wijzigingen opslaan' ); ?>
	</form>

</div>
