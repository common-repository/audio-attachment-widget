<?php
/* Description: Include script is used to load the CSS needed to style the front-end of the widget.
* Version: 1.0
* Author: Geansai .Ltd
* Author URI: http://geansai.co.uk
* License: GPLv2 or later
*/
	
/**************** Function to load CSS for front end screen *************/
function audio_attachment_css() {
	wp_enqueue_style('audio-attachment-css', trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",str_replace('/includes', '',plugin_basename(__FILE__)))) . 'css/audio_attachment_widget.css');

}

/**************** Call function and add action *************/	
add_action( 'wp_print_styles', 'audio_attachment_css');
?>