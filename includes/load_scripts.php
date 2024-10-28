<?php
/***************************************************************************************/
//	This include file loades all the javascript for the plugin
//	Audio Attachment Widget
//	Author: Geansai .Ltd
//	Author URI: http://geansai.co.uk
//	Copyright (C) 2011-2011 Geansai .Ltd / geansai.co.uk (info@geansai.co.uk)
/***************************************************************************************/

// ******************************************* LOAD JAVASCRIPT START

function load_jquery() {
	// load default wordpress JQuery
	wp_enqueue_script('jquery');
	
	// Now load our script files
	wp_enqueue_script('audiojs', trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",str_replace('/includes', '', plugin_basename(__FILE__)))) . 'audiojs/audio.min.js');
	
	wp_enqueue_script('play_list', trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",str_replace('/includes', '', plugin_basename(__FILE__)))) . 'audiojs/play_list.js');
	
}
add_action('wp_enqueue_scripts', 'load_jquery');

// ******************************************* LOAD JAVASCRIPT END
?>