<?php

final class EJO_Simple_Testimonials_Admin
{
    //* Holds the instance of this class.
    private static $_instance = null;

    //* Returns the instance.
    public static function instance() 
    {
        if ( !self::$_instance )
            self::$_instance = new self;
        return self::$_instance;
    }

    //* Plugin setup.
    protected function __construct() 
    {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts_and_styles' ) ); 
    }

    public function admin_menu(){
        add_theme_page('Simple Testimonials', 'Simple Testimonials', 'edit_theme_options', EJO_Simple_Testimonials::$slug, array( $this, 'admin_page' ) );
    }

    public function admin_page()
    {
        include_once('admin-page.php');
    }

    public function register_admin_scripts_and_styles() {
        if (isset($_GET['page']) && $_GET['page'] == EJO_Simple_Testimonials::$slug) {
            wp_enqueue_script( 'ejo-simple-testimonials-admin-js', EJO_Simple_Testimonials::$uri . 'includes/js/admin.js', array('jquery', 'jquery-ui-sortable') );

            wp_enqueue_style( 'ejo-simple-testimonials-admin', EJO_Simple_Testimonials::$uri . 'includes/css/admin.css' );
        }
    }

}