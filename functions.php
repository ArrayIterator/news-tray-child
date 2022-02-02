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

/*
add_action('init', function () {
	global $wp;
	$wp->add_query_var('s_map');
	add_rewrite_rule( '^s_map\.xml$', 'index.php?s_map=1', 'top' );
});

add_action('template_redirect', function () {
	$map = get_query_var('s_map');
	if (!$map) {
		return;
	}
	header("Cache-Control: no-store, no-cache, must-revalidate", true, 200);
	header('Content-Type: text/xml; charset=UTF-8', true, 200);
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" '
	. 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd '
	. 'http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" '
	. 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
	$args = array(
		'post_status' => 'publish',
		'order' => 'DESC',
		'orderby' => 'DATE',
		'post_type' => 'post',
		'ignore_sticky_posts' => 1,
		'date_query' => [
			[
//				'year'  => $today['year'],
//				'month' => $today['mon'],
//				'day'   => $today['mday'],
			],
		],
		'per_page' => 500,
	);
	$query = new WP_Query($args);
	$c = 0;
	while ($query->have_posts()) {
		$query->the_post();
		$post = $query->post;
		$date = get_gmt_from_date($post->post_date, 'c');
		if ($c++ === 0) {
			echo "<url>\n";
			echo "<loc>" . esc_url(home_url()) . "</loc>\n";
			echo "<lastmod>{$date}</lastmod>\n";
			echo "</url>\n";
		}
		echo "<url>\n";
		echo "<loc>" . esc_url(get_permalink($post)) . "</loc>\n";
		echo "<lastmod>{$date}</lastmod>\n";
		$image = get_attached_media('image', $post);
		if (!empty($image)) {
			foreach ($image as $img) {
				$excerpt = (wp_get_attachment_caption($img->ID));
				$excerpt = $excerpt && is_string($excerpt) ? esc_xml($excerpt) : '';
				$title = esc_xml(get_the_title($img));
				$permalink = esc_url(get_permalink($img));
				echo "<image:image>\n";
				echo "<image:loc>{$permalink}</image:loc>\n";
				echo "<image:title><![CDATA[{$title}]]></image:title>\n";
				echo "<image:caption><![CDATA[{$excerpt}]]></image:caption>\n";
				echo "</image:image>\n";
			}

		}
		echo "</url>\n";
	}
	echo "</urlset>\n";
	unset($query);
	exit;
}, 9);
*/
