<?php

   /*
    Plugin Name:  Mortgage Calculators WP
    Plugin URI:   https://mortgagecalculatorsplugin.com
    Description:  A contemporary set of mortgage calculators from Lenderd.com
    Version:      1.56
    Author:       Lenderd
    Author URI:   https://lenderd.com
    License:      GPL2
    License URI:  https://www.gnu.org/licenses/gpl-2.0.html
    Text Domain:  wpmc
    Domain Path:  /languages
    */
    // Blocking direct access to your plugin PHP files
    defined('ABSPATH') or die('No script kiddies please!');
    define('MC_PATH', plugin_dir_path(__FILE__));
    define('MC_URL', plugin_dir_url(__FILE__));
    // Load common  functions
    require(dirname(__FILE__).'/includes/functions/functions.php');
    // Load template functions
    require_once(dirname(__FILE__).'/includes/templates/templates.php');
    // Load options functions
    require_once(dirname(__FILE__).'/includes/options/options.php');
    require_once(dirname(__FILE__).'/includes/shortcodes/mcwp.php');
    // Load update network option functions
    require_once(dirname(__FILE__).'/includes/options/update_network_options.php');
    // Runs when plugin is activated
    register_activation_hook(__FILE__, 'mortgage_calculator_install');
    // Runs on plugin deactivation
    register_deactivation_hook(__FILE__, 'mortgage_calculator_remove');


    function custom_theme_setup()
    {
        load_plugin_textdomain('wpmc', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    } // end custom_theme_setup
    add_action('after_setup_theme', 'custom_theme_setup');


    function mortgage_calculator_install()
    {
        // do something when plugin is activated or installed
    }
    function mortgage_calculator_remove()
    {
        // do something when plugin is deactivated or removed
    }
    // Load CSS & JS Files
    function mcwp_enqueue()
    {
        wp_enqueue_script('wpmc_slider', plugin_dir_url(__FILE__).'assets/bootstrap-slider/bootstrap-slider.js', array( 'jquery' ), null, true);
        wp_enqueue_script('wpmc_calculator', plugin_dir_url(__FILE__).'assets/js/wpmc.js', array( 'jquery' ), null, true);
        wp_enqueue_style('wpmc_slider_css', plugin_dir_url(__FILE__).'assets/bootstrap-slider/bootstrap-slider.css');
        wp_enqueue_style('wpmc_slider', plugin_dir_url(__FILE__).'assets/css/wpmc.css');
        wp_localize_script('wpmc_calculator', 'mcwp_ajax', array(
          'ajaxurl' => admin_url('admin-ajax.php'),
          'calc_res' => __('Your calculations are on the way to your inbox!', 'wpmc'),
        ));
    }

    add_action('admin_enqueue_scripts', 'softlights_admin_scripts');
    function softlights_admin_scripts($hook)
    {
        /*
         if('appearance_page_sl-theme-options' != $hook) {
            return;
         }
         */
        wp_enqueue_style('mcwp-css', plugin_dir_url(__FILE__).'admin/admin.css');
        wp_enqueue_script('jquery');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wpmc-script-handle', plugin_dir_url(__FILE__).'admin/admin.js', array( 'wp-color-picker','jquery' ), false, true);
    }

     add_action('wp_head', function () {
         $option_func = (use_network_settings('wpmc_mail_use_network_settings') === 'yes') ? 'get_site_option' : 'get_option';
         //mcwp_color
         $mcwp_color = $option_func('mcwp_color');
		 ?>
         <style type="text/css">.mcalc-color,.mcalc .slider-handle.round,.mcalc .slider.slider-horizontal .slider-selection{background:<?php echo esc_attr($mcwp_color); ?> !important;}</style>
		 <?php
     });


    add_action("wp_enqueue_scripts", "mcwp_enqueue", 11);

    if (is_network_admin()) {
        add_filter('network_admin_menu', 'wpmc_network_admin_menu');
        function wpmc_network_admin_menu()
        {
            add_menu_page(
                __('Mortage Calculator', 'wpmc'),
                __('Calculator', 'wpmc'),
                'manage_options',
                'wpmc',
                'mortgage_calculator_html_page',
                plugin_dir_url(__FILE__) . 'assets/images/calculator.png',
                20
            );
        }
    }

    // Create Top Level Menu & Sub Menu
    if (is_admin()) {
        add_action('admin_menu', 'mortgage_calculator_admin_menu');

        function mortgage_calculator_admin_menu()
        {
            add_menu_page(
                __('Mortage Calculator', 'wpmc'),
                __('Calculator', 'wpmc'),
                'manage_options',
                'wpmc',
                'mortgage_calculator_html_page',
                plugin_dir_url(__FILE__) . 'assets/images/calculator.png',
                20
            );
        }
    }
    // Create Tabs Template
    function mortgage_calculator_html_page()
    {
        wpmc_main_template(); // Load the Main template html
    }
    add_action('admin_init', 'wpmc_admin_init');
    // Remove error:: JQMIGRATE: Migrate is installed, version 1.4.1
    add_action('wp_default_scripts', function ($scripts) {
        if (! empty($scripts->registered['jquery'])) {
            $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, array( 'jquery-migrate' ));
        }
    });

    if (isset($_GET['settings-updated'])) {
        function wpmc_admin_notice__success()
        {
            $msg = __('Settings saved.', 'wpmc');
            //echo esc_html('<div class="updated notice"><p>'.$msg.'</p></div>');
        }
        add_action('network_admin_notices', 'wpmc_admin_notice__success');
        add_action('admin_notices', 'wpmc_admin_notice__success');
    }
