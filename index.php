<?php
/**
* Plugin Name: Invoice Creator
* Plugin URI: https://pravelsolutions.com/PluginDemo
* Description: Easy way to create invoice for your customer
* Version: 1.0.0
* Author: Pravel Solutions
* Author URI: https://pravelsolutions.com/
**/

function pravel_invoice_init() {
	
	
	include(ABSPATH . "wp-includes/pluggable.php"); 

	define( 'PRAVEL_INVOICE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'PRAVEL_INVOICE_PLUGIN_VERSION', '1.0.0' );
	define( 'PRAVEL_INVOICE_PLUGIN_URL', plugin_dir_url(__FILE__) );
	define( 'PRAVEL_INVOICE_PLUGIN_BASENAME', plugin_basename(__FILE__) );
	
	
	//Include Files
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'lib/core-function.php' );
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'lib/new-post-type.php' );
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'lib/user-manage-function.php' );
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'template/invoice-form.php' );
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'template/registration.php' );
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'template/login.php' );
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'template/forgot-password.php' );
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'template/shop-product.php' );
	include_once( PRAVEL_INVOICE_PLUGIN_DIR . 'template/edit-account.php' );
	
	
	
	//Add assests file
	function pravel_invoice_load_scripts_front($hook)  {
		
		wp_enqueue_script("jquery-ui-datepicker");
		
		wp_enqueue_style( 'pravel_invoice_stylesheet', PRAVEL_INVOICE_PLUGIN_URL . 'assets/css/style.min.css', array(), PRAVEL_INVOICE_PLUGIN_VERSION);
		
		wp_enqueue_style( 'pravel_invoice_style_datatable', PRAVEL_INVOICE_PLUGIN_URL . 'assets/css/jquery.dataTables.css' , array(), PRAVEL_INVOICE_PLUGIN_VERSION);
		
		wp_enqueue_script( 'pravel_invoice_repeater', PRAVEL_INVOICE_PLUGIN_URL . 'assets/js/pravel-repeater.js' , array(),  PRAVEL_INVOICE_PLUGIN_VERSION, true);
		
		wp_enqueue_script( 'pravel_invoice_otp', PRAVEL_INVOICE_PLUGIN_URL . 'assets/js/pravel-verification-code.min.js' , array(),  PRAVEL_INVOICE_PLUGIN_VERSION, true);
		
		wp_enqueue_script( 'pravel_invoice_sweetalert', PRAVEL_INVOICE_PLUGIN_URL . 'assets/js/pravel_sweetalert.min.js' , array(),  PRAVEL_INVOICE_PLUGIN_VERSION, true);
		
		wp_enqueue_script( 'pravel_invoice_jquery_datatable', PRAVEL_INVOICE_PLUGIN_URL . 'assets/js/jquery.dataTables.js' , array(),  PRAVEL_INVOICE_PLUGIN_VERSION, true);
		
		wp_enqueue_script( 'pravel_invoice_jquery', PRAVEL_INVOICE_PLUGIN_URL . 'assets/js/main.min.js' , array(),  PRAVEL_INVOICE_PLUGIN_VERSION, true);
		
		
		
	}
	add_action('wp_enqueue_scripts', 'pravel_invoice_load_scripts_front');
	

		
}	
add_action( 'plugins_loaded', 'pravel_invoice_init' );


function parvel_invoice_tl_save_error() {
    update_option( 'plugin_error',  ob_get_contents() );
}
add_action( 'activated_plugin', 'parvel_invoice_tl_save_error' );




