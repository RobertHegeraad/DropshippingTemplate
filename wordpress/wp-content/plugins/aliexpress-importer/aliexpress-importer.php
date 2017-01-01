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
}

function AliExpressImporterDashboard() {
    OnAliExpressProductSubmit();

    include_once dirname( __FILE__ ) . '/views/AliExpressImporterDashboard.php';
}

function AliExpressImporterSettings() {
    include_once dirname( __FILE__ ) . '/views/AliExpressImporterSettings.php';
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
