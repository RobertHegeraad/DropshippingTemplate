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

require_once "Settings/Settings.php";
require_once "Dashboard/Dashboard.php";
require_once "API/AliExpressApi.php";
require_once "Import/AliExpressImage.php";
require_once "Import/AliExpressImporter.php";
require_once "Order/AliExpressOrder.php";

add_action('admin_menu', 'aliExpressImporterAdminMenu');
function aliExpressImporterAdminMenu() {
    add_menu_page( 'Aliexpress Importer', 'Aliexpress Importer', 'manage_options', 'test-plugin', 'AliExpressImporterDashboard' );
}

function AliExpressImporterDashboard() {
    OnAliExpressProductSubmit();

    include_once dirname( __FILE__ ) . '/views/AliExpressImporterDashboard.php';
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
