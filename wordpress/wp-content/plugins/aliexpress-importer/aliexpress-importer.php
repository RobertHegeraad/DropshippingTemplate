<?php
/**
 * Plugin Name: Aliexpress Importer
 * Plugin URI: http://wordpress.com
 * Description: Import aliexpress products into woocommerce via a product URL
 * Version: 1.0.0
 * Author: Robert Hegeraad
 * Author URI: http://wordpress.com
 * License: GPL2
 */

require_once "Settings/Constants.php";
require_once "Settings/Settings.php";
require_once "Dashboard/Dashboard.php";
require_once "API/AliExpressApi.php";
require_once "Import/AliExpressImage.php";
require_once "Import/AliExpressImporter.php";
require_once "Order/AliExpressOrder.php";

add_action('admin_menu', 'aliExpressImporterAdminMenu');
function aliExpressImporterAdminMenu() {
    add_menu_page( 'Aliexpress Importer', 'Aliexpress Importer', 'manage_options', 'test-plugin', 'AliExpressImporterDashboard' );
    add_submenu_page('test-plugin', 'Settings', 'Settings', 'manage_options', 'aliexpress-importer-settings', 'AliExpressImporterSettings');
    add_submenu_page('test-plugin', 'Update stock', 'Update stock', 'manage_options', 'aliexpress-importer-update-stock', 'AliExpressImporterStock');
}

function AliExpressImporterDashboard() {
    OnAliExpressProductSubmit();

    include_once dirname( __FILE__ ) . '/views/AliExpressImporterDashboard.php';
}

function AliExpressImporterSettings() {
    include_once dirname( __FILE__ ) . '/views/AliExpressImporterSettings.php';
}

function AliExpressImporterStock() {
    include_once dirname( __FILE__ ) . '/views/AliExpressImporterStock.php';
}

add_action( 'admin_init', 'aliexpress_settings_register' );
function aliexpress_settings_register() {
    register_setting( 'aliexpress-importer-settings', 'app_key' );
    register_setting( 'aliexpress-importer-settings', 'tracking_id' );

    Settings::$APP_KEY = esc_attr( get_option('app_key'));
    Settings::$TRACKING_ID = esc_attr( get_option('tracking_id'));
}

add_action('wp_ajax_AliExpressImporterGetProduct', 'AliExpressImporterGetProduct');
function AliExpressImporterGetProduct() {
    $aliExpressProductUrl = $_REQUEST['url'];

    $aliExpressApi = new AliExpressApi();
    $results = $aliExpressApi->GetProductDetails($aliExpressProductUrl);

    echo json_encode(array('results' => $results));
    wp_die();
}

add_action('wp_ajax_AliExpressImporterGetProducts', 'AliExpressImporterGetProducts');
function AliExpressImporterGetProducts() {
    $products = get_posts(array( 'post_type' => array('product'), 'posts_per_page' => -1 ));

    $aliExpressApi = new AliExpressApi();

    foreach($products as &$product) {
        $product->product_id = wc_get_product_terms($product->ID, 'pa_product_id', array('fields' => 'names'))[0];
        $product->product_url = wc_get_product_terms($product->ID, 'pa_product_url', array('fields' => 'names'))[0];
        $product->product_html = $aliExpressApi->GetProductDetailsFromUrl($product->product_id, $product->product_url);
    }

    echo json_encode(array('results' => $products));
    wp_die();
}

add_action('wp_ajax_AliExpressImporterUpdateStock', 'AliExpressImporterUpdateStock');
function AliExpressImporterUpdateStock() {
    $product = $_REQUEST;

    $aliExpressImporter = new AliExpressImporter();
    $aliExpressImporter->UpdateStockForProduct($product);

    echo json_encode(array('results' => $product));
    wp_die();
}

function OnAliExpressProductSubmit() {
    $product = $_REQUEST;

    if(is_null($product['import-product'])) {
        return;
    }

    $aliExpressImporter = new AliExpressImporter();
    $aliExpressImporter->ImportProduct($product);
}

add_action( 'woocommerce_before_order_itemmeta', 'action_woocommerce_before_order_itemmeta', 10, 3 );
function action_woocommerce_before_order_itemmeta( $item_id, $product, $order) {
    $aliExpressOrder = new AliExpressOrder();
    echo $aliExpressOrder->GetAliEpxressProductOrderLink($product['product_id']);
};

/* ---------------------------------------------------------------------------------------------------------------------------- */

$dashboard = new Dashboard();
