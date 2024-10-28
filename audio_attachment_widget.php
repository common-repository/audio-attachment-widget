<?php
/*
Plugin Name: Audio Attachment Widget
Plugin URI: http://geansai.co.uk
Description: This is a plugin to add a new wiget to wordpress, which finds all audio media items attached to the selected page or post and displays a download link for the file. If the MPEG option is set, then all MPEG files will be able to be previewed using the mp3 player option.

Version: Beta 0.5
Author: Geansai .Ltd
Author URI: http://geansai.co.uk
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// load the style sheets for the document attachment widget
	include_once 'includes/load_styles.php';
// load the javascripts for the document attachment widget
	include_once 'includes/load_scripts.php';

// load the javascripts for the document attachment widget
	include_once 'includes/url_encryption_class.php';
	
// Include global variables for use within the plugin. 
global $args, $instance;

// extend WP_Widget class
class Audio_Attachment_Widget extends WP_Widget {
	/* constructor */
	function __construct() {
		parent::WP_Widget( /* Base ID */'audio_attachments',/* Name */'Audio Attachments', array( 'description' =>'A widget to display the audio files, which have been attached to a page or post'));

}

/** @see WP_Widget::widget */
	 public function widget( $args, $instance ) {
		extract($args);		
		// tmp checking please remove print_r($args);		
		global $wpdb, $post;
		// add an array to add the mime types to after we check which user options have been selected.	
		$mime_type_array = array();
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$limit = $instance['limit']; // NEW 
		
		// Allowed audio mime types list (mpeg, ogg, wav)
		$opt_mpeg = $instance['mpeg_audio'];
		$opt_ogg = $instance['ogg_audio'];
		$opt_wav = $instance['wav_audio'];
		
	
		$opt_order_ascending = $instance['order_ascending'];
		$opt_description = $instance['doc_description'];
		$opt_hide_sidebar = $instance['hide_sidebar'];
		
		// Check to see what mime types the widget should display.
		if($opt_mpeg == '1'):
			array_push($mime_type_array, "'audio/mpeg'");
		else:
			array_push($mime_type_array, "' '");
		endif;
		if($opt_ogg == '1'):
			array_push($mime_type_array, "'audio/ogg'");
		else:
			array_push($mime_type_array, "' '");
		endif;		
		if($opt_wav == '1'):
			array_push($mime_type_array, "'audio/wav'");
		else:
			array_push($mime_type_array, "' '");
		endif;
	
		// build list of mime types as a string to use within the db query
		$mime_type_str = implode(", ", $mime_type_array);		
		
		if(isset($showresults)):
			$quick_check = count($showresults);	
				if(!$quick_check ==0){
					echo $before_widget;
				}
		endif;		
		
		if($limit == 0):
			$limit=1;
		endif;
			
		if ( $title ):
		   $post_id = $post->ID;
			// check to see if post id is set.	
			
			if($opt_order_ascending==0):
				$order = 'DESC';
			else:
				$order = 'ASC';
			endif;		
			
			if(isset($post_id)):
				// Query the post table to find all attachment items related to the post parent ID 			
				$showresults = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}posts` WHERE post_type = 'attachment' AND post_mime_type IN ($mime_type_str) AND post_parent = $post_id ORDER BY post_title $order LIMIT $limit");				
			endif;
						
			// check to see if there are any attachment documents found in the database.
			if(isset($showresults)):							
				$quick_check = count($showresults);	
				if(!$quick_check ==0){
					if(isset($args['id'])) :
						if (isset($opt_hide_sidebar)):						
							if($opt_hide_sidebar == '0'):
								echo '<aside class="widget_audio_attachments">';
								echo $before_title . $title . $after_title;
								if($opt_mpeg == '1'):
									echo '<audio  preload></audio>';
								endif;								
								echo '<div id="audio_wrapper">';
								echo '<div class="player">';	
								$i = 0;								
								// loop over the results									
								
								foreach($showresults as $application):
									$i = $i+1;
									if($i<10):
										$numbers = '0'.$i;
									else:
										$numbers = $i;
									endif;
									$application_type = explode('/', $application->post_mime_type);
									// Determine mime type and set the file type icon class
									$icon = $application_type[1];
				
									if ($icon =='mpeg'):
										$icon = 'mpeg';
									endif;
									if ($icon =='wav'):
										$icon = 'wav';
									endif;
									if ($icon =='ogg'):
										$icon = 'ogg';
									endif;
						
									// check to see if the document description should be displayed
									if($opt_description == '1'):
										$description = '<div class="audio_description">'.$application->post_content.'</div>';
									else:
										$description = '';
									endif;
																	
									// encryption class									
									$encryptObject = new MyEncryption();
									$fileURL = $encryptObject->enc_encrypt($application->guid);
									$fileURL = $encryptObject->enc_encrypt($application->guid);
														
						
									// print the final output to the page
									if ($icon =='mpeg'):		
									echo '<div class="audio_filedownload" ><a title="Download the '.$application->post_title.'" href="'.plugin_dir_url(__FILE__).'includes/download.php?file_url='.$fileURL.'">Download</a></div>';
															
									echo '<div class="playlink"><span class="download">'.$numbers.'</span> <a class="taglink" href="#"  data-src="'.$application->guid.'">'.$application->post_title.'</a></div>';
									echo $description;	
									else:
									echo '<div class="audio_filedownload" ><a title="Download the '.$application->post_title.'" href="'.plugin_dir_url(__FILE__).'includes/download.php?file_url='.$fileURL.'">Download</a></div>';
															
									echo '<div><span class="download">'.$numbers.'</span> <span class="downloadonly">'.$application->post_title.'</span></div>';	
									endif;
																		
								endforeach;
								echo '</div>
								</div>';
								echo '</aside>';
							endif;
						endif;
					endif;	
				}			
			endif;	

		endif;
		if(isset($showresults)):
			$quick_check = count($showresults);	
				if(!$quick_check ==0){
					echo $after_widget;
				}
		endif;
		
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '','limit' => '','mpeg_audio' => '','ogg_audio' => '','wav_audio' => '','order_ascending' => '','doc_description' => '','hide_sidebar' => '') );
	
	
		$instance['title'] = strip_tags($new_instance['title']);
		
		$instance['limit'] = strip_tags($new_instance['limit']); // NEW
		
		$instance['mpeg_audio'] = $new_instance['mpeg_audio'] ? 1 : 0;		
		$instance['ogg_audio'] = $new_instance['ogg_audio'] ? 1 : 0;
		$instance['wav_audio'] = $new_instance['wav_audio'] ? 1 : 0;
		
			
		$instance['order_ascending'] = $new_instance['order_ascending'] ? 1 : 0;
		$instance['doc_description'] = $new_instance['doc_description'] ? 1 : 0;
		$instance['hide_sidebar'] = $new_instance['hide_sidebar'] ? 1 : 0;
			
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {			
			$instance = wp_parse_args( (array) $instance, array( 'title' => '','limit' => '','mpeg_audio' => '','ogg_audio' => '','wav_audio' => '','order_ascending' => '','doc_description' => '', 'hide_sidebar' => '') );
			$title = esc_attr($instance['title']);
			// NEW
			
			$limit = $instance['limit']; // NEW 
			
			$opt_mpeg = $instance['mpeg_audio'] ? 'checked="checked"' : '';	
			$opt_ogg = $instance['ogg_audio'] ? 'checked="checked"' : '';	
			$opt_wav = $instance['wav_audio'] ? 'checked="checked"' : '';	
			$opt_order_ascending = $instance['order_ascending'] ? 'checked="checked"' : '';
			$opt_description = $instance['doc_description'] ? 'checked="checked"' : '';
			$opt_hide_sidebar = $instance['hide_sidebar'] ? 'checked="checked"' : '';
		}
		else {
			$title =  'Audio attachments';
			$limit = '20';
			$opt_mpeg = '';			
			$opt_ogg = '';
			$opt_wav = '';
			$opt_order_ascending = '';
			$opt_description = '';
			$opt_hide_sidebar = '';
		}
		echo 
		'<p><label for="'.$this->get_field_id('title').'">'._e('Title:').'</label> 
		<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" /></p>
		
		<p><label for="'.$this->get_field_id('limit').'">Limit the number of items returned.</label> 
		<input class="widefat" id="'.$this->get_field_id('limit').'" name="'.$this->get_field_name('limit').'" type="text" value="'.$limit.'" /></p>
		
		<p><em>Select audio file types,<br />which are allowed to be displayed, only MPEG files are supported by the player. Any other audio type can only be added as a download item:</em><br />
		<input class="checkbox" type="checkbox" '.$opt_mpeg.' id="'.$this->get_field_id('mpeg_audio').'" name="'.$this->get_field_name('mpeg_audio').'" /> <label for="'.$this->get_field_name('mpeg_audio').'">.MPEG Audio</label><br />
				
		<input class="checkbox" type="checkbox" '.$opt_ogg.' id="'.$this->get_field_id('ogg_audio').'" name="'.$this->get_field_name('ogg_audio').'" /> <label for="'.$this->get_field_name('ogg_audio').'">.OGG Audio</label><br />
		
		
				
		<input class="checkbox" type="checkbox" '.$opt_wav.' id="'.$this->get_field_id('wav_audio').'" name="'.$this->get_field_name('wav_audio').'" /> <label for="'.$this->get_field_name('wav_audio').'">.WAV Audio</label><br />
		
		<hr />		
		<p><em>Hide the widget from the sidebar:</em><br />
		<input class="checkbox" type="checkbox" '.$opt_hide_sidebar.' id="'.$this->get_field_id('hide_sidebar').'" name="'.$this->get_field_name('hide_sidebar').'" /> <label for="'.$this->get_field_name('hide_sidebar').'">Hide from sidebars</label></p>
		<hr />	
		<p><em>Order the list in an ascending order :</em><br />
		<input class="checkbox" type="checkbox" '.$opt_order_ascending.' id="'.$this->get_field_id('order_ascending').'" name="'.$this->get_field_name('order_ascending').'" /> <label for="'.$this->get_field_name('order_ascending').'">Ascending order</label></p>
		<hr />				
		<p><em>Display the files description text:</em><br />
		<input class="checkbox" type="checkbox" '.$opt_description.' id="'.$this->get_field_id('doc_description').'" name="'.$this->get_field_name('doc_description').'" /> <label for="'.$this->get_field_name('doc_description').'">Show description text</label></p><br />';

	}

} 
// register Attachment_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget("Audio_Attachment_Widget");' ) );
	
?>