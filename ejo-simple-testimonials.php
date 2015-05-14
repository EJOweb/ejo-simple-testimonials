<?php
/**
 * Plugin Name: EJO Simple Testimonials
 * Plugin URI: http://github.com/ejoweb/ejo-simple-testimonials
 * Description: Simple way of adding testimonials to your website
 * Version: 0.3.1
 * Author: Erik Joling
 * Author URI: http://www.ejoweb.nl/
 */

/**
 *
 */
final class EJO_Simple_Testimonials
{
    //* Version number of this plugin
    public static $version = '0.3.1';

    //* Holds the instance of this class.
    private static $_instance = null;

    //* Store the slug of this plugin
    public static $slug = 'ejo-simple-testimonials';

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
        //* Setup
        self::setup();

        // Include required files
        self::includes();

        //* Admin
        EJO_Simple_Testimonials_Admin::instance();

        //* Widget
        add_action( 'widgets_init', array( $this, 'manage_widgets' ) );

        //* Add shortcode
        add_shortcode( 'simple_testimonials', array( $this, 'testimonials_shortcode' ) );
        add_shortcode( 'simple-testimonials', array( $this, 'testimonials_shortcode' ) );
   }

    //* Setup
    private static function setup() 
    {
        //* Path & Url
        self::$dir = plugin_dir_path( __FILE__ );
        self::$uri = plugin_dir_url(  __FILE__ );
    }

    //* Includes
    private static function includes() 
    {
        //* Widget class
        include_once( self::$dir . 'includes/class-widget.php' );

        //* Admin class
        include_once( self::$dir . 'includes/class-admin.php' );
    }

    //* Manage this plugins widgets
    public function manage_widgets() 
    { 
        register_widget( 'EJO_Simple_Testimonials_Widget' ); 
    }

    //* Show testimonials
    public static function get_testimonials_output() 
    {
        //* Get testimonials
		$testimonials = self::get_testimonials();

        //* Abort if no testimonials available
        if (empty($testimonials))
            return 'No testimonials available';

        $output = '';
        $output .= '<div class="testimonials-container">';
        foreach ($testimonials as $testimonial) {

            $output .= '<article class="testimonial">';

            if (!empty($testimonial['title']))
                $output .= '<h4 class="testimonial-title">' . stripslashes($testimonial['title']) . '</h4>';

            $output .= '<blockquote class="testimonial">' . stripslashes($testimonial['content']) . '</blockquote>';

            if (!empty($testimonial['caption']))
                $output .= '<p class="testimonial-caption">' . stripslashes($testimonial['caption']) . '</p>';

            $output .= '</article>';
        }
        $output .= '</div>';

        return $output;
    }

    //* Get testimonials
    public static function get_testimonials() 
    {
        //* Get Testimonials
        $testimonials = get_option( '_ejo_simple_testimonials', array() );

        return $testimonials;
    }

    // Shortcode Function to show Vsee link
	public static function testimonials_shortcode() 
	{
		$output = self::get_testimonials_output();

		return $output;
	}
}

EJO_Simple_Testimonials::instance();
