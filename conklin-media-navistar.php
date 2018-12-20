<?php
/*
Plugin Name: ConklinMedia Navistar Integration
Description: This plugin is created by ConklinMedia to support integration of the Navistar workshop registration system on your wordpress site. 
Version: 1.1.0
Author: Blade | Ben
License: GPL 2.0 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: CMNI

ConklinMedia Navistar Integration Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
ConklinMedia Navistar Integration Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with ConklinMedia Navistar Integration Plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here you naughty boy?');
$options = get_option( 'CMNI_settings' );
class CMNIPlugin {

	function register_scripts() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}
	function register_admin_scripts() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}
	function activate() {
		flush_rewrite_rules();
	}
	
	function deactivate() {
		flush_rewrite_rules();
	}
	
	function uninstall() {
		// Delete the CPT
		// Delete all Plugin Data
	}
	function enqueue_admin_scripts() {
		//enqueue scripts
		wp_enqueue_style( 'cmniadminstyle', plugins_url('/assets/css/admin-style.css', __FILE__ ) );
		wp_enqueue_script( 'cmniadminscript', plugins_url('/assets/js/admin-script.js', __FILE__ ) );
	}

	function enqueue_scripts() {
		global $options;
		//enqueue scripts
		wp_enqueue_style('CMNIstyle', plugins_url('/assets/css/style.css', __FILE__ ) );
		wp_enqueue_style('add2calstyle', plugins_url('/assets/css/atc-style-glow-orange.css', __FILE__ ) );
		wp_enqueue_script('CMNIscript', plugins_url('/assets/js/front.js', __FILE__ ), array( "jquery" ));
		wp_localize_script( "cmni", "cmni", array("ajaxurl" => admin_url( "admin-ajax.php")));
		wp_enqueue_script('add2caljs', plugins_url('/assets/js/add2cal.js', __FILE__ ));
		wp_enqueue_script('cmnijs', plugins_url('/assets/js/front.js', __FILE__ ));
		//$options = get_option( 'CMNI_settings' );

	    $scriptData = array(
	        'find_reservation' => $options['CMNI_find_reservation'],
	        'select_workshop' => $options['CMNI_select_workshop'],
	        'session_selection' => $options['CMNI_session_selection'],
	        'submit_registration' => $options['CMNI_submit_registration']
	    );

	    wp_localize_script('cmnijs', 'cmni_options', $scriptData);
	}

}

if ( class_exists( 'CMNIPlugin' ) ) {
	$cmniPlugin = new CMNIPlugin();
	$cmniPlugin->register_scripts();
	$cmniPlugin->register_admin_scripts();
}

// On Activation
register_activation_hook( __FILE__, array( $cmniPlugin, 'activate' ) );

// On Deactivate
register_deactivation_hook( __FILE__, array( $cmniPlugin, 'deactivate' ) );

// On Uninstall


// Add Shortcode: [cmni_find_reservation]

add_shortcode( "cmni_find_reservation", "cmni_shortcode_find_reservation");

function cmni_shortcode_find_reservation(){
	ob_start();
	include dirname( __FILE__ ) . "/views/shortcodes/find_reservation.php";
	return ob_get_clean();
}

// Add Shortcode: [cmni_select_workshop]
add_shortcode( "cmni_select_workshop", "cmni_shortcode_select_workshop");

function cmni_shortcode_select_workshop(){
	ob_start();
	include dirname( __FILE__ ) . "/views/shortcodes/select_workshop.php";
	return ob_get_clean();
}

// Add Shortcode: [cmni_session_selection]
add_shortcode( "cmni_session_selection", "cmni_shortcode_session_selection");

function cmni_shortcode_session_selection(){
	ob_start();
	include dirname( __FILE__ ) . "/views/shortcodes/session_selection.php";
	return ob_get_clean();
}

// Add Shortcode: [cmni_submit_registration]
add_shortcode( "cmni_submit_registration", "cmni_shortcode_submit_registration");

function cmni_shortcode_submit_registration(){
	ob_start();
	include dirname( __FILE__ ) . "/views/shortcodes/submit_registration.php";
	return ob_get_clean();
}
add_action( 'admin_menu', 'CMNI_add_admin_menu' );
add_action( 'admin_init', 'CMNI_settings_init' );


function CMNI_add_admin_menu(  ) {
	$page_title = 'ConklinMedia NI Settings';
    $menu_title = 'ConklinMedia NI Settings';
    $capability = 'manage_options';
    $menu_slug = 'cmni_settings';
    $function = 'CMNI_options_page';
    $icon_url = 'dashicons-welcome-learn-more';
    $position = 6;

    add_menu_page( 
    	$page_title, 
    	$menu_title, 
    	$capability, 
    	$menu_slug, 
    	$function, 
    	$icon_url, 
    	$position 
    );
}

function CMNI_settings_init(  ) { 

	register_setting( 'CMNIPage', 'CMNI_settings' );

		add_settings_section(
		'CMNI_CMNIAbout_section', 
		__( 'Shortcodes', 'cmni' ), 
		'CMNI_about_section_callback', 
		'CMNIPage'
	);
		
	add_settings_section(
		'CMNI_CMNIPage_section', 
		__( 'Settings', 'cmni' ), 
		'CMNI_settings_section_callback', 
		'CMNIPage'
	);

	add_settings_field( 
		'CMNI_company_name', 
		__( 'Company Name', 'cmni' ), 
		'CMNI_company_name_render', 
		'CMNIPage', 
		'CMNI_CMNIPage_section' 
	);

	add_settings_field( 
		'CMNI_auth_key', 
		__( 'Authorization Key', 'cmni' ), 
		'CMNI_auth_key_render', 
		'CMNIPage', 
		'CMNI_CMNIPage_section' 
	);

	add_settings_field( 
		'CMNI_select_workshop', 
		__( 'Workshop Registration Page link', 'cmni' ), 
		'CMNI_select_workshop_render', 
		'CMNIPage', 
		'CMNI_CMNIPage_section' 
	);
	add_settings_field( 
		'CMNI_session_selection', 
		__( 'Available Workshop Schedule Page link', 'cmni' ), 
		'CMNI_session_selection_render', 
		'CMNIPage', 
		'CMNI_CMNIPage_section' 
	);
	add_settings_field( 
		'CMNI_submit_registration', 
		__( 'Submit and Register Page link', 'cmni' ), 
		'CMNI_submit_registration_render', 
		'CMNIPage', 
		'CMNI_CMNIPage_section' 
	);
}


function CMNI_company_name_render(  ) { 

	global $options; ?>
	<input 
		type='text' 
		name='CMNI_settings[CMNI_company_name]' 
		value='<?php echo $options['CMNI_company_name']; ?>'
	>
	<span>Company code that we are going to send as query </span>
	<?php

}


function CMNI_auth_key_render(  ) { 

	global $options;	?>
	<input 
		type='text' 
		name='CMNI_settings[CMNI_auth_key]' 
		value='<?php echo $options['CMNI_auth_key']; ?>'
	>
	<span>Authorization key to use</span>
	<?php

}

function CMNI_select_workshop_render(  ) {
	global $options;
	
	select_page_link('CMNI_settings[CMNI_select_workshop]', $options['CMNI_select_workshop']); ?>

	<input 
		class="link_desc" 
		disabled 
		type='text' 
		name='CMNI_settings[CMNI_select_workshop]' 
		value='<?php echo $options['CMNI_select_workshop']; ?>'
	>
	
	<span class="link_info">Page link where the shortcode <code>[cmni_select_workshop]</code> is placed eg. <code>/register</code></span>
	<?php

}

function CMNI_session_selection_render(  ) { 

	global $options;	
	select_page_link('CMNI_settings[CMNI_session_selection]', $options['CMNI_session_selection']); ?>
		
	<input 
		class="link_desc" 
		disabled 
		type='text' 
		name='CMNI_settings[CMNI_session_selection]' 
		value='<?php echo $options['CMNI_session_selection']; ?>'
	>
	<span class="link_info">Page link where the shortcode <code>[cmni_session_selection]</code> is placed eg. <code>/session-selection</code> </span>
	<?php

}
function CMNI_submit_registration_render(  ) { 

	global $options;
	select_page_link('CMNI_settings[CMNI_submit_registration]', $options['CMNI_submit_registration']); ?>
	
	<input 
		class="link_desc" 
		disabled 
		type='text' 
		name='CMNI_settings[CMNI_submit_registration]' 
		value='<?php echo $options['CMNI_submit_registration']; ?>'
	>
	
	<span class="link_info">Page link where the shortcode <code>[cmni_submit_registration]</code> is placed eg. <code>/submit-registration</code></span>
	<?php
}

function select_page_link($select_name, $selected_option){
	?>
	<select name='<?php echo $select_name ?>'>
		<?php 
			$pages = get_pages(); 
				foreach ( $pages as $page ) {
			  		$page_url = get_page_link( $page->ID );
			  		$option = '<option value="' . $page_url . '"';
						if ($page_url == $selected_option ){
							$option .= ' selected ">';
						}
						else {
							$option .= '">';
						}
					
					$option .= $page->post_title;
					$option .= '</option>';
					echo $option;
			  	}
		?>
	</select> <?php
}

function CMNI_settings_section_callback(  ) { 
	echo __('<div class="settings_page_desc">Configure how this plugin will be able to connect to the Navistar system API. </div>', 'cmni');
}

function CMNI_about_section_callback(  ) { 
	include 'about-section.php';
}

function CMNI_options_page(  ) { 
	include 'admin-form.php';
}
?>