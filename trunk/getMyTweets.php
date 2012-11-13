<?php
/*
Plugin Name: getMyTweets
Plugin URI: http://www.southplattewebdesign.com/getMyTweets
Description: Simple plugin to return a user defined number of tweets from Twitter
Version: 0.3.3
Author: Billy Nab
Author URI: http://www.southplattewebdesign.com
Copyright 2008  Billy Nab  (email : bnab@southplattewebdesign.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Version Changes:

0.1.1 - Initial version, no URL parsing, no widget
0.2.1 - Widgetized version
0.3.1 - Added ability to parse one url in tweets
0.3.2 - Modified URL parsing method
0.3.3 - Modified Twitter API URL
*/
function get_my_tweets_menu() {
  add_options_page( 'Get My Tweets', 'Get My Tweets', 8, __FILE__, 'get_my_tweets_options' );
}

function get_my_tweets() {
	$twit_usr = get_option( 'twitter_user_name' );
	$num_tweets = get_option( 'num_tweets_retrieve' );
	$reader = new XMLReader();
	$reader->open( 'http://api.twitter.com/1/statuses/user_timeline/'.$twit_usr.'.xml?count='.$num_tweets );
	while ( $reader->read() ) 
	{
	   if ( $reader->nodeType == XMLREADER::ELEMENT ) 
	   {
	   	  $name = $reader->name;
	      if( $name == "status" )
		  {
		  	while( $reader->read() )
			{
				if( $reader->nodeType == XMLREADER::ELEMENT )
				{
					$noName = $reader->name;
					if( $noName == "text" )
					{
						$reader->read();
						$checkUrl = $reader->value;
						$pattern = "/ http:\/\/(.*?)\/(.*? )/";
						$replacement = "<a href=\"$0\" target=\"_blank\">$0</a>";
						$finalString = preg_replace( $pattern, $replacement, $checkUrl );
						//$finalString = ereg_replace("[[:space:]]+[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]+[[:alnum:]]+[[:space:]]", "<a href=\"\\0\">\\0</a>", $checkUrl);
						$result .= $finalString . '<br><br>';
						//break;
					}//close if noName match
					if( $noName == "created_at" )
					{
						$reader->read();
						$tDate = date_parse($reader->value);
						if($tDate['minute'] < 10)
						{
							$tDate['minute'] = '0'.$tDate['minute'];
						}
						$tweetdate = $tDate['month'].'/'.$tDate['day'].'/'.$tDate['year'].' '.$tDate['hour'].':'.$tDate['minute'];
						$result .= $tweetdate . '<br>';
					}
				}//close if nodeType
			}//close while reader read inner
		  }//close if name item  
	   }//close if nodeType
	}//close while reader-read outer
	return $result;
	$reader->close();//close xmlreader
}//end function
// internal testing $myTweets = getMyTweets();

function get_my_tweets_options() {
	?>
	<div class="wrap">
	<h2>Get My Tweets</h2>	
	<form method="post" action="options.php">	
	<table class="form-table">
	<tr valign="top">
	<th scope="row">Twitter User Name</th>
	<td><input type="text" name="twitter_user_name" value="<?php echo get_option( 'twitter_user_name' ); ?>" /></td>
	</tr>	 
	<tr valign="top">
	<th scope="row">Number Of Tweets To Retrieve</th>
	<td><input type="text" name="num_tweets_retrieve" value="<?php echo get_option( 'num_tweets_retrieve' ); ?>" /></td>
	</tr>	
	</table>	
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="twitter_user_name,num_tweets_retrieve" />
	<?php wp_nonce_field( 'update-options' ); ?>	
	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e( 'Save Changes' ) ?>" />
	</p>
	</form>
	</div>
	<?php 
	}//end get_my_tweets_options
	
	function get_my_tweets_widget_init() {

		function widget_get_my_tweets( $args )
		{
		    extract( $args );
		    echo $before_widget;
		    echo $before_title . 'Recent Tweets' . $after_title;
		    echo get_my_tweets();
		    echo $after_widget;
		}
		if ( !function_exists('register_sidebar_widget') ) return;
			register_sidebar_widget( array( 'Get My Tweets', 'widgets' ), 'widget_get_my_tweets' );
	}
add_action ( 'admin_menu', 'get_my_tweets_menu');	
add_action( 'plugins_loaded',  'get_my_tweets_widget_init' );
?>