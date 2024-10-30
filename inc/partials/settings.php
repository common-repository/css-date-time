<?php
	
	$tab = 'settings';
	if (isset($_GET['tab'])) {
		$tab = $_GET['tab'];
	}
	
	printf('<h2 class="nav-tab-wrapper">
            	<a href="%s" class="nav-tab %s">%s</a>
            	<a href="%s&tab=instructions" class="nav-tab %s">%s</a>
        	</h2>',
        	admin_url('admin.php?page=' . $this->menu_slug),
        	($tab == 'settings') ? 'nav-tab-active' : '',
        	__('Settings', 'awflclvr'),
        	admin_url('admin.php?page=' . $this->menu_slug),
        	($tab == 'instructions') ? 'nav-tab-active' : '',
        	__('Instructions', 'awflclvr')
        );
	
	if ($tab == 'settings') {
		print('<form method="post" action="options.php">');
			settings_errors();
			settings_fields($this->settings_name);
			do_settings_sections($this->settings_name);      
			submit_button();
		print('</form>');
	} else if ($tab == 'instructions') {
		include_once($this->plugin_inst);
	}