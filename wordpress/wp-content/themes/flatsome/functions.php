<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';

/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */


add_filter( 'woocommerce_product_tabs', 'woocommerce_custom_product_tabs' );
function woocommerce_custom_product_tabs( $tabs ) {

    $tabs['shipping_returns'] = array(
        'title' 	=> __( 'Shipping & Returns', 'woocommerce' ),
        'priority' 	=> 50,
        'callback' 	=> 'woocommerce_shipping_returns_product_tab_content'
    );

    return $tabs;
}

function woocommerce_shipping_returns_product_tab_content() {
    wc_get_template_part( 'single-product/tabs/shipping-returns-tab' );
    return;
}

function get_aliexpress_product() {

}

function import_aliexpress_product() {

}