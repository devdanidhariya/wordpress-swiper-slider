<?php
/*
Plugin Name: WordPress Swiper Slider
Plugin URI: https://github.com/devdanidhariya/wordpress-swiper-slider
Description: Responsive slider plugin to create sliders. Build beautiful image slider with lazy loading.
Version: 1.0.0
Author: Devidas Danidhariya
Author URI: https://devidas.in/
Text Domain: swiper-slider
Domain Path: /languages
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('WSD') ){

    class WSD {
    	
    	/** @var string The plugin version number */
    	var $version = '1.0.0';
    	
    	/*
    	*  __construct
    	*
    	*  A constructor to ensure is only initialized once
    	*
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	N/A
    	*/
    	
    	public function __construct() {
                // constants
                define( 'WSD_PATH',$path );
                
                // vars
                
                $this->basename = plugin_basename( __FILE__ );
                $this->path = plugin_dir_path( __FILE__ );
                $this->url = plugin_dir_url( __FILE__ );
                $this->slug = dirname($basename);
                $this->lib = $this->url.'lib/';
                
                
    	}
    	
    	
    	/*
    	*  initialize
    	*
    	*  Use for initialize funcatinality
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	N/A
    	*/
    		
    	public function initialize() {
                
                // hooks
                register_activation_hook( __FILE__, array( $this, 'install' ) );
                
                
                // File Include
                require_once (WSD_PATH . 'class/admin-slider.php');
                require_once (WSD_PATH . 'class/wsd-shortcode.php');

                
                // Action hooks
                add_action( 'admin_menu', array( $this, 'add_options_page' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ));
                add_action('init',	array($this, 'register_post_types'));
                add_action('admin_head', array('adminSlider','wsd_add_mce_button'));
                add_action('admin_footer', array('adminSlider', 'add_html_in_footer'), 11);
                
                add_action( 'wp_ajax_slider_form_submit', array('adminSlider','slider_form_submit' ));
                add_action( 'wp_ajax_slider_delete', array('adminSlider','slider_delete' ));
                add_action( 'wp_ajax_slider_preview', array('adminSlider','slider_preview' ));
                
                
                // Fliter hooks
                add_filter( 'plugin_action_links', array( $this, 'add_setting_link' ), 10, 2 );
                
                //shortcode
                add_shortcode( 'slideshow', array('wspShortcode','slideshow_fun') );

                
            }
            
            /*
    	*  install
    	*
    	*  At plugin active time call
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	N/A
    	*/
            
            public function install(){
                
            }
            
            public function add_options_page(){
                
                add_menu_page('Slider Setting', 'Swiper Slider', 'manage_options','slide-short-code-list',array( 'adminSlider', 'displayShortcodeList'),'dashicons-slides');
                add_submenu_page( 'slide-short-code-list', 'Add Slider', 'Add Slider','manage_options', 'add-slider',array('adminslider','addSlider'));
                add_submenu_page( NULL, 'Add Slider', 'Add Slider','manage_options', 'edit-slider',array('adminslider','editSlider'));
                
            }
            
            
            /**
            * Enqueue scripts and styles
            */
            public function load_custom_wp_admin_style($hook){
                
                // include js css file in particuler pages
                

                $page_list = array('toplevel_page_slide-short-code-list','swiper-slider_page_add-slider','admin_page_edit-slider');
                
                if(in_array($hook, $page_list)){
                    // JS
                    wp_register_script('wsd_jquery', $this->lib.'js/jquery.min.js');
                    wp_enqueue_script('wsd_jquery');
                    
                    wp_register_script('wsd_bootstrap', $this->lib.'js/bootstrap.min.js');
                    wp_enqueue_script('wsd_bootstrap');
                    wp_register_script('wsd_datatables', $this->lib.'js/datatables.min.js');
                    wp_enqueue_script('wsd_datatables');
                    
                    wp_register_script('wsd_media_js', $this->lib.'js/main.js');
                    wp_enqueue_script('wsd_media_js');
                    
                    wp_enqueue_media();


                    // CSS
                    
                    
                    wp_register_style('wsd_bootstrap', $this->lib.'css/bootstrap.min.css');
                    wp_enqueue_style('wsd_bootstrap');
                    
                    wp_register_style('wsd_datatable', $this->lib.'css/datatables.min.css');
                    wp_enqueue_style('wsd_datatable');
                    
                    wp_register_style('wsd_fontawesome', '//use.fontawesome.com/releases/v5.8.1/css/all.css');
                    wp_enqueue_style('wsd_fontawesome');
                }
                
            }
            
            
            /*
    	*  register_post_types
    	*
    	*  This function will register post types 
    	*
    	*  @param	n/a
    	*  @return	n/a
    	*/
    	
    	public function register_post_types() {
                
                // register post type 'wsd-slider'
                register_post_type('wsd-slider', array(
                        'labels'			=> array(
                            'name'					=> __( 'Swiper Slider', 'swiper-slider' ),
                            'singular_name'			=> __( 'Swiper Slider', 'swiper-slider' ),
                            'add_new'				=> __( 'Add New' , 'swiper-slider' ),
                            'add_new_item'			=> __( 'Add New Slider' , 'swiper-slider' ),
                            'edit_item'				=> __( 'Edit Slider' , 'swiper-slider' ),
                            'new_item'				=> __( 'New Slider' , 'swiper-slider' ),
                            'view_item'				=> __( 'View Slider', 'swiper-slider' ),
                            'search_items'			=> __( 'Search Slider', 'swiper-slider' ),
                            'not_found'				=> __( 'No Slider found', 'swiper-slider' ),
                            'not_found_in_trash'	=> __( 'No Slider found in Trash', 'swiper-slider' ), 
                        ),
                        'public'			=> false,
                        'show_ui'			=> false,
                        '_builtin'			=> false,
                        'capability_type'	=> 'post',
                        'hierarchical'		=> false,
                        'rewrite'			=> false,
                        'query_var'			=> false,
                        'supports' 			=> array('title'),
                        'show_in_menu'		=> false,
                ));
    		
    		
    		
    	}
            
            /**
            * add setting and about us link in plugin 
            */
            public function add_setting_link($links, $file){
                if ( $file == plugin_basename(dirname(__FILE__) . '/wordpress-swiper-slider.php') ) {
                    $setting = '<a href="admin.php?page=slide-short-code-list" >' . __('Settings','swiper-slider') . '</a>';
                    $about = '<a href="https://github.com/devdanidhariya/wordpress-swiper-slider"  target="_blank">' . __('About','swiper-slider') . '</a>';
                    array_unshift($links, $setting,$about);
                }
                return $links;
            }
    }

    $wsd = new WSD();
    $wsd->initialize();
} // class_exists check                