<?php
/**
 * Plugin Name:     EJO Simple Testimonials
 * Plugin URI:      http://github.com/ejoweb/ejo-simple-testimonials
 * Description:     Simple way of adding testimonials to your website
 * Version:         0.5
 * Author:          Erik Joling
 * Author URI:      http://www.ejoweb.nl/
 * Text Domain:     ejo-simple-testimonials
 * Domain Path:     /languages
 *
 * GitHub Plugin URI: http://github.com/EJOweb/ejo-simple-testimonials
 * GitHub Branch:     basiswebsite
 */

/**
 *
 */
final class EJO_Simple_Testimonials
{
    //* Version number of this plugin
    public static $version = '0.5';

    //* Holds the instance of this class.
    private static $_instance = null;

    //* Store the unique identifier of this plugin
    public static $id = 'ejo-simple-testimonials';

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
        //* Setup
        self::setup();

        // Include required files
        self::includes();

        //* Admin
        EJO_Simple_Testimonials_Admin::instance();

        //* Widget
        add_action( 'widgets_init', array( 'EJO_Simple_Testimonials', 'manage_widgets' ) );

        //* Add shortcode
        add_shortcode( 'simple_testimonials', array( 'EJO_Simple_Testimonials', 'testimonials_shortcode' ) );
   }

    //* Setup
    private static function setup() 
    {
        //* Setup main variables
        self::$dir = plugin_dir_path( __FILE__ );
        self::$uri = plugin_dir_url(  __FILE__ );
        self::$slug = self::$id; 

        /* Load the translation for the plugin */
        load_plugin_textdomain( 'ejo-simple-testimonials', false, self::$slug . '/languages' );
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
    public static function manage_widgets() 
    { 
        register_widget( 'EJO_Simple_Testimonials_Widget' ); 
    }

    //* Get testimonials
    public static function get_testimonials()
    {
        //* Get Testimonials
        $testimonials = get_option( '_ejo_simple_testimonials', array() );

        return $testimonials;
    }

   public static function the_testimonial($testimonial, $char_limit = '0')
    {
        ?>
        <div class="testimonial" itemscope itemtype="http://schema.org/Review">

            <div style="display: none" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Organization">
                <span itemprop="name"><?= get_bloginfo('name'); ?></span>
            </div>

            <?php if ( '' != $testimonial['author_name'] ) : ?>

                <div class="author" itemprop="author" itemscope itemtype="http://schema.org/Person">
                    <h4 class="author-name" itemprop="name"><?= $testimonial['author_name']; ?></h4>

                    <?php if ( '' != $testimonial['author_info'] ) : ?>

                        <span class="author-info"><?= $testimonial['author_info']; ?></span>

                    <?php endif; ?>
                </div>

            <?php endif; ?>
            
            <?php if ( '' != $testimonial['review_rating'] ) : ?>

                <div class="review-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                    <span itemprop="ratingValue"><?= $testimonial['review_rating']; ?></span>
                </div>

            <?php endif; ?>

            <?php if ( '' != $testimonial['review_content'] ) : ?>

                <?php 
                $testimonial['review_content'] = EJO_Simple_Testimonials::process_character_limit($testimonial['review_content'], $char_limit); 
                ?>

                <div class="review-content">
                    <blockquote class="" itemprop="reviewBody"><?= $testimonial['review_content']; ?></blockquote>
                </div>

            <?php endif; ?>

            <?php if ( '' != $testimonial['review_date'] ) : ?>

                <span class="">
                    <meta itemprop="datePublished" content="<?= $testimonial['review_date']; ?>">
                    <?= date_i18n( get_option( 'date_format' ), strtotime( $testimonial['review_date'] ) ); ?>
                </span>

            <?php endif; ?>

        </div>
        <?php
    }

    public static function process_character_limit($content, $char_limit = '0')
    {
        //* Limit number of characters
        if ( $char_limit > 0 ) {

            if ( strlen($content) > $char_limit ) {
                $content = substr($content, 0, $char_limit) . '...';
            }
        }

        return $content;
    }

    //* Show testimonials
    public static function testimonials_shortcode() 
    {
        //* Get testimonials
        $testimonials = self::get_testimonials();

        ob_start(); 
        ?>

        <ul class="testimonials">

            <?php if ( !empty($testimonials) ) : ?>

                <?php foreach ($testimonials as $testimonial) : //* Loop through testimonials ?>

                    <li>
                        <?php EJO_Simple_Testimonials::the_testimonial($testimonial); ?>
                    </li>

                <?php endforeach; ?>
                
            <?php else : ?>
            
                <li>No testimonials available</li>
            
            <?php endif; ?>

        </ul>

        <?php  
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}

EJO_Simple_Testimonials::instance();
