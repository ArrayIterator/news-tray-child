<?php
if (!defined('ABSPATH')) {
	return;
}

?>
<?php if ( ! is_paged() ) : ?>
<div class="content-wrap-area">
    <div class="content-wrap-left">
            <?php if ( apply_filters( 'news-tray-boolean-feature-enable-feature-slider',
            options_news_tray( 'homepage', 'enable_slider' ) === 'yes' ) ) : ?>
            <?php get_template_part( 'templates/components/home/section-slider' ); ?>
        <?php endif; ?>
    </div>
    <div class="content-wrap-right">
        <div class="sidebar-section top-child-sidebar sidebar-enabled">
            <div class="sidebar-top-container sidebar-entry">
			    <?php dynamic_sidebar( 'news-tray-child-top-sidebar' ); ?>
            </div>
        </div>
    </div>
</div>
<?php
    $options      = options_news_tray( 'wide_home_slider' );
    if (($options['enable_slider']??null) !== 'no') :
        $slider_count = isset( $options['slider_count'] ) && is_numeric( $options['slider_count'] )
            ? absint( $options['slider_count'] )
            : 4;
        $slider_count = $slider_count < 1 ? 1 : ( $slider_count > 15 ? 5 : $slider_count );
        $_args        = [
            'posts_per_page'      => $slider_count + 10, // Number of related posts to display.
            'current_page'        => 1,
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'has_password'        => false,
            'order'               => 'DESC',
            'orderby'             => 'DATE',
            'ignore_sticky_posts' => true,
            'suppress_filters'    => true,
            'no_found_rows'       => true,
            'tax_query'           => [
                'relation' => 'OR',
            ],
        ];
        $contains = false;
        if ( ! empty( $options['slider_category'] ) && is_array( $options['slider_category'] ) ) {
            $options['slider_category'] = array_map( 'absint', array_filter( $options['slider_category'], 'is_numeric' ) );
            if ( ! empty( $options['slider_category'] ) ) {
                $_args['tax_query'][] = [
                    'taxonomy' => 'category',
                    'terms'    => $options['slider_category'],
                    'field'    => 'term_id',
                    'operator' => 'IN',
                ];
                $contains = true;
            }
        }
        if ( ! empty( $options['slider_tags'] ) && is_array( $options['slider_tags'] ) ) {
            $options['slider_tags'] = array_map( 'absint', array_filter( $options['slider_tags'], 'is_numeric' ) );
            if ( ! empty( $options['slider_tags'] ) ) {
                $_args['tax_query'][] = [
                    'taxonomy' => 'post_tag',
                    'terms'    => $options['slider_tags'],
                    'operator' => 'IN',
                ];
                $contains = true;
            }
        }
        if (!$contains) {
            $_tags   = get_tags();
            uasort( $_tags, '_wp_object_count_sort_cb' );
            $_tags = array_map(function ($e) {
                return $e->term_id;
            }, array_splice($_tags, 0, 3));
        }
        $_posts = get_posts( $_args );
        if (!empty($_posts)) :
            $_config = json_encode( [
                'autoplay'           => true,
                'autoplayHoverPause' => true,
                'loop'               => true,
                'margin'             => 0,
                'singleItem'         => true,
                'items'              => 1,
                'nav'                => true,
                'lazyLoad'           => true,
                'autoHeight'         => true,
                'autoHeightClass'    => 'owl-height',
                'mouseDrag'          => true,
                'touchDrag'          => true,
                'dots'               => false,
                'autoplayTimeout'    => 7000,
                'autoplaySpeed'      => 1000,
                'responsive' => [
                    '300' => [
                        'items' => 1
                    ],
                    '414' => [
                        'items' => 2
                    ],
                    '780' => [
                        'items' => 3
                    ],
                    '980' => [
                        'items' => 4
                    ]
                ]
            ], JSON_UNESCAPED_SLASHES );
    ?>
    <div class="slider-wide-home-feature d-block w-100">
        <div class="slider-wide-home-container" data-carousel="<?= esc_attr( $_config ); ?>">
            <?php
            $ent = 'entry-feature';
            $c = 0;
            foreach ( $_posts as $_id => $_post ) :
                unset( $_posts[ $_id ] );
                if ($c++ >= $slider_count) {
                    break;
                }
                $_id = $_post->ID;
                $thumb = misc_news_tray_get_thumbnail_alt(
                    $_id,
                    'small-thumbnail'
                );
                if (!$thumb) {
                    continue;
                }
            ?>
                <div class="feature-item" data-pid="<?= $_id;?>">
                    <a href="<?= esc_attr(get_permalink($_id));?>">
                        <?= $thumb['html'];?>
                        <span class="feature-title"><?= get_the_title($_id);?></span>
                    </a>
                </div>
            <?php
            endforeach;
            unset($_posts);
            ?>
        </div>
    </div>
    <?php
        endif;
    endif;
endif;
?>
<?php

$_config = json_encode( [
	'autoplay'           => true,
	'autoplayHoverPause' => true,
	'loop'               => true,
	'margin'             => 0,
//    'responsiveClass' => true,
	'singleItem'         => true,
	'items'              => 1,
	'nav'                => false,
	'lazyLoad'           => true,
	'autoHeight'         => true,
	'autoHeightClass'    => 'owl-height',
	'mouseDrag'          => true,
	'touchDrag'          => true,
	'dots'               => true,
	'autoplayTimeout'    => 10000,
	'autoplaySpeed'      => 1000,
//    'navElement' => 'button name="owl-dot-navigation" data-use="bebe" type="button" role="presentation"'
], JSON_UNESCAPED_SLASHES );

$options      = options_news_tray( 'homepage' );
$slider_count = isset( $options['slider_count'] ) && is_numeric( $options['slider_count'] )
	? absint( $options['slider_count'] )
	: 4;
$slider_count = $slider_count < 1 ? 1 : ( $slider_count > 6 ? 6 : $slider_count );
$is_last      = options_news_tray( 'homepage', 'slider_mode' ) === 'latest_post';
if ( ! $is_last ) {
	$_args = [
		'posts_per_page'      => $slider_count, // Number of related posts to display.
		'current_page'        => 1,
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'has_password'        => false,
		'order'               => 'DESC',
		'orderby'             => 'date',
		'ignore_sticky_posts' => true,
		'suppress_filters'    => true,
		'no_found_rows'       => true,
		'tax_query'           => [
			'relation' => 'OR',
		],
	];
	if ( ! empty( $options['slider_category'] ) && is_array( $options['slider_category'] ) ) {
		$options['slider_category'] = array_map( 'absint', array_filter( $options['slider_category'], 'is_numeric' ) );
		if ( ! empty( $options['slider_category'] ) ) {
			$_args['tax_query'][] = [
				'taxonomy' => 'category',
				'terms'    => $options['slider_category'],
				'field'    => 'term_id',
				'operator' => 'IN',
			];
		}
	}
	if ( ! empty( $options['slider_tags'] ) && is_array( $options['slider_tags'] ) ) {
		$options['slider_tags'] = array_map( 'absint', array_filter( $options['slider_tags'], 'is_numeric' ) );
		if ( ! empty( $options['slider_tags'] ) ) {
			$_args['tax_query'][] = [
				'taxonomy' => 'post_tag',
				'terms'    => $options['slider_tags'],
				'operator' => 'IN',
			];
		}
	}

	$_posts = get_posts( $_args );
	if ( empty( $_posts ) ) {
		return;
	}
} else {
	$_c = 0;
	while ( have_posts() && $_c ++ < $slider_count ) {
		the_post();
		$_post    = get_post();
		$_posts[] = $_post;
	}
}

if ( empty( $_posts ) ) {
	return;
}
?>
<?php
$active_sidebar = is_active_sidebar( 'homepage-sidebar' );
?>
<?php do_action( 'news-tray-before-home' ); ?>
<?php /* START TOP WIDE */ ?>
<?php get_template_part( 'templates/components/home/section-wide-top' ); ?>
<?php /* END TOP WIDE */ ?>

<?php
/**
 * START -> HOMEPAGE
 * @hook boolean `news-tray-boolean-render-home` set false to disable render
 */
if ( apply_filters( 'news-tray-boolean-render-home', true ) !== false ) :
?>
    <div class="content-article-wrapper<?php echo $active_sidebar ? " sidebar-enabled" : ''; ?>">
        <div class="content-article<?php echo $active_sidebar ? " sidebar-enable" : ''; ?>">
			<?php do_action( 'news-tray-before-home-loop' ); ?>
			<?php get_template_part( 'templates/components/home/section-area-top' ); ?>

			<?php if ( is_paged() ) : ?>
				<?php get_template_part( 'templates/components/archive/feature' ); ?>
			<?php endif; ?>

			<?php /* <WRAPPED> */ ?>
			<?php get_template_part( 'templates/components/home/section-articles-top' ); ?>
			<?php if ( have_posts() ) : ?>
                <div class="filter-section-home">
					<?php
					/*
					 * SWITCH
					 */
					$enable_switch = options_news_tray( 'archive_default', 'enable_grid_switch' ) !== 'no';
					if ( apply_filters( 'news-tray-boolean-render-enable-grid-switch', $enable_switch ) == true ) :
						$must_grid = options_news_tray( 'archive_default', 'grid_type' ) == 'list';
						?>
                        <div class="loop-filter">
                            <span data-mode="grid"<?= ! $must_grid ? ' class="current-mode"' : ''; ?>>
                                <i class="ic-th-thumb"></i>
                            </span>
                            <span data-mode="grid-list"<?= $must_grid ? ' class="current-mode"' : ''; ?>>
                                <i class="ic-align-justify"></i>
                            </span>
                        </div>
					<?php endif; ?>
					<?php do_action( 'news-tray-before-render-loop' ); ?>
                    <h2 class="section-title">
                        <?php
                            echo strip_tags(options_news_tray('post_default', 'post_title', __('Latest Post', 'news-tray')))
                        ?>
                    </h2>
                </div>

				<?php do_action( 'news-tray-before-render-loop' ); ?>
                <div class="loop-wrap">
					<?php get_template_part( 'templates/parts/' . ( have_posts() ? 'loop' : 'notfound' ) ); ?>
                </div>
				<?php do_action( 'news-tray-after-render-loop' ); ?>
                <!-- .loop-wrap -->
			<?php endif; ?>
			<?php get_template_part( 'templates/components/home/section-articles-bottom' ); ?>
			<?php /* </WRAPPED> */ ?>

			<?php /* <FEATURES> */ ?>
			<?php if ( apply_filters( 'news-tray-boolean-feature-enable-feature-tags',
				options_news_tray( 'homepage', 'enable_tag_section' ) === 'yes' ) ) : ?>
				<?php get_template_part( 'templates/components/home/section-feature-tags' ); ?>
			<?php endif; ?>
			<?php if ( apply_filters( 'news-tray-boolean-feature-enable-feature-youtube-playlist',
				options_news_tray( 'homepage', 'enable_youtube_video_playlist' ) === 'yes' ) ) : ?>
				<?php get_template_part( 'templates/components/home/section-feature-youtube-playlist' ); ?>
			<?php endif; ?>
			<?php /* </FEATURES> */ ?>

			<?php get_template_part( 'templates/components/home/section-area-bottom' ); ?>
			<?php do_action( 'news-tray-after-home-loop' ); ?>
        </div>
        <!-- .content-article -->
		<?php /* SIDEBAR */ ?>
		<?php if ( $active_sidebar ) : ?>
            <div class="sidebar-section sidebar-home">
				<?php do_action( 'news-tray-before-sidebar-side-entry', 'homepage-sidebar' ); ?>
                <div class="sidebar-entry">
					<?php dynamic_sidebar( 'homepage-sidebar' ); ?>
                </div>
				<?php do_action( 'news-tray-after-sidebar-side-entry', 'homepage-sidebar' ); ?>
            </div>
		<?php endif; ?>
		<?php /* END SIDEBAR */ ?>
    </div>
<?php endif; ?>

<?php /* START BOTTOM WIDE */ ?>
<?php get_template_part( 'templates/components/home/section-wide-bottom' ); ?>
<?php /* END BOTTOM WIDE */ ?>

<?php do_action( 'news-tray-after-home' ); ?>

