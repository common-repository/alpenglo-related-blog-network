<?php
/*
Plugin Name: AlpenGlo Blog Discovery Network
Plugin URI: http://www.alpenglo.org
Description: Displays related blogs from our network at the end of each post and allows you to track click-through activity for each related blog. In turn, your blog can appear in these same places on related blogs, which helps boost traffic and increase inbound relevant links for better search engine rankings.
Version: 1.0
Author: Chris Charlwood
Author URI: http://www.charlwood.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7398317
*/

function alpenglo_admin() {  
 include('alpenglo_preferences.php'); 
  $ch = curl_init();
$timeout = 5; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, 'http://www.alpenglo.org/plugin/config.php');
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$file_contents = curl_exec($ch);
curl_close($ch);

// display file
echo $file_contents;
}
 
 function alpenglo_admin_actions() {  
add_options_page("AlpenGlo Preferences", "AlpenGlo", 1, "AlpenGlo Settings", "alpenglo_admin");  
 }  

// add the admin menu
add_action('admin_menu', 'alpenglo_admin_actions');  

// get related blogs from database
function get_blogs($content) {
			//Connect to the AlpenGlo database
			$alpenglodb = new wpdb(get_option('oscimp_dbuser'),get_option('oscimp_dbpwd'), get_option('oscimp_dbname'), get_option('oscimp_dbhost'));

$limit=get_option('blog_limit');
			$retval = '';
			//Get blog title and URL
			global $post;
			$purl= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
			$rel2 = $post->post_title;
			$rel3 = $post->post_tags;
				$Related = $alpenglodb->get_results( "SELECT blog_title,blog_url,blog_keywords FROM alpenglo_users WHERE (MATCH (blog_title, blog_keywords) AGAINST ('$rel2' IN BOOLEAN MODE)) OR (MATCH (blog_title, blog_keywords) AGAINST ('$rel3' IN BOOLEAN MODE)) GROUP BY blog_url LIMIT 0,10");				
						
				//LIMIT 0,'$limit'
				$c='0';
				$mainurl='http://www.alpenglo.org/';
				//Build the HTML code
				if ($Related){
				
				$retval .= '<blockquote>';
				$retval .= '<strong>Related blogs</strong> <font size=1>(<a href="http://www.alpenglo.org" target="_blank">submit a blog</a>)</font><br><UL>';
foreach($Related as $key=>$value){
$c=$c+1;
				if ($c<= $limit) {
				
				$retval .= '<LI><a rel="nofollow" href="'.$mainurl.'views.php?blog_url='.$value->blog_url.'&purl='.$purl.'">'. $value->blog_title . '</a></LI>';
				}
}
				$retval .= '</blockquote>';
				}
				
			return $content.$retval;
}
//insert related blogs at the end of each post
add_filter('the_content', 'get_blogs');
?>