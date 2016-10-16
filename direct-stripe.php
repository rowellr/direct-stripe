<?php
/*
Plugin Name: Direct Stripe
Plugin URI: https://github.com/New0/Direct-Stripe/archive/master.zip
Description: Use Stripe payment buttons anywhere in a WordPress website, let your users easily proceed to checkout
Author: Nicolas Figueira @nahuelmahe
Version: 1.0
Author URI: https://newo.me
*/
defined( 'ABSPATH' ) or die( 'Please!' );

if(!defined('DSCORE_PATH')) {
define('DSCORE_PATH', plugin_dir_path(__FILE__));
}
if(!defined('DSCORE_URL')) {
define('DSCORE_URL', plugin_dir_url(__FILE__));
}
if(!defined('DSCORE_BASENAME')) {
define('DSCORE_BASENAME', plugin_basename( __FILE__ ));
}

require_once ( DSCORE_PATH . 'includes/functions.php' );

/* functions object */
$directstripe = new \DirectStripeFunctions;

//Add user type when activating plugin
register_activation_hook( __FILE__,  array( $directstripe, 'direct_stripe_user_roles_on_activation') );

//Translation ready
load_plugin_textdomain('direct-stripe', false, DSCORE_PATH . '/languages' );

//Load admin scripts
add_action( 'admin_enqueue_scripts', array( $directstripe, 'direct_stripe_load_admin_scripts') );

//Add shortcode
add_shortcode( 'direct-stripe', array( $directstripe, 'direct_stripe_buttons_func') );

// Custom queries variables
add_filter('query_vars', array( $directstripe, 'direct_stripe_query_vars') );

//Redirections Payment or Subscription
add_action('parse_request', array( $directstripe, 'direct_stripe_parse_request') );

//Users
add_action( 'show_user_profile', array( $directstripe, 'direct_stripe_show_extra_profile_fields') );
add_action( 'edit_user_profile', array( $directstripe, 'direct_stripe_show_extra_profile_fields') );
add_action( 'personal_options_update', array( $directstripe, 'direct_stripe_save_extra_profile_fields') );
add_action( 'edit_user_profile_update', array( $directstripe, 'direct_stripe_save_extra_profile_fields') );

//Custom Styles
$d_stripe_styles = get_option( 'direct_stripe_styles_settings' );
if(  $d_stripe_styles['direct_stripe_use_custom_styles'] === '1' ) {
	add_action( 'wp_enqueue_scripts', array( $directstripe, 'direct_stripe_styles_method') );
}

// Admin actions
if (is_admin() ) { 
  // Add admin settings area
add_action( 'admin_menu', array( $directstripe, 'direct_stripe_add_admin_menu') );
add_action( 'admin_init', array( $directstripe, 'direct_stripe_settings_init') );
}