<?php
return [
	'wide_home_slider' => [
		'title' => __('Slide Above Main Slider', 'news-tray'),
		'args' => [],
		'settings' => [
			'enable_slider'                           => [
				'label'       => __( 'Enable Slider', 'news-tray' ),
				'description' => __( 'Enable top slider.', 'news-tray' ),
				'priority'    => 10,
				'transport'   => 'refresh',
				'type'        => 'select',
				'default'     => 'yes',
				'choices'     => [
					'no'  => __( 'No', 'news-tray' ),
					'yes' => __( 'Yes', 'news-tray' ),
				],
			],
			'slider_title'                               => [
				'label'       => __( 'Slider Title', 'news-tray' ),
				'description' => __( 'Slider section title', 'news-tray' ),
				'priority'    => 10,
				'transport'   => 'refresh',
				'type'        => 'text',
				'default'     => __( 'Hot', 'news-tray' ),
			],
			'slider_count' => [
				'label'       => __( 'Slide count', 'news-tray' ),
				'description' => __( 'Count of slider. (min. 6 / max. 15).', 'news-tray' ),
				'priority'    => 10,
				'transport'   => 'refresh',
				'type'        => 'number',
				'default'     => '10',
			],
			'slider_category'  => [
				'label'             => __( 'Slider Category', 'news-tray' ),
				'description'       => __( 'Categories of slider to shown.', 'news-tray' ),
				'priority'          => 10,
				'transport'         => 'refresh',
				'type'              => 'multiselect',
				'default'           => null,
				'sanitize_callback' => 'misc_news_tray_get_all_category_as_key_name_sanitize',
				'choices'           => misc_news_tray_get_all_category_as_key_name(),
			],
			'slider_tags'   => [
				'label'             => __( 'Slider Tags', 'news-tray' ),
				'description'       => __( 'Tags of slider to shown.', 'news-tray' ),
				'priority'          => 10,
				'transport'         => 'refresh',
				'type'              => 'multiselect',
				'default'           => null,
				'sanitize_callback' => 'misc_news_tray_get_all_tags_as_key_name_sanitize',
				'choices'           => misc_news_tray_get_all_tags_as_key_name(),
			],
		]
	],
    'post_default' => [
        'post_title' => [
            'label'       => __( 'Articles Title', 'news-tray' ),
            'description' => __( 'Title On Article', 'news-tray' ),
            'priority'    => 10,
            'transport'   => 'refresh',
            'type'        => 'text',
            'default'     => __( 'Latest Post', 'news-tray' ),
        ],
    ]
];