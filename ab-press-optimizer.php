<?php
/**
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 *
 * @wordpress-plugin
 * Plugin Name: AB Press Optimizer Lite
 * Plugin URI:  http://ABPressOptimizer.com
 * Description: AB Press Optimizer A/B testing integrated directly into your WordPress site. Quickly and easily create dozens of different versions of your images, buttons and headlines. Showing you which versions will increase your bottom line. 
 * Version:     1.0.0
 * Author:      Ivan Lopez
 * Author URI:  http://ABPressOptimizer.com
 * License: GPLv2
 * Text Domain: ab-press-optimizer-locale
 *
 * ------------------------------------------------------------------------
 * Copyright 2013 AB Press Optimizer (http://ABPressOptimizer.com)
 *
 **/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ABPO_PATH', plugin_dir_path( __FILE__ ) );

require_once( plugin_dir_path( __FILE__ ) . 'class-ab-press-optimizer.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/functions.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'ABPressOptimizer', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ABPressOptimizer', 'deactivate' ) );

ABPressOptimizer::get_instance();


if(!isset($_SESSION) && is_admin()) {
   session_start();
}
