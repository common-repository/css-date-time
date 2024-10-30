<?php

if (!defined('ABSPATH')) exit;

/**
 * Awful Clever Plugin Framework
 *
 * @author Awful Clever
 * @version 1.0.2
 */
class AWFLCLVR_Plugin_Framework {
	
	public $plugin_name;
	public $menu_slug;
	public $parent_menu_slug;
	public $plugin_basename;
	public $settings_name;
	public $plugin_dir;
	public $plugin_inst;
	
	/**
	 * Class constructor
	 *
	 * @param string  $plugin_name     		Plugin name
	 * @param string  $menu_slug     		Plugin menu slug
	 * @param string  $parent_menu_slug     Plugin parent menu slug
	 * @param string  $plugin_basename 		Plugin basename
	 * @param string  $settings_name 		Plugin settings name
	 * @param string  $plugin_inst 			Plugin instructions page
	 */
	public function __construct ($plugin_name, $menu_slug, $parent_menu_slug, $plugin_basename, $settings_name, $plugin_inst) {
		
		$this->plugin_name		= $plugin_name;
		$this->menu_slug 		= $menu_slug;
		$this->parent_menu_slug = $parent_menu_slug;
		$this->plugin_basename 	= $plugin_basename;
		$this->settings_name	= $settings_name;
		$this->plugin_dir		= plugin_dir_path(dirname(__FILE__));
		$this->plugin_inst		= $plugin_inst;
		
		$this->init();
	}

	/**
	 * Set up Hooks & Filters
	 *
	 * @return void
	 */
	public function init () {
		add_action('admin_menu', array($this, 'menu_pages'));
		
		add_filter('plugin_action_links_' . $this->plugin_basename, array($this, 'settings_link'));
	}
	
	/**
	 * Adds Plugin Menu settings link
	 *
	 * @return	array	Plugin links
	 */
	public function settings_link ($links) {
	
		$new_links = array();
		$new_links['settings'] = sprintf('<a href="%s"> %s </a>', 
											admin_url('admin.php?page=' . $this->menu_slug), 
											__('Settings', 'awflclvr')
										);
										
		$new_links['deactivate'] = $links['deactivate'];
		
		return $new_links;
	}
	
	/**
	 * Adds plugin settings page
	 *
	 * @return	void
	 */
	public function menu_pages () {	
		add_submenu_page(
			$this->parent_menu_slug,
			$this->plugin_name,
			$this->plugin_name,
			'manage_options',
			$this->menu_slug,
			array ($this, 'settings_page')
		);
	}
	
	/**
	 * Renders settings page
	 *
	 * @return	settings page
	 */
	public function settings_page () {
		include_once(plugin_dir_path(dirname(__FILE__)) . 'inc/partials/settings.php');
	}
}


	



