<?php
/**
 * NEWS TRAY CHILDREN
 */
if (!defined('ABSPATH')) {
	return;
}

add_filter('news-tray-boolean-enqueue-enable-reduce-motions', '__return_false');

/*!
 * -------------------------------------------------------
 * TEMPLATE
 * -------------------------------------------------------
 */


// disable feature tags
add_action('news-tray-boolean-feature-enable-feature-tags', '__return_false');
// disable youtube
add_action('news-tray-boolean-feature-enable-feature-youtube-playlist', '__return_false');

// change scripts
add_action('wp_enqueue_scripts', function () {
	$is_singular = is_singular();
	$is_home_page = logic_news_tray_is_home_page();
	if ($is_singular || !$is_home_page) {
		wp_dequeue_script( 'news-tray-theme-all' );
		wp_enqueue_script( 'news-tray-theme' );
		if (!$is_singular) {
			wp_enqueue_script( 'news-tray-load-more' );
		}
	}
	wp_enqueue_style('news-tray-child-custom', get_theme_file_uri('assets/css/custom.css'));
}, 40);

// add bottom
if (!function_exists('news_tray_child_after_nav_header')) {
	function news_tray_child_after_nav_header() {
		get_template_part( 'templates/components/header/top-bottom' );
	}
}

add_action('news-tray-after-nav-header', 'news_tray_child_after_nav_header');
add_action('news-tray-boolean-feature-enable-top-menu', '__return_false');

if (!function_exists('news_tray_child_after_setup_theme')) {
	function news_tray_child_after_setup_theme() {
		register_sidebar( [
			'id'            => 'news-tray-child-top-sidebar',
			'name'          => __( 'Top Sidebar Beside Slider', 'news-tray' ),
			'before_widget' => '',
			'after_widget'  => "\n",
			'before_title'  => '',
			'after_title'   => "\n",
		] );
	}
}
add_action('after_setup_theme', 'news_tray_child_after_setup_theme');

add_action('admin_enqueue_scripts', function () {
	wp_enqueue_style('news-tray-child-custom', get_theme_file_uri('assets/css/custom.css'));
});
