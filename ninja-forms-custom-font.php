<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: Ninja Forms - Custom Font
 * Description: Adds a form setting for specifying a custom font family.
 * Version: 0.0.1
 * Author: Kyle B. Johnson
 * Author URI: http://kylebjohnson.me
 * Text Domain: ninja-forms-custom-font
 *
 * Copyright 2016 Kyle B. Johnson.
 */

if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3.0', '>' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

    include 'deprecated/ninja-forms-custom-font.php';

} else {

    /**
     * Class NF_CustomFont
     */
    final class NF_CustomFont
    {
        const VERSION = '0.0.1';
        const SLUG    = 'custom-font';
        const NAME    = 'Custom Font';
        const PREFIX  = 'NF_CustomFont';

        /**
         * @var NF_CustomFont
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_CustomFont Highlander Instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof NF_CustomFont)) {
                self::$instance = new NF_CustomFont();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register( array( self::$instance, 'autoloader' ) );
            }

            return self::$instance;
        }

        public function __construct()
        {
            add_action( 'ninja_forms_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_filter( 'ninja_forms_from_display_settings', array( $this, 'from_display_settings' ) );
        }

        public function enqueue_scripts( $data )
        {
            $form = Ninja_Forms()->form( $data[ 'form_id' ] )->get();

            $font_family = $form->get_setting( 'custom_font' );

            NF_CustomFont()->template( 'custom-font-css.php', compact( 'font_family' ) );
        }

        public function from_display_settings( $settings )
        {
            $new_settings = NF_CustomFont()->config( 'FormDisplaySettings' );
            return array_merge( $settings, $new_settings );
        }

        /*
         * Optional methods for convenience.
         */

        public function autoloader($class_name)
        {
            if (class_exists($class_name)) return;

            if ( false === strpos( $class_name, self::PREFIX ) ) return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }
        
        /**
         * Template
         *
         * @param string $file_name
         * @param array $data
         */
        public static function template( $file_name = '', array $data = array() )
        {
            if( ! $file_name ) return;

            extract( $data );

            include self::$dir . 'includes/Templates/' . $file_name;
        }
        
        /**
         * Config
         *
         * @param $file_name
         * @return mixed
         */
        public static function config( $file_name )
        {
            return include self::$dir . 'includes/Config/' . $file_name . '.php';
        }

    }

    /**
     * The main function responsible for returning The Highlander Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Highlander Instance
     */
    function NF_CustomFont()
    {
        return NF_CustomFont::instance();
    }

    NF_CustomFont();
}
