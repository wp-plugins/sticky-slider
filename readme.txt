=== Sticky Slider ===
Contributors: butterflymedia
Donate link: http://www.blogtycoon.net/
Tags: slider, slides, sticky, featured, cycle, jquery
Requires at least: 2.7
Tested up to: 3.1
Stable tag: 1.1.2

== Description ==

WordPress provides a way to mark certain posts as featured or sticky posts. Sticky posts will appear before other posts when listing them in index.php. This plugin creates a slider from sticky posts. It features "Previous" and "Next" navigation and slides pagination.

In order to add a post to the slider, just mark it as sticky. Edit its Visibility in the Publish block while in the post writing page and check "Stick this post to the front page".

Check the [official homepage](http://www.blogtycoon.net/wordpress-plugins/sticky-slider/ "Blog Tycoon") for feedback and support.

== Installation ==

1. Upload to your plugins folder, usually `wp-content/plugins/`
2. Activate the plugin on the plugin screen.
3. Add `<?php if(function_exists('sticky_slider')) sticky_slider();?>` to your index template.
4. Configure the slider in Settings | Sticky Slider

== Frequently Asked Questions ==

= How do I add the slider to my blog? =

You need to add the `<?php if(function_exists('sticky_slider')) sticky_slider();?>` PHP function to the index template.

== Screenshots ==

1. Front-end slider with navigation and pagination
2. Back-end section

== Changelog ==

= 1.1.2 =
* Removed shadow from pagination to have a consistent look for dark layouts

= 1.1.1 =
* Administration UI improvements
* Added pause when hovering on pagination
* Added title size as some themes don't have proper heading styles
* Added screenshots for WordPress plugin repository

= 1.1 =
* Added pagination
* Fixed official homepage link
* Fixed the jQuery function (faster and cleaner)
* Removed an unused .js file

= 1.0 =
* First release
