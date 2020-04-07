<?php

// Let's add some of the fun editor bits.
add_theme_support( 'title-tag' );
add_theme_support( 'align-wide' );
add_theme_support(
	'custom-logo',
	array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	)
);

// Let's hide the stuff we don't need.
function zen_remove_admin_menus() {
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', 'zen_remove_admin_menus' );

// Here we'll add and remove things from the customizer.
function zen_customizer_options( $wp_customize ) {
	$wp_customize->remove_control( 'show_on_front' );
	$wp_customize->remove_control( 'page_on_front' );
	$wp_customize->remove_control( 'page_for_posts' );

	$wp_customize->add_section(
		'static_front_page',
		array(
			'title'       => __( 'Zen Settings' ),
			'description' => __( '<h2>Why Zen?</h2><p>Zen is all about making WordPress simple again. Taking out all the stuff you don\'t need and getting you up and running as soon as possible.</p><h3>Now you have some decisions to make.</h3> <em>Choose wisely.</em> ' ),
			'priority'    => 160,
			'capability'  => 'edit_theme_options',
		)
	);

	$wp_customize->add_setting(
		'page_on_front',
		array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'zen_setup_front_page',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'zen_page_control',
			array(
				'type'     => 'dropdown-pages',
				'label'    => __( 'Where\'s your content?', 'zen' ),
				'section'  => 'static_front_page',
				'settings' => 'page_on_front',
			)
		)
	);

	$wp_customize->add_setting(
		'text_color',
		array(
			'default'   => '#f4f6eb',
			'transport' => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'text_color',
			array(
				'section' => 'static_front_page',
				'label'   => esc_html__( 'What color is your text?', 'theme' ),
			)
		)
	);

	// Link color.
	$wp_customize->add_setting(
		'bg_color',
		array(
			'default'           => '#2e8744',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'bg_color',
			array(
				'section' => 'static_front_page',
				'label'   => esc_html__( 'What color is your background?', 'theme' ),
			)
		)
	);

	// Accent color.
	$wp_customize->add_setting(
		'accent_color',
		array(
			'default'           => '#1c4d2c',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accent_color',
			array(
				'section' => 'static_front_page',
				'label'   => esc_html__( 'What color would make a nice accent?', 'theme' ),
			)
		)
	);

	// Getting rid of the menus as who needs them in a single page theme.
	remove_action(
		'customize_controls_enqueue_scripts',
		array(
			$wp_customize->nav_menus,
			'enqueue_scripts',
		)
	);
	remove_action(
		'customize_register',
		array(
			$wp_customize->nav_menus,
			'customize_register',
		),
		11
	);
	remove_filter(
		'customize_dynamic_setting_args',
		array(
			$wp_customize->nav_menus,
			'filter_dynamic_setting_args',
		)
	);
	remove_filter(
		'customize_dynamic_setting_class',
		array(
			$wp_customize->nav_menus,
			'filter_dynamic_setting_class',
		)
	);
	remove_action(
		'customize_controls_print_footer_scripts',
		array(
			$wp_customize->nav_menus,
			'print_templates',
		)
	);
	remove_action(
		'customize_controls_print_footer_scripts',
		array(
			$wp_customize->nav_menus,
			'available_items_template',
		)
	);
	remove_action(
		'customize_preview_init',
		array(
			$wp_customize->nav_menus,
			'customize_preview_init',
		)
	);
}
add_action( 'customize_register', 'zen_customizer_options' );

// Here's where we'll make it colorful.
function zen_styles() {
	wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/style.css' );

	$text_color   = get_theme_mod( 'text_color', '' );
	$bg_color     = get_theme_mod( 'bg_color', '' );
	$accent_color = get_theme_mod( 'accent_color', '' );
	$custom_css   = "
		:root {
			--bg: $bg_color;
			--text: $text_color;
			--border: $accent_color;
			--detail: $accent_color;
		}";
	wp_add_inline_style( 'custom-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'zen_styles' );

/**
 * Update homepage display settings.
 *
 * @return int
 */
function zen_setup_front_page( $value ) {
	if ( ! empty( $value ) ) {
		update_option( 'show_on_front', 'page' );
	}

	return (int) $value;
}
