<?php


/**


 * Plugin Name: Google News Just Better


 * Plugin URI: http://www.stefaniamarchisio.com/google-news-widget-shortcode-plugin/


 * Description: Important Note: This plugin still works at this time (wp version 3.4.1) but wont be maintained any longer. Please uninstall it and use [RSS just better](http://wordpress.org/extend/plugins/rss-just-better/) instead which covers the same functionalities and offers a few extra customizations too.


 * Version: 1.4


 * Author: Stef Marchisio


 * Author URI: http://stefaniamarchisio.com/about/


 *


 * This program is distributed in the hope that it will be useful,


 * but WITHOUT ANY WARRANTY; without even the implied warranty of


 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


 */





/* 


This is a wordpress plugin compatible with wordpress 2.8+ as a widget; wordpress 2.5+ as shortcode.


Copyright (C) 2012  Stefania Marchisio (email: stefonthenet@gmail.com)





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





/**


 * Add function to widgets_init


 * @since 0.1


 */


add_action( 'widgets_init', 'funct_gnews' );





/**


 * Register our widget.


 * 'WP_gnews' is the widget class used below.


 *


 * @since 0.1


 */











function funct_gnews() {


   register_widget( 'WP_gnews' );


}





/**


 * WP_gnews Widget class.


 * This class handles everything that needs to be handled with the widget:


 * the settings, form, display, and update.  Nice!


 *


 * @since 0.1


 */





class WP_gnews extends WP_Widget {















































/**























 * Widget setup.























 */























 function WP_gnews() {























 /* Widget settings. */























 $widget_ops = array( 'classname' => 'googlenewsjustbetter', 'description' => __('A customizable list of feed items given: URL and number of displayable items. Also available as shortcode. Compatible with RSS vers. 0.91, 0.92 and 2.0 & Atom 1.0.', 'gnews') );















































 /* Widget control settings. */























 $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'gnews-just-better' );















































 /* Create the widget. */























 $this->WP_Widget( 'gnews-just-better', __('Google News Just Better', 'gnews'), $widget_ops, $control_ops );























 }















































/**























 * How to display the widget on the screen.























 */























 function widget( $args, $instance ) {























	extract( $args );















































	/* Our variables from the widget settings. */























  	$title = apply_filters('widget_title', $instance['title'] );























	$feed = $instance['name'];























	$cachefeed = $instance['cachefeed'];















































	$filter = $instance['filter'];























	$num = $instance['number'];























	$list = $instance['list'];























	$target= $instance['target'];























	$charex = $instance['charex'];























	$dformat = $instance['dformat'];























	$tformat = $instance['tformat'];























	$chartle = $instance['chartle'];























	$sort = $instance['sort'];















































    /* Boolean vars */























	$lkbtitle = isset( $instance['lkbtitle'] ) ? $instance['lkbtitle'] : false;























	$pubdate = isset( $instance['pubdate'] ) ? $instance['pubdate'] : false;























	$pubtime = isset( $instance['pubtime'] ) ? $instance['pubtime'] : false;























	$pubauthor = isset( $instance['pubauthor'] ) ? $instance['pubauthor'] : false;























	$excerpt = isset( $instance['excerpt'] ) ? $instance['excerpt'] : false;























	$sort = isset( $instance['sort'] ) ? $instance['sort'] : false;















































	/* Before widget (defined by themes). */























	echo $before_widget;















































	$tle = '';























	/* Display the widget title if one was input (before and after defined by themes). */























	if ( $title ) {























        	if ( $lkbtitle ) $tle = "<a target='" . $target . "' href='$feed' title='$title'><img src='/wp-content/plugins/RSS-just-better/rss-cube.gif' width='25px' height='25px' title=' [feed link] '></a> ";























			echo $before_title . $tle . $title . $after_title;























        }















































	/* Call the function to read the feed content */























        echo gnews_List($feed, $filter, $num, $list, $target, $pubdate, $pubtime, $dformat, $tformat, $pubauthor, $excerpt, $charex, $chartle, $sort, $cachefeed);















































	/* After widget (defined by themes). */























	echo $after_widget;























}















































/**























 * Update the widget settings.























 */























function update( $new_instance, $old_instance ) {























	$instance = $old_instance;















































	/* Strip tags for title & name 2 remove HTML (important for text inputs). */























	$instance['title'] = strip_tags($new_instance['title']);























	$instance['name'] = strip_tags($new_instance['name']);























	$instance['filter'] = strip_tags($new_instance['filter']);















































	$instance['number'] = strip_tags($new_instance['number']);























	$instance['charex'] = strip_tags($new_instance['charex']);























	$instance['chartle'] = strip_tags($new_instance['chartle']);























	$instance['dformat'] = strip_tags($new_instance['dformat']);























	$instance['tformat'] = strip_tags($new_instance['tformat']);























	$instance['cachefeed'] = strip_tags($new_instance['cachefeed']);















































	/* No need to strip tags for drop-down menus */























	$instance['target'] = $new_instance['target'];























	$instance['list'] = $new_instance['list'];















































	/* No need to strip tags for checkboxes */























	$instance['lkbtitle'] = $new_instance['lkbtitle'];























	$instance['pubdate'] = $new_instance['pubdate'];























	$instance['pubtime'] = $new_instance['pubtime'];























	$instance['pubauthor'] = $new_instance['pubauthor'];























	$instance['excerpt'] = $new_instance['excerpt'];























	$instance['sort'] = $new_instance['sort'];















































    return $instance;























}















































/**























 * Displays the widget settings controls on the widget panel.























 * Make use of the get_field_id() and get_field_name() function























 * when creating your form elements. This handles the confusing stuff.























 */























function form( $instance ) {















































	/* Default widget settings. */























	$defaults = array( 'title' => __('The Latest RSS Items', 'gnews'), 'name' => __('http://feeds.feedburner.com/StefaniasBlog?format=xml', 'gnews'), 'lkbtitle' => 'off', 'filter' => '', 'number' => '5', 'pubdate' => 'off', 'pubtime' => 'off', 'dformat' => '', 'tformat' => '', 'pubauthor' => 'on', 'excerpt' => 'off', 'charex' => '', 'chartle' => '', 'list' => 'ul', 'target' => '_blank', 'sort' => 'false', 'cachefeed' => '3600');















































	$instance = wp_parse_args( (array) $instance, $defaults ); 















































?>























	<!-- Widget Title: Text Input -->























	<p>























	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget Title:', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />























	</p>















































	<!-- Link Title to Feed? Checkbox -->























	<p>























	<label for="<?php echo $this->get_field_id( 'lkbtitle' ); ?>"><?php _e('Link Widget Title to RSS/Atom Feed URL?', 'gnews'); ?></label>























	<input class="checkbox" type="checkbox" <?php checked( $instance['lkbtitle'], 'on' ); ?> id="<?php echo $this->get_field_id( 'lkbtitle' ); ?>" name="<?php echo $this->get_field_name( 'lkbtitle' ); ?>" /> 























	</p>















































	<!-- this changes according to the plugin -->























	<!-- Feed URL: Text Input -->























	<p>























	<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e('RSS/Atom Feed URL:', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:95%;" />























	</p>























	<!-- end changes according to plugin -->















































	<!-- Cache Refresh Frequency(sec.): Text Input -->























	<p>























	<label for="<?php echo $this->get_field_id( 'cachefeed' ); ?>"><?php _e('Cache Refresh Frequency (in sec.):', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'cachefeed' ); ?>" name="<?php echo $this->get_field_name( 'cachefeed' ); ?>" value="<?php echo $instance['cachefeed']; ?>" style="width:10%;" />























	</p>















































	<!-- Sort by Title: checkbox -->























	<p>























	<label for="<?php echo $this->get_field_id( 'sort' ); ?>"><?php _e('Sort by Title (instead of date/time):', 'gnews'); ?></label>























	<input class="checkbox" type="checkbox" <?php checked( $instance['sort'], 'on' ); ?> id="<?php echo $this->get_field_id( 'sort' ); ?>" name="<?php echo $this->get_field_name( 'sort' ); ?>" /> 	























	</p>















































	<!-- Text Filter: Text Input -->























	<p>























	<label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e('Keywords Filter:', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" value="<?php echo $instance['filter']; ?>" style="width:95%;" />























	<br /><span style="font-size: 0.8em;">Example: with [foo -bar] items need to include "foo" and not "bar" words. Case insensitive, no wildchars, quotes, booleans or exact phrases are accepted.</span>























 	</p>















































	<!-- N. articles to display: Text Input -->























	<p>























	<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('No. of Items:', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:10%;" />























	</p>























	<!-- Limit/Truncate title to ... chars: Text Input -->























<p>























<label for="<?php echo $this->get_field_id( 'chartle' ); ?>"><?php _e('Limit Item title to ', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'chartle' ); ?>" name="<?php echo $this->get_field_name( 'chartle' ); ?>" value="<?php echo $instance['chartle']; ?>" style="width:10%;" /> chars.























	</p>















































	<!-- Show Publication Date? Checkbox -->























	<p>























	<label for="<?php echo $this->get_field_id( 'pubdate' ); ?>"><?php _e('Show item date?', 'gnews'); ?></label>























	<input class="checkbox" type="checkbox" <?php checked( $instance['pubdate'], 'on' ); ?> id="<?php echo $this->get_field_id( 'pubdate' ); ?>" name="<?php echo $this->get_field_name( 'pubdate' ); ?>" /> 























	<label for="<?php echo $this->get_field_id( 'dformat' ); ?>"><?php _e(' Date Format ', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'dformat' ); ?>" name="<?php echo $this->get_field_name( 'dformat' ); ?>" value="<?php echo $instance['dformat']; ?>" style="width:20%;" /> chars.























	</p>















































	<!-- Show Publication Time? Checkbox -->























	<p>























	<label for="<?php echo $this->get_field_id( 'pubtime' ); ?>"><?php _e('Show item time?', 'gnews'); ?></label>























	<input class="checkbox" type="checkbox" <?php checked( $instance['pubtime'], 'on' ); ?> id="<?php echo $this->get_field_id( 'pubtime' ); ?>" name="<?php echo $this->get_field_name( 'pubtime' ); ?>" /> 























	<label for="<?php echo $this->get_field_id( 'tformat' ); ?>"><?php _e(' Time Format ', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'tformat' ); ?>" name="<?php echo $this->get_field_name( 'tformat' ); ?>" value="<?php echo $instance['tformat']; ?>" style="width:20%;" /> chars.























	<br /><span style="font-size: 0.8em;">Learn how to customize you <a target="_blank" href="http://codex.wordpress.org/Formatting_Date_and_Time">Date and Time Formats</a>.</span>























	</p>















































	<!-- Show Excerpt? Checkbox -->























	<p>























	<label for="<?php echo $this->get_field_id( 'excerpt' ); ?>"><?php _e('Show excerpt?', 'gnews'); ?></label>























	<input class="checkbox" type="checkbox" <?php checked( $instance['excerpt'], 'on' ); ?> id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" />























	<label for="<?php echo $this->get_field_id( 'charex' ); ?>"><?php _e(' and limit it to ', 'gnews'); ?></label>























	<input id="<?php echo $this->get_field_id( 'charex' ); ?>" name="<?php echo $this->get_field_name( 'charex' ); ?>" value="<?php echo $instance['charex']; ?>" style="width:10%;" /> chars.























	<br /><span style="font-size: 0.8em;">(Warning. the excerpt may contain formatting/images: might not be suitable for sidebars)</span>























         </p>















































	<!-- List Type: Select Box -->























	<p>























	<label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php _e('List Type:', 'gnews'); ?></label> 























	<select id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" class="widefat" style="width:95%;">























	<option value="ul" <?php if ('ul' == $instance['list']) echo 'selected';?>>Unordered (or Dotted) List (default)</option>























	<option value="ol" <?php if ('ol' == $instance['list']) echo 'selected';?>>Ordered (or Numbered) List</option>























	</select>























	</p>















































	<!-- Target: Select Box -->























	<p>























	<label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php _e('Target:', 'gnews'); ?></label> 























	<select id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>" class="widefat" style="width:95%;">























	<option value="_blank" <?php if ('_blank' == $instance['target']) echo 'selected';?>>Open link in a new window (default)</option>























	<option value="_self" <?php if ('_self' == $instance['target']) echo 'selected';?>>Open link in the same window</option>























	</select>























	</p>















































	<!-- Show Plugin Homepage? Checkbox -->























	<p>























	<label for="<?php echo $this->get_field_id( 'pubauthor' ); ?>"><?php _e('Show footer [link to this plugin homepage]?', 'gnews'); ?></label>























	<input class="checkbox" type="checkbox" <?php checked( $instance['pubauthor'], 'on' ); ?> id="<?php echo $this->get_field_id( 'pubauthor' ); ?>" name="<?php echo $this->get_field_name( 'pubauthor' ); ?>" /> 























	<br /><span style="font-size: 0.9em;">(please, say yes)</span>























	</p>























<?php























	}























}















































/**























 * The following code is for the shortcode























 * @since 0.2























 */























/**























 * Add shortcode to wordpress























 */























// add_shortcode('gnews', 'gnews_funct');























add_shortcode('gnews', array('gnewsClass', 'gnews_funct'));















































/**




















 * Shortcode Class and Function























 */















































class gnewsClass {















































	function gnews_funct($atts) {























     		extract(shortcode_atts(array(























		"feed" => '', // required























     		"filter" => '',























     		"num" => '5',























     		"list" => 'ul',























     		"target" => '_blank',























     		"pubdate" => 'false',























     		"pubtime" => 'false',























		"dformat" => get_option('date_format'),























		"tformat" => get_option('time_format'),























    		"pubauthor" => 'true',























     		"excerpt" => 'false',























     		"charex" => '',























     		"chartle" => '',























     		"title" => '',























     		"link" => 'false',























		"sort" => 'false',























     		"cachefeed" => '3600'























	), $atts));







































































	if ($feed) {















































		/* Display title and/or link-to-feed from given attributes */























     		$tle = '';























     		if ( filter_var($link, FILTER_VALIDATE_BOOLEAN) ) { 























			$tle = "<a target='$target' href='$feed'><img src='/wp-content/plugins/google-news-widget/rss-cube.gif' width='25px' height='25px' alt=' [feed link] '></a> "; 























		}























      		if ( $title ) { 























     			$tle .= "<b>$title</b>"; 























    		} 

















		return $tle . gnews_List($feed, $filter, $num, $list, $target, $pubdate, $pubtime, $dformat, $tformat, $pubauthor, $excerpt, $charex, $chartle, $sort, $cachefeed);























	} else {























		return '<br>RSS or Atom Feed URL not provided. This shortcode does require the attribute feed.<br /> Ex: <code>[gnews feed = "http://your-rss-or-atom-feed-URL-here"]</code>.'; 























	}

















	}























}







































































/*























From URL: http://www.rssboard.org/rss-2-0-1#hrelementsOfLtitemgt























(The latest specification of an RSS feed (2.0.1). Backcompatible with versions 2.0, 0.92, 0.91)























A channel may contain any number of <item>s. An item may represent a "story" -- much like a story in a newspaper or magazine; if so its description is a synopsis of the story, and the link points to the full story. An item may also be complete in itself, if so, the description contains the text (entity-encoded HTML is allowed), and the link and title may be omitted. All elements of an item are optional, however at least one of title or description must be present. 























*/















































function gnews_List($feed, $filter, $num, $list, $target, $pubdate, $pubtime, $dformat, $tformat, $pubauthor, $excerpt, $charex, $chartle, $sort, $cachefeed) {























	include_once(ABSPATH . WPINC . '/feed.php');















































	// set cache recreation frequency (in seconds)























	add_filter( 'wp_feed_cache_transient_lifetime' , create_function( '$a', 'return '.$cachefeed.';' )  );















































	// unset cache recreation frequency























	// remove_filter( 'wp_feed_cache_transient_lifetime' , create_function( '$a', 'return 42300;' )  );















































	// decoding needed when you use a shortcode as URLs are encoded by the shortcode























	$feed = html_entity_decode($feed);  















































	// fetch feed using simplepie. Returns a standard simplepie object























	$rss = fetch_feed($feed);























	// Checks that the object is created correctly 























	if (is_wp_error( $rss )) 











		$flist = "Ooops...this plugin cannot read your feed at this time: it might be temp. not available or - if you use it for the first time - you might have mistyped the url or it might be a misformatted RSS or Atom feed.<br>Please, check if your browser can read it instead: <a target='_blank' href='$feed'>$feed</a>.<br> If yes, contact me at stefonthenet AT gmail DOT com with your RSS or Atom feed URL and the shortcode or widget params you want to use it with; if not, contact the feed URL provider.<br>";























	else {























    		// Figure out how many total items there are, but limit it to the given param $num. 























		$nitems = $rss->get_item_quantity($num);























		if ($nitems == 0) $flist = '<li>No item found in feed URL.</li>';























		else {















































		// Build an array of all the items, starting with element 0 (first element).























		$rss_items = $rss->get_items(0, $nitems); 















































		// Sort by title























		if (filter_var($sort, FILTER_VALIDATE_BOOLEAN)) asort($rss_items); 















































		$flist = "<$list>\n";















































		foreach ( $rss_items as $item ) {























			// get title from feed























			$title = esc_html( $item->get_title() );















































			$exclude = false;























			$include = 0;























			$includetest = false;























			if ($filter) {























				$aword = explode( ' ' , $filter );























				// count occurences of each $ainclude element in $title























				foreach ( $aword as $word ) {























					if ( substr($word,0,1) == "-" ) {























						// this word needs to be excluded as it starts with a -























						stripos( $title, substr($word,1) )===false?$exclude=false:$exclude=true;























						// if it finds the word that excludes the item, then breaks the loop























						if ($exclude) break;















































					} else {























						$includetest=true;























						// this word needs to be included























						if ( stripos( $title, $word )!==false) $include++;























						// if it finds the word that includes the item, then set the nclusion variable























						// i cannot break the look as i might still find exclusion words 























					}























 				}























			} // if ($filter)















































			// either (at least one occurrence of searched words is found AND no excluded words is found)























			if ( !$exclude and ($includetest===false or $include>0)) {















































				// get description from feed























				$desc = esc_html( $item->get_description() );















































				$flist .= "<li>";























				// get title from feed























				$title = gnews_cdataize( $item->get_title() );























				// get description from feed























				$desc = gnews_cdataize( $item->get_description() );















































				// sanitize title and description























				$title = gnews_sanitxt( $title );























				$desc = gnews_sanitxt( $desc );















































				if ($chartle>=1) $title=substr($title, 0, $chartle).'...';















































				if ( $title || $desc ) {























					if ( $item->get_permalink() ) {























					$permalink = esc_url( $item->get_permalink() );























				   	$motext = isset( $desc ) ? $desc : $title;















































				   	// writes the link























					$flist .= "<a target='$target' href='$permalink' title='".substr($motext,0,400)."...'>";























					}























					// writes the title























					$flist .= isset( $title ) ? $title : $motext;























					// end the link (anchor tag)























					if ( $permalink ) $flist .= '</a>'; 























        























					if ( $item->get_date() ) {























						$datim = strtotime( $item->get_date() );























						if ( filter_var($pubdate, FILTER_VALIDATE_BOOLEAN) ) {























							if (empty($dformat)) $dformat = get_option('date_format');























							$flist .= ' - ' . date( $dformat, $datim );























						}















































              				if ( filter_var($pubtime, FILTER_VALIDATE_BOOLEAN) ) {























							if (empty($tformat)) $tformat = get_option('time_format');























							$flist .= ' at ' . date ( $tformat, $datim ); 























						}























					}















































					if ($desc && filter_var($excerpt, FILTER_VALIDATE_BOOLEAN)) {























						if ( $charex >= 1 && $charex<strlen($desc) ) { 























							$flist .= '<br/>' . substr($motext, 0, $charex) . " [<a target='$target' href='$permalink'>...</a>]";























						} else {























							$flist .= '<br/>' . $motext;























						}	























					}























				} else {























					$flist .= '<li>No standard <item> in file.';























				}























				$flist .= '</li>';























			} // if ($count>0 || !$filter)























		} // foreach















































		$flist .= "</$list>\n";















































		} // else of if ($nitems == 0)















































	} // else of if (is_wp_error( $rss ))















































 	// if pubauthor has been selected























	if ( filter_var($pubauthor, FILTER_VALIDATE_BOOLEAN) ) {























		$flist .= '<p style="text-align:center; font-size: 0.8em">powered by <a target="_blank" href="http://www.stefaniamarchisio.com/google-news-widget-shortcode-plugin/">Google News Just Better</a> 1.4 plugin</p>';























	}























	return $flist;























}















































function gnews_cdataize($content) {























$content = preg_replace('/\&\#([0-9]+);/','<![CDATA[&#\\1;]]>',$content);























$content = preg_replace('/\&\#x([0-9A-F]+);/i','<![CDATA[&#\\1;]]>',$content);























return($content);























}















































function gnews_convert_smart_quotes($string) 























{ 























    $search = array(chr(145), 























                    chr(146), 























		    chr(0x60), // grave accent























		    chr(0xB4), // acute accent























		    chr(0x91),  























		    chr(0x92),  























		    chr(0x93),  























		    chr(0x94),  























                    chr(147), 























                    chr(148), 























                    chr(151)); 























 























    $replace = array("'", 























                     "'", 























                     "'", 























                     "'", 























                     '"', 























                     '"', 























                     '"', 























                     '"', 























                     '"', 























                     '-'); 























 























    return str_replace($search, $replace, $string,$countN); 























} 















































function gnews_sanitxt($sanitxt) {























	// add a space between closing and opening tags























	$sanitxt = str_replace('><','> <',$sanitxt);























	// remove HTML tags























	$sanitxt = strip_tags($sanitxt);























	// remove multiple spaces























	$sanitxt = preg_replace('/\s+/', ' ', $sanitxt);























	// convert smart quotes into normal























	gnews_convert_smart_quotes($sanitxt);























	// replace quotes and ampersands with HTML chars (recommended)























	// and encode string to UTF-8. UTF-8 is default from PHP 5.4.0 only























	$sanitxt = htmlspecialchars($sanitxt,ENT_QUOTES,"UTF-8",FALSE);















































return($sanitxt);























}























?>