<?php
/*
Plugin Name: WP RSS Fetcher ShortCode
Plugin URI: http://www.wpmize.com/wordpress-plugins/wordpress-plugin-wp-rss-fetcher-shortcode/
Description: Use a shortcode to grab RSS feeds from external sources and embed them into posts or pages. Example shortcode usage: [wpmizerss url="http://feeds.feedburner.com/Wpmize" feeds_limit="5" words_limit="100" ul_class=' class="ordered"']
Author: WPMize
Version: 1.0
Author URI: http://www.wpmize.com/
*/

/* 

   Support WordPress MU
   
   To support WPMU just copy this single file (not its parent directory) in
   the /wp-content/mu-plugins/ directory (create it if it does not exists) and
   it will be loaded automatically by default.
   
*/


/*
    Limit words of a string
	http://www.minimalite.com/
*/

function short_name_custom($str, $limit = 30)
{
    if(strlen($str) > $limit) return sprintf('%s', substr($str, 0, $limit)."..."); else return $str;
}

/* 
    Grab RSS Feeds from External Sources 
*/

function get_rss_feeds_from_site($url, $feeds_limit = 5, $words_limit = 100, $ul_class = "", $output = "LIST")
{
	include_once(ABSPATH.WPINC.'/rss.php'); // path to include script
	$feed = fetch_rss($url); // specify feed url
	$items = array_slice($feed->items, 0, $feeds_limit); // specify first and last item
	if (!empty($items)) 
	{
	    $str = '<ul'.$ul_class.'>';
        foreach($items as $item)
        $str .= '<li><a href="'.htmlspecialchars($item['link']).'" title="'.htmlspecialchars($item['title']).'">'.short_name_custom( htmlspecialchars($item['title']), $words_limit ).'</a></li>';
        $str .= '</ul>';
		return $str;
	}
}

/*
    Example shortcode usage:
	
    [wpmizerss url="http://feeds.feedburner.com/Wpmize" feeds_limit="5" words_limit="100" ul_class=' class="ordered"']
*/

function wpmizerss_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'url' => 'http://feeds.feedburner.com/Wpmize',
		'feeds_limit' => '5',
		'words_limit' => '100',
		'ul_class' => '',
	), $atts ) );

	return get_rss_feeds_from_site( $url, $feeds_limit, $words_limit, $ul_class );
}
add_shortcode( 'wpmizerss', 'wpmizerss_shortcode_func' );

?>