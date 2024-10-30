<?php

if (!defined('ABSPATH')) exit;

/**
 * Awful Clever CSS Date Time
 *
 * @author Awful Clever
 * @version 1.0.1
 */
class AWFLCLVR_CSS_Date_Time {
	
	public $css_classes;
	public $options;
	public $enable_javascript;
	public $enable_browser_time;
	public $css_prefix;
	public $onetime_events;
	public $recurring_events;
	
	public $plugin_name;
	public $menu_slug;
	public $parent_menu_slug;
	public $plugin_basename;
	public $plugin_slug;
	public $js_safe_slug;
	public $plugin_docs;
	public $admin_tools;
	public $settings_name;
	public $settings_section;
	
	/**
	 * Class constructor
	 *
	 * @param string  $plugin_name     		Plugin name
	 * @param string  $version 	    		Plugin version
	 * @param string  $menu_slug     		Plugin menu slug
	 * @param string  $parent_menu_slug     Plugin parent menu slug
	 * @param string  $plugin_basename 		Plugin basename
	 * @param string  $plugin_slug 			Plugin slug
	 * @param string  $plugin_docs 			Plugin documentation URL
	 * @param string  $admin_tools 			Admin tools
	 */
	public function __construct ($plugin_name, $version, $menu_slug, $parent_menu_slug, $plugin_basename, $plugin_slug, $plugin_docs, $admin_tools) {
	
		$this->plugin_name		= $plugin_name;
		$this->version			= $version;
		$this->menu_slug 		= $menu_slug;
		$this->parent_menu_slug = $parent_menu_slug;
		$this->plugin_basename 	= $plugin_basename;
		$this->plugin_slug 		= $plugin_slug;
		$this->js_safe_slug 	= str_replace('-', '_', $plugin_slug);
		$this->plugin_docs 		= $plugin_docs;
		$this->admin_tools		= $admin_tools;
		$this->settings_name	= 'awflclvr-' . $plugin_slug . '-settings';
		$this->settings_section	= 'awflclvr-' . $plugin_slug . '-settings-section';
		
		$this->init();
	}
	
	/**
	 * Initialize plugin variables, hooks, filters and menu
	 *
	 * @since    1.0.0
	 */
	public function init () {
		
		$this->enable_javascript	= false;
		$this->enable_browser_time	= false;
		$this->css_prefix			= 'cssdt';
		$this->onetime_events 		= array();
		$this->recurring_events 	= array();
			
		if ($this->options = get_option($this->settings_name)) {
			$this->enable_javascript	= ($this->get_option('enable_javascript') == 1) ? true : false;
			$this->enable_browser_time	= ($this->get_option('enable_browser_time') == 1) ? true : false;
			$this->css_prefix			= ($this->get_option('css_prefix') != '') ? trim(preg_replace('![^a-z0-9]+!i', '-', $this->get_option('css_prefix')), '-') : 'cssdt';
			$this->onetime_events 		= array_map('trim', explode("\n", $this->get_option('onetime_events', '')));
			$this->recurring_events 	= array_map('trim', explode("\n", $this->get_option('recurring_events', '')));
		}
		
		$this->css_factory();
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		
		add_action('admin_init', array($this, 'settings_register'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
		
		add_action('wp_ajax_css_date_time_retrieve_data', 			array($this, 'ajax_retrieve_data'));
		add_action('wp_ajax_nopriv_css_date_time_retrieve_data',	array($this, 'ajax_retrieve_data'));
			
		add_filter('body_class', array($this, 'body_class_insertion'));
	
		new AWFLCLVR_Plugin_Framework(
				$this->plugin_name, 
				$this->menu_slug,
				$this->parent_menu_slug, 
				$this->plugin_basename, 
				$this->settings_name,
				plugin_dir_path(__FILE__) . 'partials/instructions.php'
			);
	}
	
	/**
	 * Evaluate date/time and build CSS classes
	 *
	 * @since    1.0.0
	 */
	public function css_factory ($timestamp = null) {
		
		$this->css_classes = array();
		
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		if (is_admin() && $action != 'css_date_time_retrieve_data') return;
		
		$this->date = $date = $this->date_object($timestamp);
		
		$this->css_classes[] = $this->css_format( $date->Year );						// YEAR
		if ($date->LeapYear) $this->css_classes[] = $this->css_format( 'Leap Year' );	// LEAP YEAR
		$this->css_classes[] = $this->css_format( $date->MonthName );					// MONTH
		$this->css_classes[] = $this->css_format( $date->MonthName . '-' . $date->Day);	// MONTH - DATE
		$this->css_classes[] = $this->css_format( $date->DayName );						// DAY
		$this->css_classes[] = $this->css_format( 'Hour ' . $date->Hour24 . '00');		// HOUR
		$this->css_classes[] = $this->css_format( $date->AMPM );						// AM || PM
		
		if ($date->HMS >= '00:00:00' && $date->HMS <= '05:59:59') $this->css_classes[] = $this->css_format( 'Night' );		// NIGHT
		if ($date->HMS >= '06:00:00' && $date->HMS <= '11:59:59') $this->css_classes[] = $this->css_format( 'Morning' );	// MORNING
		if ($date->HMS >= '12:00:00' && $date->HMS <= '17:59:59') $this->css_classes[] = $this->css_format( 'Afternoon' );	// AFTERNOON
		if ($date->HMS >= '18:00:00' && $date->HMS <= '23:59:59') $this->css_classes[] = $this->css_format( 'Evening' );	// EVENING
		
		$fixed_events = $this->fixed_events();
		foreach ($fixed_events as $d => $c) {
			if ($d == $date->MD) {
				$this->css_classes[] = $this->css_format($c);	
			}
		}
		
		$variable_events = $this->variable_events();
		foreach ($variable_events as $e) {
			$this->css_classes[] = $this->css_format($e);
		}
	}
	
	/**
	 * Format CSS class 
	 *
	 * @since  	1.0.0
	 * @param	String	$class	CSS class name
	 * @return  String  Formatted CSS class name
	 */
	public function css_format ($class) {
		
		return sprintf('%s-%s',
						str_replace(' ', '-', strtolower($this->css_prefix)),
						str_replace(' ', '-', strtolower($class))
					);
	}
	
	/**
	 * Create date object
	 *
	 * @since    	1.0.0
	 * @return      Object    Custom date object.
	 */
	public function date_object ($timestamp = null, $timezone = null) {
		$d = new DateTime();
		$date = new stdclass();
		
		if ($timestamp) { // SET TIME FROM SPECIFIC TIMESTAMP
			$d->setTimestamp($timestamp);
		} else { // SET TIME FROM WP TIMESTAMP
			$d->setTimestamp(strtotime(current_time('mysql')));
		}
		
		if ($timezone) { // SET TIME FROM SPECIFIC TIMEZONE --> USER LOCATION TIME
			$d = new DateTime();
			$d->setTimeZone(new DateTimeZone($timezone));
		}
		
		$date->Timestamp	= $d->getTimestamp();
		$date->DateTime		= $d->format('Y-m-d H:i:s');
		$date->Date			= $d->format('m-d-Y');
		$date->Year 		= $d->format('Y');
		$date->LeapYear 	= ($d->format('L')) ? true : false;
		$date->Month 		= $d->format('n');
		$date->MonthName 	= $d->format('F');
		$date->Day			= $d->format('j');
		$date->DayName		= $d->format('l');
		$date->DayNumber	= $d->format('N');
		$date->DayYear		= $d->format('z');
		$date->Week			= $d->format('W');
		$date->Hour 		= $d->format('g');
		$date->Hour24 		= $d->format('H');
		$date->Minutes 		= $d->format('i');
		$date->Seconds 		= $d->format('s');
		$date->HMS 			= $d->format('H:i:s');
		$date->YMD 			= $d->format('Y-m-d');
		$date->MD 			= $d->format('m-d');
		$date->AMPM			= $d->format('a');
		$date->AM			= ($d->format('a') == 'am') ? true : false;
		$date->PM 			= ($d->format('a') == 'pm') ? true : false;
		return $date;
	}
	
	/**
	 * Return query data for admin-ajax requests
	 *
	 * @since    1.0.0
	 */
	public function ajax_retrieve_data () {
	
		if (defined('DOING_AJAX') && DOING_AJAX) {
			
			$nonce = isset($_POST['nonce']) ? $_POST['nonce'] : false;
				
			if (wp_verify_nonce($nonce, $this->plugin_slug)) {
				
				if ($this->enable_browser_time) {
					$timestamp = strtotime($_POST['datetime']);
					$this->css_factory($timestamp);
				}
				
				$response = new stdclass;
				$response->success = true;
				$response->css = implode(' ', $this->css_classes);
			
				echo json_encode($response);
			}
		}
		wp_die();
	}	
	
	/**
	 * Adds CSS classes to <body> class
	 *
	 * Classes can be disabled in plugin settings
	 * Disabled when cache option is set in plugin settings
	 *
	 * @since    1.0.0
	 */
	public function body_class_insertion ($classes) {
		
		if ($this->enable_javascript) return $classes;
		
		foreach ($this->css_classes as $class) {
			$classes[] = $class;
		}		
		return $classes;
	}
	
	/**
	 * Enqueue CSS
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles () {
		//wp_enqueue_style($this->js_safe_slug, plugin_dir_url(__FILE__) . '/css/reactive-message.css', array(), $this->version, 'all');
	}
	
	/**
	 * Enqueue Javascript
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts () {
		wp_enqueue_script($this->js_safe_slug, plugin_dir_url(__FILE__) . 'js/css-date-time.min.js', array('jquery'), $this->version, false );
		
		$js_params = array(
						'ajaxurl' 		=> admin_url('admin-ajax.php'),
						'css_classes' 	=> implode(' ', $this->css_classes),
						'js_parse'		=> $this->enable_javascript,
						'nonce'			=> wp_create_nonce($this->plugin_slug)
						);
			
		wp_localize_script($this->js_safe_slug, $this->js_safe_slug, $js_params);
	}
	
	/**
	 * Enqueue Admin CSS
	 *
	 * @since    1.0.0
	 */
	public function enqueue_admin_styles ($hook) {		
		wp_enqueue_style($this->js_safe_slug, plugin_dir_url( __FILE__ ) . '/css/css-date-time-admin.css', array(), $this->version, 'all');
	}
	
	/**
	 * Register Plugin Settings.
	 *
	 * @since    1.0.0
	 */
	public function settings_register () {
		
		if (get_option($this->settings_name) == false) add_option($this->settings_name);
    
		add_settings_section(
			$this->settings_section,         
        	sprintf('<h1 style="font-weight: normal;">%s Settings</h1>', $this->plugin_name),              
        	array($this, 'section_callback'), 	
        	$this->settings_name        	
    	);
		
		$settings = array (
						'enable_javascript' => array (
												'name' 	=> __('Content Display', 'awflclvr'),
												'meta' 	=> __(sprintf('Add CSS classes using post-load Javascript callbacks.<br> Recommended when Caching is enabled. <br><a href="%s" target="_blank">View Docs</a>', $this->plugin_docs), 'awflclvr'),
												'type' 	=> 'tracking',
												'label' => __('Enable Javascript Callback', 'awflclvr'),
												'opts'  => null,
												'disabled'	=> null,
												'upgrade'  	=> null
											),
						'enable_browser_time' => array (
												'name' 	=> __('Browser Time', 'awflclvr'),
												'meta' 	=> __(sprintf('Date/Time based on user\'s browser.<br> <strong>Available only when Enable Javascript Callback is checked.</strong><br><a href="%s" target="_blank">View Docs</a>', $this->plugin_docs), 'awflclvr'),
												'type' 	=> 'checkbox',
												'label' => __('Enable Browser-based Time', 'awflclvr'),
												'opts'  => null,
												'disabled'	=> null,
												'upgrade'  	=> null
											),
						'css_prefix' => array (
												'name' 	=> __('CSS Prefix', 'awflclvr'),
												'meta' 	=> __(sprintf('Replace default <strong>cssdt</strong> prefix with custom prefix. <br><a href="%s" target="_blank">View Docs</a>', $this->plugin_docs), 'awflclvr'),
												'type' 	=> 'text',
												'label' => '',
												'opts'  => null,
												'disabled'	=> null,
												'upgrade'  	=> null
											),
						'onetime_events' => array (
												'name' 	=> __('One-Time Full-day Events', 'logichop'),
												'meta' 	=> __(sprintf('Add CSS classes for one-time events. <br>Format: YYYY-MM-DD CLASSNAME<br>Example: <em>2018-03-04 academy-awards</em> <br>Enter one per line. <br><a href="%s" target="_blank">View Docs</a>', $this->plugin_docs), 'awflclvr'),
												'type' 	=> 'textarea',
												'label' => '',
												'opts'  => null,
												'disabled'	=> true,
												'upgrade'  	=> __('<strong>CSS Date Time Pro Feature</strong> – <a href="https://awfulclever.com/downloads/css-date-time-pro/" target="_blank">Upgrade Now</a>', 'awflclvr')
											),
						'recurring_events' => array (
												'name' 	=> __('Recurring Full-day Events', 'logichop'),
												'meta' 	=> __(sprintf('Add CSS classes for recurring events. <br>Format: MM-DD CLASSNAME<br>Example: <em>04-15 tax-day</em> <br>Enter one per line. <br><a href="%s" target="_blank">View Docs</a>', $this->plugin_docs), 'awflclvr'),
												'type' 	=> 'textarea',
												'label' => '',
												'opts'  => null,
												'disabled'	=> true,
												'upgrade'  	=> __('<strong>CSS Date Time Pro Feature</strong> – <a href="https://awfulclever.com/downloads/css-date-time-pro/" target="_blank">Upgrade Now</a>', 'awflclvr')
											)
					);
		
		foreach ($settings as $var => $params) {
			add_settings_field( 
				$var,                     
				$params['name'],                           	
				array($this, 'render_setting_input'),   
				$this->settings_name,                    
				$this->settings_section,        
				array($var, $params['type'], $params['meta'], $params['label'], $params['opts'], $params['disabled'], $params['upgrade'])
			);
		}
		
		register_setting(
			$this->settings_name,
			$this->settings_name,
			array($this, 'setting_validation')
		);
	}
	
	/**
	 * Validate Plugin Settings.
	 *
	 * @since    1.0.0
	 * @param	array	$input		Plugin settings
	 * @return	array	Plugin settings
	 */
	public function setting_validation ($input) {
		
		$output = array();
    	$error = false;
    	$error_msg = '';

    	foreach ($input as $key => $value) {
    		if (isset($input[$key])) {
         		$output[$key] = strip_tags(stripslashes($input[$key]));
         		/*if ($validation) {
					$error = true;
					$error_msg .= 'Error';
					$output[$key] = '';
				}*/
        	}
    	}
    	
    	if ($error) {
    		add_settings_error(
        		$this->settings_section,
				'settings_updated',
        		sprintf('<h2>Settings Error</h2><ul>%s</ul>', $error_msg),
        		'error'
    		);
    	}
    	
		return $output;
	}

	/**
	 * Plugin section callback.
	 *
	 * @since	1.0.0
	 * @return  null
	 */
	public function section_callback () {
		return;
	}
	
	/**
	 * Render Settings Form Inputs.
	 *
	 * @param	array	$args		Setting arguments
	 * @since    1.0.0
	 */
	public function render_setting_input ($args) {
		
		$var 	= isset($args[0]) ? $args[0] : '';
		$type 	= isset($args[1]) ? $args[1] : 'text';
		$meta 	= isset($args[2]) ? $args[2] : '';
		$label 	= isset($args[3]) ? $args[3] : '';
		$opts 	= isset($args[4]) ? $args[4] : array();
		$disabled	= isset($args[5]) ? $args[5] : false;
		$upgrade	= isset($args[6]) ? $args[6] : '';
		
		$options = get_option($this->settings_name);
		
		$output = ''; 
		
		if ($type == 'text') {
			$output = sprintf('<input type="text" id="%s[%s]" name="%s[%s]" value="%s" style="width: 400px; max-width: 95%%; height: 30px;" placeholder="%s">
					<p><small>%s</small></p>',
					$this->settings_name,
					$var,
					$this->settings_name,
					$var,
					isset($options[$var]) ? sanitize_text_field($options[$var]) : '',
					$label,
					$meta
				);
		}
		
		if ($type == 'textarea') {
			$output = sprintf('<textarea id="%s[%s]" name="%s[%s]" style="width: 400px; max-width: 95%%; height: 60px;">%s</textarea>
					<p><small>%s</small></p>',
					$this->settings_name,
					$var,
					$this->settings_name,
					$var,
					isset($options[$var]) ? sanitize_textarea_field($options[$var]) : '',
					$meta
				);
		}
		
		if ($type == 'select') {
			$value = isset($options[$var]) ? sanitize_text_field($options[$var]) : '';
			
			$option_items = '';
			foreach ($opts as $o) {
				$option_items .= sprintf('<option value="%s" %s>%s</option>',
											$o['value'],
											($value == $o['value']) ? 'selected' : '',
											$o['name']
										);
			}
			
			$output = sprintf('<select id="%s[%s]" name="%s[%s]" style="width: 400px; max-width: 95%%; height: 30px;">
						%s
					</select>
					<p><small>%s</small></p>',
					$this->settings_name,
					$var,
					$this->settings_name,
					$var,
					$option_items,
					$meta
				);
		}
		
		if ($type == 'checkbox') {
			$value = isset($options[$var]) ? $options[$var] : '';
			$output = sprintf('<input type="checkbox" id="%s[%s]" name="%s[%s]" value="1" %s />
					<label for="%s"><strong>%s</strong></label>
					<p><small>%s</small></p>',
					$this->settings_name,
					$var,
					$this->settings_name,
					$var,
					checked(1, $value, false),
					$var,
					$label,
					$meta
				);
		}
		
		if ($type == 'tracking') {
			$value = isset($options['enable_javascript']) ? $options['enable_javascript'] : '';
			$output = sprintf('<input type="checkbox" id="%s[%s]" name="%s[%s]" value="1" %s />
					<label for="%s"><strong>%s</strong></label>
					<p><small>%s</small></p>
					<p><strong style="color: rgb(255,0,0);">%s</strong></p>',
					$this->settings_name,
					$var,
					$this->settings_name,
					$var,
					checked(1, $value, false),
					$var,
					$label,
					$meta,
					(defined('WP_CACHE') && WP_CACHE && !$value) ? 'Caching Detected: "Enable Javascript" is recommended.' : ''
				);
		}
		
		if ($disabled) {
			$output = str_replace(' name="', ' disabled name="', $output);
			$output .= sprintf('<p>%s</p>', $upgrade);
		}
		
		print($output);
	}
	
	/**
	 * Utility to get Wordpress option from $options 
	 *
	 * @since		1.0.0
	 * @param      	string   	$option   			Option Name
	 * @param      	var			$default_return		Default return value - Optional
	 */
	public function get_option ($option, $default_return = '') {
		if (isset($this->options[$option])) return $this->options[$option];
		return $default_return;
	}
	
	/**
	 * Debug 
	 *
	 * @param	object	$data		
	 * @since    1.0.0
	 */
	public function _d ($data) {
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}
	
	/**
	 * Check variable date event 
	 *
	 * @return  Boolean		Is the variable event date met	
	 * @since    1.0.0
	 */
	public function event_check ($month, $start_day, $end_day, $weekday) {
		
		if ($month == $this->date->Month) {
			if ($this->date->Day >= $start_day && $this->date->Day <= $end_day) {
				if ($weekday == $this->date->DayName) {
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * Default fixed date events 
	 *
	 * @return  Array	Events and dates	
	 * @since    1.0.0
	 */
	public function fixed_events () {
		
		$events = array (
			'01-01' => 'New Years Day',
			'02-14' => 'Valentines Day',
			'03-17' => 'St Patricks Day',
			'04-01' => 'April Fools Day',
			'07-04' => 'Independence Day',
			'10-31' => 'Halloween',
			'11-11' => 'Veterans Day',
			'12-24' => 'Christmas Eve',
			'12-25' => 'Christmas Day',
			'12-31' => 'New Years Eve'
		);
		
		return $events;
	}
	
	/**
	 * Default variable date events 
	 *
	 * @return  Array	Event names	
	 * @since    1.0.0
	 */
	public function variable_events () {
		
		$events = array();
		
		$variable_events = array (
			'MLK Day' => array (
					'month' => 1,
					'start' => 15,
					'end'	=> 21,
					'day'	=> 'Monday'
				),
			'Washingtons Birthday' => array (
					'month' => 2,
					'start' => 15,
					'end'	=> 21,
					'day'	=> 'Monday'
				),
			'Mothers Day' => array (
					'month' => 5,
					'start' => 8,
					'end'	=> 15,
					'day'	=> 'Sunday'
				),
			'Memorial Day' => array (
					'month' => 5,
					'start' => 25,
					'end'	=> 31,
					'day'	=> 'Monday'
				),
			'Fathers Day' => array (
					'month' => 6,
					'start' => 15,
					'end'	=> 21,
					'day'	=> 'Sunday'
				),
			'Labor Day' => array (
					'month' => 9,
					'start' => 1,
					'end'	=> 7,
					'day'	=> 'Monday'
				),
			'Columbus Day' => array (
					'month' => 10,
					'start' => 8,
					'end'	=> 14,
					'day'	=> 'Monday'
				),
			'Thanksgiving Day' => array (
					'month' => 11,
					'start' => 22,
					'end'	=> 28,
					'day'	=> 'Thursday'
				)
		);
		
		foreach ($variable_events as $k => $v) {
			if ($this->event_check($v['month'], $v['start'], $v['end'], $v['day'])) {
				$events[] = $k;
			}
		}
		
		return $events;
	}
	
}

