<?php

add_action( 'admin_menu', 'register_referenties_admin_menu' );
add_action( 'admin_enqueue_scripts', 'referenties_admin_scripts_and_styles' ); 
// add_action( 'widgets_init', 'register_referenties_widget' );

function register_referenties_admin_menu(){
	add_submenu_page(CHILD_SLUG, 'Referenties', 'Referenties', 'edit_theme_options', 'referenties', 'referenties_admin_content');
}

function referenties_admin_content()
{
	include_once('referenties-admin.php');
}

function referenties_admin_scripts_and_styles() {
    if (isset($_GET['page']) && $_GET['page'] == 'referenties') {
		//* Add necessary media-library scripts
        wp_enqueue_media();
        wp_register_script('referenties-admin-js', CHILD_INC_URL . '/theme-options/referenties/js/referenties-admin.js', array('jquery'));
        wp_enqueue_script('referenties-admin-js');

        wp_enqueue_style( 'referenties-admin-css', CHILD_INC_URL . '/theme-options/referenties/css/referenties-admin.css' );
    }
}

//* Register Referenties Widget
function register_referenties_widget() 
{ 
	register_widget( 'Referenties_Widget' ); 
}

//* Include Widget Classes
// include_once( 'referenties-widget.php' );

//* Include Referenties functions
include_once( 'referenties-functions.php' );