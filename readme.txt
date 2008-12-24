=== Get My Tweets ===
Contributors: south_platte
Donate link: http://www.southplattewebdesign.com/getmytweets-wordpress-plugin/
Tags: twitter, xmlreader
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 0.3.1

Simple plugin to return a user defined number of tweets from Twitter.

== Description ==

Using PHP 5 and XMLReader, this plugin will load a user definable number of Tweets from Twitter.  It requires that the Twitter user name be supplied to the plugin, and the desired number of Tweets to retrieve.  It also requires that user's Twitter timeline be "public" to be able to be parsed and read.  The plugin will parse one link per Tweet.

== Installation ==

1. Upload `getMyTweets.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. You can add the widget through the 'Widgets' menu under 'Appearance'
1. If you are not using themes, you can add `<?php get_my_tweets();?>` to your theme's files where you want your tweets to show up.

== Frequently Asked Questions ==

= Is there a specific PHP version? =

Yes, Get My Tweets requires PHP version 5 or higher with the XMLReader extension loaded

== Screenshots ==

1. Get My Tweets options.