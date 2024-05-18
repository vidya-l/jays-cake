<?php
if (!function_exists('jays_cake_cart_link')) {

    function jays_cake_cart_link() {
        ?>	
        <a class="cart-contents" href="#" data-tooltip="<?php esc_attr_e('Cart', 'jays'); ?>" title="<?php esc_attr_e('Cart', 'jays'); ?>">
            <i class="la la-shopping-bag"><span class="count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span></i>
            <div class="amount-cart hidden-xs"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></div> 
        </a>
        <?php
    }

}

if (!function_exists('jays_cake_header_cart')) {

	add_action('jays_cake_header', 'jays_cake_header_cart', 30);
	
    function jays_cake_header_cart() {
        if (get_theme_mod('woo_header_cart', 1) == 1) {
            ?>
            <div class="header-cart">
                <div class="header-cart-block">
                    <div class="header-cart-inner">
                        <?php jays_cake_cart_link(); ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }

}
if (!function_exists('jays_cake_cart_content')) {

    add_action('wp_footer', 'jays_cake_cart_content', 30);

    function jays_cake_cart_content() {
        if (get_theme_mod('woo_header_cart', 1) == 1) {
            ?>
            <ul class="site-header-cart list-unstyled">
                <i class="la la-times-circle"></i>
                <li>
                    <?php the_widget('WC_Widget_Cart', 'title='); ?>
                </li>
            </ul>
            <?php
        }
    }

}
if (!function_exists('jays_cake_header_add_to_cart_fragment')) {
    add_filter('woocommerce_add_to_cart_fragments', 'jays_cake_header_add_to_cart_fragment');

    function jays_cake_header_add_to_cart_fragment($fragments) {
        ob_start();

        jays_cake_cart_link();

        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }

}

if (!function_exists('jays_cake_my_account')) {

	add_action('jays_cake_header', 'jays_cake_my_account', 40);

    function jays_cake_my_account() {
        $login_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
        ?>
        <div class="header-my-account">
            <div class="header-login"> 
                <a href="<?php echo esc_url($login_link); ?>" data-tooltip="<?php esc_attr_e('My Account', 'jays'); ?>" title="<?php esc_attr_e('My Account', 'jays'); ?>">
                    <i class="la la-user"></i>
                </a>
            </div>
        </div>
        <?php
    }

}

if (!function_exists('jays_cake_head_wishlist')) {

    add_action('jays_cake_header', 'jays_cake_head_wishlist', 50);

    function jays_cake_head_wishlist() {
        if (function_exists('YITH_WCWL')) {
            $wishlist_url = YITH_WCWL()->get_wishlist_url();
            ?>
            <div class="header-wishlist">
                <a href="<?php echo esc_url($wishlist_url); ?>" data-tooltip="<?php esc_attr_e('Wishlist', 'jays'); ?>" title="<?php esc_attr_e('Wishlist', 'jays'); ?>">
                    <i class="lar la-heart"></i>
                </a>
            </div>
            <?php
        }
    }

}

if (!function_exists('jays_cake_head_compare')) {

	add_action('jays_cake_header', 'jays_cake_head_compare', 60);
	
    function jays_cake_head_compare() {
        if (function_exists('yith_woocompare_constructor')) {
            global $yith_woocompare;
            ?>
            <div class="header-compare product">
                <a class="compare added" rel="nofollow" href="<?php echo esc_url($yith_woocompare->obj->view_table_url()); ?>" data-tooltip="<?php esc_attr_e('Compare', 'jays'); ?>" title="<?php esc_attr_e('Compare', 'jays'); ?>">
                    <i class="la la-sync"></i>
                </a>
            </div>
            <?php
        }
    }

}

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

add_action('woocommerce_before_main_content', 'jays_cake_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'jays_cake_wrapper_end', 10);

function jays_cake_wrapper_start() {
    ?>
    <div class="row">
        <article class="woo-content col-md-<?php jays_cake_main_content_width_columns(); ?>">
            <?php
}

function jays_cake_wrapper_end() {
            ?>
        </article>       
        <?php get_sidebar('right'); ?>
    </div>
    <?php
}

// Load cart widget in header. Required since Woo 7.8
function jays_cake_wc_cart_fragments() { wp_enqueue_script( 'wc-cart-fragments' ); }
add_action( 'wp_enqueue_scripts', 'jays_cake_wc_cart_fragments' );
