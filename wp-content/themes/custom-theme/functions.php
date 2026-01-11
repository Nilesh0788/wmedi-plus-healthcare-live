<?php
/**
 * WMedi Plus Healthcare Theme Setup
 * This is a web application, NOT a blog
 */

function custom_theme_setup() {
    // Add support for essential WordPress features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'gallery', 'caption'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'custom-theme'),
        'footer' => __('Footer Menu', 'custom-theme'),
    ));
}
add_action('after_setup_theme', 'custom_theme_setup');

/**
 * DISABLE WORDPRESS BLOGGING FEATURES
 * This project is a healthcare web app, not a blog
 */

// Remove comments support completely
add_action('init', function() {
    // Remove post type support for comments
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
    
    // Disable comment feeds
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
}, 20);

// Hide comments from REST API
add_filter('rest_endpoints', function($endpoints) {
    if (isset($endpoints['/wp/v2/comments'])) {
        unset($endpoints['/wp/v2/comments']);
    }
    return $endpoints;
});

// Remove comments from menus
add_action('admin_menu', function() {
    remove_menu_page('edit-comments.php');
});

// Disable comment notifications
add_filter('pre_comment_on_post', '__return_false');

/**
 * Enqueue scripts and styles.
 */
function custom_theme_scripts() {
    wp_enqueue_style('custom-theme-style', get_stylesheet_uri());
    wp_enqueue_script('custom-theme-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_theme_scripts');

/**
 * Register widget area.
 */
function custom_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'custom-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'custom-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'custom_theme_widgets_init');

// Register custom post types
function custom_post_types() {
    // Register Services post type
    register_post_type('service', array(
        'labels' => array(
            'name' => __('Services'),
            'singular_name' => __('Service')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'services'),
        'supports' => array('title', 'editor', 'thumbnail')
    ));

    // Register Projects post type
    register_post_type('project', array(
        'labels' => array(
            'name' => __('Projects'),
            'singular_name' => __('Project')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'projects'),
        'supports' => array('title', 'editor', 'thumbnail')
    ));

    // Register Doctors/Team Members post type
    register_post_type('doctor', array(
        'labels' => array(
            'name' => __('Doctors'),
            'singular_name' => __('Doctor')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'doctors'),
        'supports' => array('title', 'editor', 'thumbnail')
    ));
}
add_action('init', 'custom_post_types');
