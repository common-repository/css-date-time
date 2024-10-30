<?php

if (!defined('ABSPATH')) exit;

/**
 * Awful Clever Admin Tools Singleton
 *
 * @author Awful Clever
 * @version 1.0.0
 */
class AWFLCLVR_Admin_Tools {

	private static $instance = null;
	
	public $title			= 'Awful Clever';
	public $default_menu	= 'Dashboard';
	public $menu_slug		= 'awflclvr-menu';
	public $awflclvr_url 	= 'https://awfulclever.com/';
	
	public static function get_instance () {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    private function __construct () {
 		$this->init();	
    }
    
    private function init () {
    	add_action('admin_menu', array($this, 'menu_pages'));
    	
    	add_filter('custom_menu_order', array($this, 'set_menu_page_order'));
    }
    
    /**
	 * Add Awful Clever Admin Pages
	 *
	 * @since    1.0.0
	 */
    public function menu_pages () {
    	add_menu_page( 
			$this->title, 
			$this->title, 
			'edit_theme_options', 
			$this->menu_slug, 
			null, 
			plugins_url('images/icon-blue.png', __FILE__),
			80
		);
		
		add_submenu_page(
			$this->menu_slug,
			$this->default_menu,
			$this->default_menu,
			'manage_options',
			'awflclvr-dashboard',
			array($this, 'dashboard_page')
		);
	}	
	
	/**
	 * Re-order sub-menu pages
	 * Set Dashboard to be first and removes default menu page
	 *
	 * @since    1.0.0
	 */
	public function set_menu_page_order ($menu_order) {
		global $submenu;
		
		if (key_exists($this->menu_slug, $submenu)) {
			$new_order = array();
			for ($i = 0; $i < count($submenu[$this->menu_slug]); $i++) {
				if ($submenu[$this->menu_slug][$i][0] == $this->default_menu) {
					array_unshift($new_order, $submenu[$this->menu_slug][$i]);
				} else {
					if ($submenu[$this->menu_slug][$i][2] != $this->menu_slug) {
						$new_order[] = $submenu[$this->menu_slug][$i];
					}
				}
			}
			$submenu[$this->menu_slug] = $new_order;
		}
		return $menu_order;
	}	
	
	/**
	 * Display Dashboard Page.
	 * Include partial file dashboard.php
	 *
	 * @since    1.0.0
	 */
	public function dashboard_page () {
		include_once(plugin_dir_path(dirname(__FILE__)) . 'inc/partials/dashboard.php');
	}
	
	public function get_url ($page = '', $ref = '') {
		
		$url = $this->awflclvr_url;
		
		if ($page == 'blog') 		$url .= 'blog/';
		if ($page == 'contact') 	$url .= 'contact/';
		if ($page == 'twitter') 	$url .= 'https://twitter.com/awful_clever';
		
		if ($page == 'dashboard')	$url = admin_url('admin.php?page=awflclvr-dashboard');
		
		if ($ref != '') $url = sprintf('%s?ref=%s', $url, $ref);
		
		return $url;
	}
}