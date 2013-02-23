<?php
/*
Plugin Name: Responsive Sticky Slider
Plugin URI: http://getbutterfly.com/wordpress-plugins/sticky-slider/
Description: WordPress provides a way to mark certain posts as featured or sticky posts. Sticky posts will appear before other posts when listing them in index.php. This plugin creates a slider from sticky posts.
Version: 1.2.1
Author: Ciprian Popescu
Author URI: http://getbutterfly.com/
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Copyright 2011, 2012, 2013 Ciprian Popescu (email: getbutterfly@gmail.com)

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

//
define('STICKY_SLIDER_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('STICKY_SLIDER_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
//

// plugin localization
$plugin_dir = basename(dirname(__FILE__)); 
load_plugin_textdomain('rssl', false, $plugin_dir . '/languages'); 
//

// Sticky Slider shortcode
add_shortcode('sticky-slider', 'sticky_slider');

// Begin display functions
function sticky_slider_scripts() {
	?>
	<!-- // Begin Sticky Slider Head Options -->
	<style type="text/css">
	#featured { height: 200px; overflow: hidden; }

	.slider-controls { float: left; }
	.sticky-clear { clear: both; }

	#slider-nav { float: right;}
	#slider-nav a { border: 1px solid #DDDDDD; background-color: #EEEEEE; text-decoration: none; margin: 0 1px; padding: 3px 5px; font-size: 9px; }
	#slider-nav a.activeSlide { background-color: #DDDDDD; }
	#slider-nav a:focus { outline: none; }
	</style>
	<?php
	wp_enqueue_script('jquery');
}
function sticky_slider_cycle() {
	$sticky_timer = get_option('sticky_timer');
	$sticky_timer = $sticky_timer * 1000;
	$sticky_fx = get_option('sticky_fx');
	$sticky_easing = get_option('sticky_easing');
	?>
	<!-- // Begin Sticky Slider Head Options -->
	<script src="<?php echo STICKY_SLIDER_URL; ?>/jquery.cycle.all.js"></script>
	<script src="<?php echo STICKY_SLIDER_URL; ?>/jquery.easing.1.3.js"></script>
	<script>
	jQuery(document).ready(function(){
		jQuery('#featured').cycle({
			next: '#slider-next',
			prev: '#slider-prev',
			pager: '#slider-nav',
			pauseOnPagerHover: 1, // pause when hovering over pager link
			timeout: <?php echo $sticky_timer; ?>,
			delay: -4000,
			speed: 800,
			fx: '<?php echo $sticky_fx; ?>',
			easing: '<?php echo $sticky_easing; ?>',
		});
	});
	</script>
	<!-- // End Sticky Slider Head Options -->
	<?php
}
// End display functions

add_action('admin_menu', 'sticky_slider_plugin_menu');

add_option('sticky_slides', '5');
add_option('sticky_timer', '4');
add_option('sticky_category', '-1');
add_option('sticky_fx', 'turnLeft');
add_option('sticky_easing', 'easeInOutQuad');

function sticky_slider_plugin_menu() {
	add_options_page('Responsive Sticky Slider', 'Responsive Sticky Slider', 'manage_options', 'ss', 'sticky_slider_plugin_options');
}
function sticky_slider_plugin_options() {
	$hidden_field_name = 'sticky_submit_hidden';

    // See if the user has posted us some information // if yes, this hidden field will be set to 'Y'
	if(isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
		update_option('sticky_slides', $_POST['sticky_slides']);
		update_option('sticky_timer', $_POST['sticky_timer']);
		update_option('sticky_fx', $_POST['sticky_fx']);
		update_option('sticky_easing', $_POST['sticky_easing']);
		update_option('sticky_category', $_POST['sticky_category']);
		?>
		<div class="updated"><p><strong>Settings saved</strong></p></div>
		<?php
	}

	$args = array(
		'show_option_all'    => '',
		'show_option_none'   => '',
		'orderby'            => 'ID', 
		'order'              => 'ASC',
		'show_count'         => 1,
		'hide_empty'         => 1, 
		'child_of'           => 0,
		'exclude'            => '',
		'echo'               => 1,
		'selected'           => get_option('sticky_category'),
		'hierarchical'       => 0, 
		'name'               => 'sticky_category',
		'id'                 => '',
		'class'              => 'postform',
		'depth'              => 0,
		'tab_index'          => 0,
		'taxonomy'           => 'category',
		'hide_if_empty'      => false
	);

	echo '<div class="wrap">';
		echo '<div id="icon-tools" class="icon32"></div>';
		echo '<h2>Responsive Sticky Slider Settings</h2>';
		?>
		<h3>Plugin Settings</h3>
		<form name="form1" method="post" action="">
			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />
			<p>
				<input type="number" name="sticky_slides" id="sticky_slides" value="<?php echo get_option('sticky_slides'); ?>" min="1" max="99"> 
				<label for="sticky_slides">Number of slides</label>
			</p>
			<p><span class="description">How many sticky posts do you want to slide? Default is 5.</span></p>
			<p>
				<input type="number" name="sticky_timer" id="sticky_timer" value="<?php echo get_option('sticky_timer'); ?>" min="1" max="99"> 
				<label for="sticky_timer">Slides timeout (in seconds)</label>
			<p>
			<p><span class="description">How long should a sticky post display before sliding the next one? Default is 4.</span></p>

			<p>
				<?php wp_dropdown_categories($args); ?>
				<label for="sticky_category">Slides category</label>
			</p>

			</p>
				<select name="sticky_fx" id="sticky_fx">
					<option value="<?php echo get_option('sticky_fx'); ?>" selected="selected"><?php echo get_option('sticky_fx'); ?></option>
					<option value="blindX">blindX</option>
					<option value="blindY">blindY</option>
					<option value="blindZ">blindZ</option>

					<option value="cover">cover</option>

					<option value="curtainX">curtainX</option>
					<option value="curtainY">curtainY</option>

					<option value="fade">fade</option>
					<option value="fadeZoom">fadeZoom</option>

					<option value="growX">growX</option>
					<option value="growY">growY</option>

					<option value="scrollUp">scrollUp</option>
					<option value="scrollDown">scrollDown</option>
					<option value="scrollLeft">scrollLeft</option>
					<option value="scrollRight">scrollRight</option>
					<option value="scrollHorz">scrollHorz</option>
					<option value="scrollVert">scrollVert</option>

					<option value="shuffle">shuffle</option>

					<option value="slideX">slideX</option>
					<option value="slideY">slideY</option>

					<option value="toss">toss</option>

					<option value="turnUp">turnUp</option>
					<option value="turnDown">turnDown</option>
					<option value="turnLeft">turnLeft</option>
					<option value="turnRight">turnRight</option>

					<option value="uncover">uncover</option>
					<option value="wipe">wipe</option>
					<option value="zoom">zoom</option>
				</select> 
				<select name="sticky_easing" id="sticky_easing">
					<option value="<?php echo get_option('sticky_easing'); ?>" selected="selected"><?php echo get_option('sticky_easing'); ?></option>
					<option value="jswing">jswing</option>
					<option value="def">def</option>
					<option value="easeInQuad">easeInQuad</option>
					<option value="easeOutQuad">easeOutQuad</option>
					<option value="easeInOutQuad">easeInOutQuad</option>
					<option value="easeInCubic">easeInCubic</option>
					<option value="easeOutCubic">easeOutCubic</option>
					<option value="easeInOutCubic">easeInOutCubic</option>
					<option value="easeInQuart">easeInQuart</option>
					<option value="easeOutQuart">easeOutQuart</option>
					<option value="easeInOutQuart">easeInOutQuart</option>
					<option value="easeInQuint">easeInQuint</option>
					<option value="easeOutQuint">easeOutQuint</option>
					<option value="easeInOutQuint">easeInOutQuint</option>
					<option value="easeInSine">easeInSine</option>
					<option value="easeOutSine">easeOutSine</option>
					<option value="easeInOutSine">easeInOutSine</option>
					<option value="easeInExpo">easeInExpo</option>
					<option value="easeOutExpo">easeOutExpo</option>
					<option value="easeInOutExpo">easeInOutExpo</option>
					<option value="easeInCirc">easeInCirc</option>
					<option value="easeOutCirc">easeOutCirc</option>
					<option value="easeInOutCirc">easeInOutCirc</option>
					<option value="easeInElastic">easeInElastic</option>
					<option value="easeOutElastic">easeOutElastic</option>
					<option value="easeInOutElastic">easeInOutElastic</option>
					<option value="easeInBack">easeInBack</option>
					<option value="easeOutBack">easeOutBack</option>
					<option value="easeInOutBack">easeInOutBack</option>
					<option value="easeInBounce">easeInBounce</option>
					<option value="easeOutBounce">easeOutBounce</option>
					<option value="easeInOutBounce">easeInOutBounce</option>
				</select>
				<label for="sticky_fx">Slides effect and</label> <label for="sticky_easing">easing type</label>
			</p>

			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="Save Changes" />
			</p>
		</form>

		<h3>Plugin Usage</h3>
		<p>Add the <code>&lt;?php if(function_exists('sticky_slider')) { sticky_slider(); } ?&gt;</code> function to your page template or use the <code>[sticky-slider]</code> shortcode anywhere in your post or page.</p>

		<p>For support, feature requests and bug reporting, please visit the <a href="http://getbutterfly.com/wordpress-plugins/sticky-slider/" rel="external">official web site</a>, or rate it on <a href="http://wordpress.org/extend/plugins/sticky-slider/" rel="external">WordPress plugin repository.</a></p>
	</div>
<?php
}

add_action('init', 'sticky_slider_scripts');
add_action('wp_footer', 'sticky_slider_cycle');

function sticky_slider() {
	?>
	<div class="featured-wrapper">
		<div class="slider-controls">
			<a id="slider-prev" class="slider-control" href="#" title="<?php echo _('Previous', 'rssl'); ?>">&laquo; <?php echo _('Previous', 'rssl'); ?></a>
			<a id="slider-next" class="slider-control" href="#" title="<?php echo _('Next', 'rssl'); ?>"><?php echo _('Next', 'rssl'); ?> &raquo;</a>
		</div>
		<div id="slider-nav"></div>
		<div class="sticky-clear"></div>
		<div id="featured">
			<?php
			$sticky_slides = get_option('sticky_slides');
			$sticky_posts = get_option('sticky_posts');
			$sticky_category = get_option('sticky_category');

			query_posts(array(
				'post_status' => 'publish',
				'post_type' => array('post'),
				'posts_per_page' => $sticky_slides,
				'cat' => $sticky_category
			));
			if(have_posts()) : while(have_posts()) : the_post();
				?>
				<div>
					<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<div class="entry-summary">
						<?php echo strip_tags(get_the_excerpt()); ?>
					</div>
				</div>
				<?php
			endwhile; endif;
			?>
		</div>
	</div>
<?php } ?>
