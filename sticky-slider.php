<?php
/*
Plugin Name: Sticky Slider
Plugin URI: http://www.blogtycoon.net/wordpress-plugins/sticky-slider/
Description: WordPress provides a way to mark certain posts as featured or sticky posts. Sticky posts will appear before other posts when listing them in index.php. This plugin creates a slider from sticky posts.
Version: 1.1.1
Author: Ciprian Popescu
Author URI: http://www.blogtycoon.net/
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Copyright 2011 Ciprian Popescu (email: office@butterflymedia.ro)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

phpMyAdmin is licensed under the terms of the GNU General Public License
version 2, as published by the Free Software Foundation.
*/
if(!defined('WP_CONTENT_URL')) define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if(!defined( 'WP_PLUGIN_URL')) define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if(!defined('WP_CONTENT_DIR')) define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if(!defined('WP_PLUGIN_DIR')) define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

define('STICKY_SLIDER_URL', WP_PLUGIN_URL.'/sticky-slider');
define('STICKY_SLIDER_PATH', WP_PLUGIN_DIR.'/sticky-slider');

// Begin display functions
function sticky_slider_scripts() {
	$sticky_timer = get_option('sticky_timer');
	$sticky_timer = $sticky_timer * 1000;
	?>
	<!-- // Begin Sticky Slider Options -->
	<style type="text/css">
	#featured { height: 200px; overflow: hidden; }
	#featured h2 { font-size: 24px; }

	.slider-controls { float: left; }
	.sticky-clear { clear:both; }

	#slider-nav { float: right;}
	#slider-nav a { border: 1px solid #DDDDDD; background-color: #EEEEEE; text-decoration: none; margin: 0 1px; padding: 3px 5px; font-size: 9px; text-shadow: 0 -1px 0 #FFFFFF; }
	#slider-nav a.activeSlide { background-color: #DDDDDD; }
	#slider-nav a:focus { outline: none; }
	</style>

	<script type="text/javascript" src="<?php echo STICKY_SLIDER_URL;?>/jquery.cycle.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#featured").cycle({
			next: '#slider-next',
			prev: '#slider-prev',
			pager: '#slider-nav',
			pauseOnPagerHover: 1, // pause when hovering over pager link
			timeout: <?php echo $sticky_timer;?>,
			delay: -4000,
			speed: 800,
		});
	});
	</script>
	<!-- // End Sticky Slider Options -->
	<?php
	wp_enqueue_script('jquery');
}
function sticky_slider_jquery() {
	wp_enqueue_script('jquery');            
}    
// End display functions

add_action('admin_menu', 'sticky_slider_plugin_menu');

add_option('sticky_slides', '5', '', 'no');
add_option('sticky_timer', '4', '', 'no');

function sticky_slider_plugin_menu() {
	add_options_page('Sticky Slider', 'Sticky Slider', 'manage_options', 'ss', 'sticky_slider_plugin_options');
}
function sticky_slider_plugin_options() {
	if(!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	$hidden_field_name = 'sticky_submit_hidden';
	$data_field_name_1 = 'sticky_slides';
	$data_field_name_2 = 'sticky_timer';

	// read in existing option value from database
    $option_value_data_1 = get_option('sticky_slides');
    $option_value_data_2 = get_option('sticky_timer');

    // See if the user has posted us some information // if they did, this hidden field will be set to 'Y'
	if(isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
		$option_value_data_1 = $_POST[$data_field_name_1];
		$option_value_data_2 = $_POST[$data_field_name_2];

		update_option('sticky_slides', $option_value_data_1);
		update_option('sticky_timer', $option_value_data_2);
		?>
		<div class="updated"><p><strong>Settings saved</strong></p></div>
		<?php
	}
	echo '<div class="wrap">';
		echo '<div id="icon-tools" class="icon32"></div>';
		echo '<h2>Sticky Slider Settings</h2>';
		?>
		<form name="form1" method="post" action="">
			<input type="hidden" name="<?php echo $hidden_field_name;?>" value="Y" />
			<p>
				<input type="text" name="<?php echo $data_field_name_1;?>" id="<?php echo $data_field_name_1;?>" value="<?php echo $option_value_data_1;?>" size="3" /> 
				<label for="<?php echo $data_field_name_1;?>">Number of slides</label>
			</p>
			<p><span class="description">How many sticky posts do you want to slide? Default is 5.</span></p>
			<p>
				<input type="text" name="<?php echo $data_field_name_2;?>" id="<?php echo $data_field_name_2;?>" value="<?php echo $option_value_data_2;?>" size="3" /> 
				<label for="<?php echo $data_field_name_2;?>">Slides timeout (in seconds)</label>
			<p>
			<p><span class="description">How long should a sticky post display before sliding the next one? Default is 4.</span></p>
			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e('Save Changes');?>" />
			</p>
		</form>

		<hr />
		<p>Add the <code>&lt;?php if(function_exists('sticky_slider')) sticky_slider();?&gt;</code> function to your index.php before <code>&lt;?php if(have_posts()) : while(have_posts()) : the_post();?&gt;</code> line.</p>

		<p>For support, feature requests and bug reporting, please visit the <a href="http://www.blogtycoon.net/wordpress-plugins/sticky-slider/" rel="external">official web site</a>, or rate it on <a href="http://wordpress.org/extend/plugins/sticky-slider/" rel="external">WordPress plugin repository.</a></p>
	</div>
<?php
}

add_action('init', 'sticky_slider_jquery');
add_action('wp_head', 'sticky_slider_scripts');

function sticky_slider() {
	?>
	<?php if(is_home()) {?>
		<div class="featured-wrapper">
			<div class="slider-controls">
				<a id="slider-prev" class="slider-control" href="#" title="Previous Post">&laquo; Previous</a>
				<a id="slider-next" class="slider-control" href="#" title="Next Post">Next &raquo;</a>
			</div>
			<div id="slider-nav"></div>
			<div class="sticky-clear"></div>
			<div id="featured">
				<?php
				$sticky_slides = get_option('sticky_slides');

				$loop = new WP_Query(array(
					'showposts' => $sticky_slides,
					'post__in'  => get_option('sticky_posts'),
					'caller_get_posts' => 1
				));
				while($loop->have_posts()) : $loop->the_post();
					?>
					<div>
						<h2><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php the_title();?></a></h2>
						<div class="entry-summary">
							<?php the_excerpt();?>
						</div>
					</div>
				<?php endwhile;?>
			</div>
		</div>
	<?php }?>
<?php }?>
