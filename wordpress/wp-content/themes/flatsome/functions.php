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

function init_session_start() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init','init_session_start', 1);

/* Custom product tabs ----------------------------------------------------------- */

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

/* Google Analytics  ----------------------------------------------------------- */

add_action('wp_footer', 'add_googleanalytics');

function add_googleanalytics() { ?>

<?php }


function footer_menu_wrap() {
    // open the <ul>, set 'menu_class' and 'menu_id' values
    $wrap  = '<ul id="%1$s" class="%2$s">';

    // get nav items as configured in /wp-admin/
    $wrap .= '%3$s';

    $wrap .= '<li><a href="' . get_home_url() . '/terms/TERMS_OF_SERVICE.pdf">Terms of service</a></li>';
    $wrap .= '<li><a href="' . get_home_url() . '/terms/RETURN_POLICY.pdf">Return policy</a></li>';
    $wrap .= '<li><a href="' . get_home_url() . '/terms/PRIVACY_STATEMENT.pdf">Privacy Policy</a></li>';

    $wrap .= '</ul>';

    return $wrap;
}

//add_filter('woocommerce_show_variation_price',      function() { return TRUE;});

/* Product Page SKU ----------------------------------------------------------- */

function remove_product_page_skus( $enabled ) {
    if ( ! is_admin() && is_product() ) {
        return false;
    }

    return $enabled;
}
add_filter( 'wc_product_sku_enabled', 'remove_product_page_skus' );

/* Image Alt text ----------------------------------------------------------- */

function add_img_title( $attr, $attachment = null ) {

    $img_title = trim( strip_tags( $attachment->post_title ) );

    $attr['title'] = $img_title;
    $attr['alt'] = $img_title;

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes','add_img_title', 10, 2 );

add_filter( 'woocommerce_formatted_address_force_country_display', '__return_true' );

/* Exchange rates ----------------------------------------------------------- */

//add_action('init','get_currency', 2);
//
//$_SESSION['rate_changed'] = false;
//
//function get_converted_price($price) {
//    return round($price * $_SESSION['exchange_rate'], 2);
//}
//
//function get_converted_price_html($price, $html) {
//    return $html;
//    if($_SESSION['currency'] == get_woocommerce_currency()) {
//        return $html;
//    }
//
//    $html = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">%symbol%</span>%price%</span>';
//    $html = str_replace('%symbol%', get_currency_symbol(), $html);
//    $html = str_replace('%price%', get_converted_price($price), $html);
//    return $html;
//}
//
//function is_set_currency($currency) {
//    return $currency == $_SESSION['currency'];
//}
//
//function is_default_currency() {
//    return $_SESSION['currency'] == get_woocommerce_currency();
//}
//
//function get_currency_symbol() {
//    $currency_symbols = array(
//        "EUR" => "€",
//        "USD" => "$"
//    );
//
//    return $currency_symbols[$_SESSION['currency']];
//}
//
//function get_currency() {
//    if(isset($_GET['currency'])) {
//        $set_currency = $_GET['currency'];
//
//        if ($set_currency != $_SESSION['currency']) {
//            $_SESSION['currency'] = $set_currency;
//            $_SESSION['rate_changed'] = true;
//
//            convert_currency();
//        }
//    } else if(!isset($_SESSION['currency'])) {
//        $_SESSION['currency'] = get_woocommerce_currency();
//    }
//}
//
///**
// * https://docs.openexchangerates.org/docs/latest-json
// */
//function convert_currency() {
//    $default_currency = get_woocommerce_currency();
//    $currency = (isset($_SESSION['currency'])) ? $_SESSION['currency'] : $default_currency; // default to USD
//
//    if($currency == $default_currency) {
//        $_SESSION['exchange_rate'] = 1;
//    }
//
//    if($currency != $default_currency && $_SESSION['rate_changed'] === true) {
//        $exchange_rate_json = getOpenExchangeRates("http://openexchangerates.org/api/latest.json", array(
//            "app_id" => "12f4ec73059d42449a071ed4d22af493"
//        ));
//
//        $exchange_rate = json_decode($exchange_rate_json, true);
//
//        $_SESSION['exchange_rate'] = $exchange_rate['rates'][$currency];
//        $_SESSION['rate_changed'] = false;
//    }
//}
//
//function getOpenExchangeRates($url, $data = false) {
//    if ($data) {
//        $url = sprintf("%s?%s", $url, http_build_query($data));
//    }
//
//    $curl = curl_init($url);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//    $result = curl_exec($curl);
//    curl_close($curl);
//
//    return $result;
//}
//
//add_filter( 'woocommerce_get_price_html', 'change_product_html', 10, 2 );
//add_filter( 'woocommerce_cart_item_price', 'change_product_cart_html', 10, 2 );
//add_filter( 'woocommerce_cart_item_subtotal', 'change_product_cart_subtotal_html', 10, 2 );
//add_filter( 'woocommerce_cart_totals_subtotal', 'change_cart_totals_subtotal_html', 10, 1 );
//add_filter( 'woocommerce_cart_totals_total', 'change_cart_totals_total_html', 10, 1 );
//add_filter( 'woocommerce_cart_totals_taxes_total', 'change_cart_totals_taxes_total_html', 10, 1 );
//add_filter( 'woocommerce_mini_cart_subtotal', 'change_mini_cart_subtotal_html', 10, 1 );
//
//function change_product_html( $price_html, $product ) {
//    return get_converted_price_html($product->price, $price_html);
//}
//
//function change_product_cart_html($price_html, $cart_item) {
//    return get_converted_price_html($cart_item['data']->price, $price_html);
//}
//
//function change_product_cart_subtotal_html($price_html, $cart_item) {
//    return get_converted_price_html($cart_item['data']->price * $cart_item['quantity'], $price_html);
//}
//
//function change_cart_totals_subtotal_html($price_html) {
//    return get_converted_price_html(WC()->cart->subtotal, $price_html);
//}
//
//function change_cart_totals_total_html($price_html) {
//    return get_converted_price_html(WC()->cart->total, $price_html);
//}
//
//function change_cart_totals_taxes_total_html($price_html) {
//    return get_converted_price_html(WC()->cart->tax_total, $price_html);
//}
//
//function change_mini_cart_subtotal_html($price_html) {
//    return get_converted_price_html(WC()->cart->subtotal, $price_html);
//}
