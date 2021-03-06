<?php
/**
 * Processum functions and definitions
 *
 * @package Processum
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'processum_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function processum_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Processum, use a find and replace
	 * to change 'processum' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'processum', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'processum' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'processum_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // processum_setup
add_action( 'after_setup_theme', 'processum_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function processum_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'processum' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'processum_widgets_init' );


/**
 * Enqueue scripts and styles.
 * Add Custom CSS, Foundation CSS, Init JS depending on priority :
 3 fonctions pour 3 priorités : 10, 100 et 500 (ordre croissant)
 priorité 10 : style Underscores + navigation et skip-link-focus-fix
 priorité 100 : Foundation CSS JS et Init JS
 priorité 500 (la plus haute !) : custom.css et la fin avec la condition sur les commentaires
 */


function styles_priority10_scripts() {
	wp_enqueue_style( 'processum-style', get_stylesheet_uri() );
	wp_enqueue_script( 'processum-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'processum-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	}

add_action( 'wp_enqueue_scripts', 'styles_priority10_scripts', 10 );


function styles_priority100_scripts() {
	
/* Add Foundation CSS */
	wp_enqueue_style( 'foundation-normalize', get_stylesheet_directory_uri() . '/inc/foundation/css/normalize.css' );
	wp_enqueue_style( 'foundation', get_stylesheet_directory_uri() . '/inc/foundation/css/foundation.css' );
	wp_enqueue_style( 'foundation-icons', get_stylesheet_directory_uri() . '/inc/foundation-icons/foundation-icons.css', array(), '1' );

/* Add Foundation JS */
	wp_enqueue_script( 'foundation-js', get_template_directory_uri() . '/inc/foundation/js/foundation.min.js', array( 'jquery' ), '1', true );
	wp_enqueue_script( 'foundation-modernizr-js', get_template_directory_uri() . '/inc/foundation/js/vendor/modernizr.js', array( 'jquery' ), '1', false );
	wp_enqueue_script( 'foundation-fastclick-js', get_template_directory_uri() . '/inc/foundation/js/vendor/fastclick.js', false, '1', true );

/* Foundation Init JS */
	wp_enqueue_script( 'foundation-init-js', get_template_directory_uri() . '/inc/foundation.js', array( 'jquery' ), '1', true );
	}
	
add_action( 'wp_enqueue_scripts', 'styles_priority100_scripts', 100  );


function styles_priority500_scripts() {
	wp_enqueue_style( 'processum-custom-style', get_stylesheet_directory_uri() . '/custom.css', array(), '1' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'styles_priority500_scripts', 500 );


/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
