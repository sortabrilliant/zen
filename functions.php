<?php
add_action('after_setup_theme', 'zen_editor_css');

add_theme_support( 'title-tag' );
add_theme_support( 'align-wide' );
add_theme_support( 'custom-logo', array(
	'height'      => 250,
	'width'       => 250,
	'flex-width'  => true,
	'flex-height' => true,
) );

function zen_editor_css()
{
    add_theme_support('editor-styles');
    add_editor_style('editor.css');
}

add_action('customize_register', 'zen_customizer_options');

function zen_customizer_options($wp_customize)
{
    $wp_customize->remove_control('show_on_front');

		remove_action( 'customize_controls_enqueue_scripts', array( $wp_customize->nav_menus, 'enqueue_scripts' ) );
		remove_action( 'customize_register', array( $wp_customize->nav_menus, 'customize_register' ), 11 );
		remove_filter( 'customize_dynamic_setting_args', array( $wp_customize->nav_menus, 'filter_dynamic_setting_args' ) );
		remove_filter( 'customize_dynamic_setting_class', array( $wp_customize->nav_menus, 'filter_dynamic_setting_class' ) );
		remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'print_templates' ) );
		remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'available_items_template' ) );
		remove_action( 'customize_preview_init', array( $wp_customize->nav_menus, 'customize_preview_init' ) );

		$wp_customize->add_section('static_front_page', array(
  		'title' => __('Homepage Settings'),
  		'description' => __('Select which page has your content.'),
  		'priority' => 160,
  		'capability' => 'edit_theme_options',
));

    $wp_customize->add_setting(
    	'zen_page_setting',
    array(
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'zen_sanitize_dropdown_pages',
      )
);
    $wp_customize->add_control(
      new WP_Customize_Control(
         $wp_customize,
         'zen_page_control',
         array(
                         'type' => 'dropdown-pages',
                         'label'      => __('Display Page', 'zen'),
             'section'    => 'static_front_page',
             'settings'   => 'zen_page_setting'
         )
     )
  );

    function zen_sanitize_dropdown_pages($page_id, $setting)
    {
        $page_id = absint($page_id);

        return ('publish' == get_post_status($page_id) ? $page_id : $setting->default);
    }
}
