<?php
require_once 'framework/utilities.php';
function my_theme_enqueue_styles()
{
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.css');
    wp_enqueue_script('main-scripts', get_template_directory_uri() . '/assets/js/script.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function setup()
{
    add_post_type_support('page', 'excerpt');
    add_theme_support('menus');
    add_theme_support('revisions', array('post', 'page', 'carrera', 'beca'));
    register_nav_menus(array(
        'menu-main' => 'Menú principal',
        'menu-footer' => 'Menú footer',
    ));
}

$opciones =  acf_add_options_page(
    array(
        'page_title' => 'Opciones del sitio',
        'menu_title' => 'Opciones del sitio',
        'menu_slug' => 'options_site',
        'capability' => 'edit_posts',
        'position' => false,
        'parent_slug' => '',
        'redirect' => true,
        'post_id' => 'options',
        'autoload' => false,
        'icon_url' => 'dashicons-hammer',
    )
);

acf_add_options_sub_page(array(
    'menu_title'     => 'Opciones Generales',
    'page_title'     => 'Opciones Generales',
    'parent_slug'     => $opciones['menu_slug'],
));