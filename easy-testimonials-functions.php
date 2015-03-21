<?php

function get_referenties() 
{
	//* Get referenties-data
	$referenties = get_option( 'theme_options_referenties' );
	$referenties = ($referenties !== false) ? $referenties : array();

	//* Abort if no referenties available
	if (empty($referenties))
		return;

	$output = '';
	$output .= '<div class="testimonials-container">';
	foreach ($referenties as $referentie) {

		$output .= '<article class="testimonial">';

		if (!empty($referentie['title']))
			$output .= '<h4 class="testimonial-title">' . stripslashes($referentie['title']) . '</h4>';

			$output .= '<blockquote class="testimonial">' . stripslashes($referentie['content']) . '</blockquote>';

		if (!empty($referentie['caption']))
			$output .= '<p class="testimonial-caption">' . stripslashes($referentie['caption']) . '</p>';

		$output .= '</article>';
		$output .= '<hr>';
	}
	$output = rtrim($output, '<hr>');
	$output .= '</div>';

	return $output;
}