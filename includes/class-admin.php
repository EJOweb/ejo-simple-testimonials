<?php

final class EJO_Simple_Testimonials_Admin
{
    //* Holds the instance of this class.
    private static $_instance = null;

    //* Store the slug of this plugin
    public static $slug;

    //* Stores the directory path for this plugin.
    public static $dir;

    //* Stores the directory URI for this plugin.
    public static $uri;

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
        /* Setup */
        self::setup();

        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts_and_styles' ) ); 
    }

    //* Setup
    private static function setup() 
    {
        //* Setup main variables
        self::$dir = EJO_Simple_Testimonials::$dir;
        self::$uri = EJO_Simple_Testimonials::$uri;
        self::$slug = EJO_Simple_Testimonials::$slug; 
    }

    public function admin_menu() 
    {
        //* Allow capability for menu page to be filtered
        $cap = apply_filters( 'ejo_simple_testimonials_cap', 'edit_theme_options' );

        add_theme_page('Simple Testimonials', 'Simple Testimonials', $cap, self::$slug, array( $this, 'admin_page' ) );
    }

    public function admin_page()
    {
        include_once('admin-page.php');
    }

    public function register_admin_scripts_and_styles() {
        if (isset($_GET['page']) && $_GET['page'] == self::$slug) {
            wp_enqueue_script( 'ejo-simple-testimonials-admin-js', self::$uri . 'includes/js/admin.js', array('jquery', 'jquery-ui-sortable') );

            wp_enqueue_style( 'ejo-simple-testimonials-admin', self::$uri . 'includes/css/admin.css' );
        }
    }

}