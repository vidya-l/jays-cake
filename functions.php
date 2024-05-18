<?php
/**
 * The current version of the theme.
 */
$the_theme = wp_get_theme();
define('jays_cake_VERSION', $the_theme->get( 'Version' ));

add_action('after_setup_theme', 'jays_cake_setup');

if (!function_exists('jays_cake_setup')) :

    /**
     * Global functions
     */
    function jays_cake_setup() {

        // Theme lang.
        load_theme_textdomain('jays', get_template_directory() . '/languages');

        // Add Title Tag Support.
        add_theme_support('title-tag');

        // Register Menus.
		$menus = array('main_menu' => esc_html__('Main Menu', 'jays'));
        register_nav_menus($menus);

        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(300, 300, true);
        add_image_size('jays-cake-img', 1140, 540, true);

        // Add Custom Background Support.
        $args = array(
            'default-color' => 'ffffff',
        );
        add_theme_support('custom-background', $args);

        add_theme_support('custom-logo', array(
            'height' => 60,
            'width' => 200,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => array('site-title', 'site-description'),
        ));

        // Adds RSS feed links to for posts and comments.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         */
        add_theme_support('title-tag');

        // Set the default content width.
        $GLOBALS['content_width'] = 1140;

        add_theme_support('custom-header', apply_filters('jays_cake_custom_header_args', array(
            'width' => 2000,
            'height' => 60,
            'default-text-color' => '',
            'wp-head-callback' => 'jays_cake_header_style',
        )));

        // WooCommerce support.
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('html5', array('search-form'));
		    add_theme_support('align-wide');
        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('assets/css/bootstrap.css', jays_cake_fonts_url(), 'assets/css/editor-style.css'));

    }

endif;

if (!function_exists('jays_cake_header_style')) :

    /**
     * Styles the header image and text displayed on the blog.
     */
    function jays_cake_header_style() {
        $header_image = get_header_image();
        $header_text_color = get_header_textcolor();
        if (get_theme_support('custom-header', 'default-text-color') !== $header_text_color || !empty($header_image)) {
            ?>
            <style type="text/css" id="jays-cake-header-css">
            <?php
            // Has a Custom Header been added?
            if (!empty($header_image)) :
                ?>
                    .site-header {
                        background-image: url(<?php header_image(); ?>);
                        background-repeat: no-repeat;
                        background-position: 50% 50%;
                        -webkit-background-size: cover;
                        -moz-background-size:    cover;
                        -o-background-size:      cover;
                        background-size:         cover;
                    }
            <?php endif; ?>	
            <?php
            // Has the text been hidden?
            if ('blank' === $header_text_color) :
                ?>
                    .site-title,
                    .site-description {
                        position: absolute;
                        clip: rect(1px, 1px, 1px, 1px);
                    }
            <?php elseif ('' !== $header_text_color) : ?>
                    .site-title a, 
                    .site-title, 
                    .site-description {
                        color: #<?php echo esc_attr($header_text_color); ?>;
                    }
            <?php endif; ?>	
            </style>
            <?php
        }
    }

endif; // jays_cake_header_style

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function jays_cake_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}

add_action('wp_head', 'jays_cake_pingback_header');

/**
 * Set Content Width
 */
function jays_cake_content_width() {

    $content_width = $GLOBALS['content_width'];

    if (is_active_sidebar('jays-cake-right-sidebar')) {
        $content_width = 847;
    } else {
        $content_width = 1140;
    }

    /**
     * Filter content width of the theme.
     */
    $GLOBALS['content_width'] = apply_filters('jays_cake_content_width', $content_width);
}

add_action('template_redirect', 'jays_cake_content_width', 0);

/**
 * Register custom fonts.
 */
function jays_cake_fonts_url() {
    $fonts_url = '';

    /**
     * Translators: If there are characters in your language that are not
     * supported by Lato, translate this to 'off'. Do not translate
     * into your own language.
     */
    $font = get_theme_mod('main_typographydesktop', '');

    if ('' == $font) {
        $font_families = array();

        $font_families[] = 'Lato:300,400,700,900';

        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'subset' => urlencode('cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese'),
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    return esc_url_raw($fonts_url);
}

/**
 * Add preconnect for Google Fonts.
 */
function jays_cake_resource_hints($urls, $relation_type) {
    if (wp_style_is('jays-cake-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}

add_filter('wp_resource_hints', 'jays_cake_resource_hints', 10, 2);

/**
 * Enqueue Styles (normal style.css and bootstrap.css)
 */
function jays_cake_theme_stylesheets() {
    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('jays-cake-fonts', jays_cake_fonts_url(), array(), null);
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array('hc-offcanvas-nav'), '3.3.7');
	  wp_enqueue_style('hc-offcanvas-nav', get_template_directory_uri() . '/assets/css/hc-offcanvas-nav.min.css', array(), jays_cake_VERSION);
    // Theme stylesheet.
    wp_enqueue_style('jays-cake-stylesheet', get_stylesheet_uri(), array('bootstrap'), jays_cake_VERSION);
    // WooCommerce stylesheet.
	if (class_exists('WooCommerce')) {
		wp_enqueue_style('jays-cake-woo-stylesheet', get_template_directory_uri() . '/assets/css/woocommerce.css', array('jays-cake-stylesheet', 'woocommerce-general'), jays_cake_VERSION);
	}
    // Load Line Awesome css.
    wp_enqueue_style('line-awesome', get_template_directory_uri() . '/assets/css/line-awesome.min.css', array(), '1.3.0');
}

add_action('wp_enqueue_scripts', 'jays_cake_theme_stylesheets');

/**
 * Register jquery
 */
function jays_cake_theme_js() {
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
    wp_enqueue_script('jays-cake-theme-js', get_template_directory_uri() . '/assets/js/entr.js', array('jquery'), jays_cake_VERSION, true);
	wp_enqueue_script('hc-offcanvas-nav', get_template_directory_uri() . '/assets/js/hc-offcanvas-nav.min.js', array('jquery'), jays_cake_VERSION, true);
}

add_action('wp_enqueue_scripts', 'jays_cake_theme_js');



if (!function_exists('jays_cake_title_logo')) {
    
	add_action('jays_cake_header', 'jays_cake_title_logo', 10);
    /**
     * Title, logo code
     */
    function jays_cake_title_logo() {
        ?>
        <div class="site-heading" >    
            <div class="site-branding-logo">
                <?php the_custom_logo(); ?>
            </div>
            <div class="site-branding-text">
                <?php if (is_front_page()) : ?>
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                <?php else : ?>
                    <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                <?php endif; ?>

                <?php
                $description = get_bloginfo('description', 'display');
                if ($description || is_customize_preview()) :
                    ?>
                    <p class="site-description">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php endif; ?>
            </div><!-- .site-branding-text -->
        </div>
        <?php
    }

}

if (!function_exists('jays_cake_menu')) {

		add_action('jays_cake_header', 'jays_cake_menu', 20);


    /**
     * Menu
     */
    function jays_cake_menu() {
        ?>
        <div class="menu-heading">
            <div id="site-navigation" class="navbar navbar-default">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'main_menu',
                    'depth' => 5,
                    'container_id' => 'theme-menu',
                    'container' => 'nav',
                    'container_class' => 'menu-container',
                    'menu_class' => 'nav navbar-nav navbar-' . get_theme_mod('menu_position', 'right'),
                    'fallback_cb' => 'jays_cake_WP_Bootstrap_Navwalker::fallback',
                    'walker' => new jays_cake_WP_Bootstrap_Navwalker(),
                ));
                ?>
            </div>
        </div>
        <?php
    }

}

add_action('jays_cake_header', 'jays_cake_head_start', 25);
function jays_cake_head_start() {
    echo '<div class="header-right" >';
}

add_action('jays_cake_header', 'jays_cake_head_end', 80);
function jays_cake_head_end() {
    echo '</div>';
}
if (!function_exists('jays_cake_menu_button')) {
    
	add_action('jays_cake_header', 'jays_cake_menu_button', 28);
    /**
     * Mobile menu button
     */
    function jays_cake_menu_button() {
        ?>
        <div class="menu-button visible-xs" >
            <div class="navbar-header">
				<a href="#" id="main-menu-panel" class="toggle menu-panel" data-panel="main-menu-panel">
					<span></span>
				</a>
            </div>
        </div>
        <?php
    }
}

/**
 * Register Custom Navigation Walker include custom menu widget to use walkerclass
 */
require_once( trailingslashit(get_template_directory()) . 'lib/wp_bootstrap_navwalker.php' );

/**
 * Register Theme Info Page
 */


if (class_exists('WooCommerce')) {

    /**
     * WooCommerce options
     */
    require_once( trailingslashit(get_template_directory()) . 'lib/woocommerce.php' );
}



require_once( trailingslashit(get_template_directory()) . 'lib/extra.php' );

add_action('widgets_init', 'jays_cake_widgets_init');

/**
 * Register the Sidebar(s)
 */
function jays_cake_widgets_init() {
    register_sidebar(
            array(
                'name' => esc_html__('Sidebar', 'jays'),
                'id' => 'jays-cake-right-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h3>',
                'after_title' => '</h3></div>',
            )
    );
    register_sidebar(
            array(
                'name' => esc_html__('Footer Section', 'jays'),
                'id' => 'jays-cake-footer-area',
                'before_widget' => '<div id="%1$s" class="widget %2$s col-md-3">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h3>',
                'after_title' => '</h3></div>',
            )
    );
}


if (!function_exists('wp_body_open')) :

    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     *
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         *
         */
        do_action('wp_body_open');
    }

endif;

/**
 * Skip to content link
 */
function jays_cake_skip_link() {
    echo '<a class="skip-link screen-reader-text" href="#site-content">' . esc_html__('Skip to the content', 'jays') . '</a>';
}

add_action('wp_body_open', 'jays_cake_skip_link', 5);

add_filter( 'woocommerce_states', 'fs_add_uae_emirates' );

function fs_add_uae_emirates( $states ) {
 $states['AE'] = array(
 	'AZ' => __( 'Abu Dhabi', 'woocommerce' ),
 	'AJ' => __( 'Ajman', 'woocommerce' ),
 	'FU'  => __( 'Fujairah', 'woocommerce' ),
 	'SH' => __( 'Sharjah', 'woocommerce' ),
 	'DU'  => __( 'Dubai', 'woocommerce' ),
 	'RK' => __( 'Ras Al Khaimah', 'woocommerce' ),
 	'UQ'  => __( 'Umm Al Quwain', 'woocommerce' ),
 );
 return $states;
}

function add_custom_product_tab($tabs) {
    // Adds the new tab
    $tabs['specifications'] = array(
        'title'    => __('Specifications', 'your-textdomain'),
        'priority' => 50,
        'callback' => 'custom_product_tab_content'
    );
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'add_custom_product_tab');

function custom_product_tab_content() {
    global $product;
    echo '<h2>' . __('Specifications', 'jays-cake') . '</h2>';
    echo '<table class="woocommerce-product-specifications">';
    echo '<tr><th>' . __('Specification', 'jays-cake') . '</th><th>' . __('Details', 'your-textdomain') . '</th></tr>';
    echo '<tr><td>' . __('Weight', 'jays-cake') . '</td><td>' . esc_html($product->get_weight()) . ' ' . esc_html(get_option('woocommerce_weight_unit')) . '</td></tr>';
    echo '<tr><td>' . __('Dimensions', 'jays-cake') . '</td><td>' . esc_html($product->get_dimensions()) . '</td></tr>';
    // Add more specifications as needed
    echo '</table>';
}

function add_theme_option_menu() {
    add_submenu_page(
        'themes.php',               
        'Theme options',           
        'Theme options',              
        'manage_options',
        'theme-options',
        'update_theme_options'
    );
}
add_action('admin_menu', 'add_theme_option_menu');

function update_theme_options() {
    ?>
      <div class="wrap">
        <h1><?php esc_html_e('Theme options', 'jays-cake'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('my_custom_settings_group');
            do_settings_sections('my-custom-menu');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function my_custom_settings_init() {
    // Register a setting group with a specific option name
    register_setting('my_custom_settings_group', 'my_custom_setting');

    // Add a new section in the custom admin page
    add_settings_section(
        'my_custom_settings_section',
        __('', 'jays-cake'),
        'my_custom_settings_section_callback',
        'my-custom-menu'
    );

    // Add a field to the new section
    add_settings_field(
        'my_custom_setting_field',
        __('Announcement text ', 'jays-cake'),
        'my_custom_setting_field_callback',
        'my-custom-menu',
        'my_custom_settings_section'
    );
}
add_action('admin_init', 'my_custom_settings_init');

function my_custom_settings_section_callback() {
    echo '<p>' . __('Enter your settings below:', 'jays-cake') . '</p>';
}

function my_custom_setting_field_callback() {
    $setting = get_option('my_custom_setting');
    echo '<textarea name="my_custom_setting" rows="10" cols="120">' . esc_attr($setting) . '</textarea>';
}