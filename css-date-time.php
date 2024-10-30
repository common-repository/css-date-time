<?php
/*
	Plugin Name: CSS Date Time
	Plugin URI: https://awfulclever.com/
	Description: CSS Date Time
	Author: Awful Clever
	Author URI: https://awfulclever.com
	Version: 0.9.2
*/

if (!defined('ABSPATH')) exit;

if (!class_exists('AWFLCLVR_Admin_Tools')) require(dirname(__FILE__) . '/inc/AWFLCLVR_Admin_Tools.php');
if (!class_exists('AWFLCLVR_Plugin_Framework')) require(dirname(__FILE__) . '/inc/AWFLCLVR_Plugin_Framework.php');
if (!class_exists('AWFLCLVR_CSS_Date_Time')) require(dirname(__FILE__) . '/AWFLCLVR_CSS_Date_Time.php');

function awflclvr_css_date_time () {

	$plugin_name 		= 'CSS Date Time';
	$plugin_version 	= '0.9.2';
	$plugin_id			= 1883;
	$plugin_basename 	= plugin_basename(__FILE__);
	$plugin_slug 		= basename(__FILE__, '.php');
	$menu_slug			= 'awflclvr-css-date-time';
	$plugin_docs 		= 'https://awfulclever.com/docs/css-date-time-pro';
	
	$admin_tools = AWFLCLVR_Admin_Tools::get_instance();
	
	return new AWFLCLVR_CSS_Date_Time(
			$plugin_name,					// Plugin name
			$plugin_version, 				// Plugin version
			$menu_slug,						// Plugin menu slug
			$admin_tools->menu_slug,		// Parent menu slug
			$plugin_basename,				// Plugin basename
			$plugin_slug,					// Plugin slug
			$plugin_docs,					// Plugin documentation
			$admin_tools					// Admin tools 
		);
}

$awflclvr_css_date_time = awflclvr_css_date_time();
