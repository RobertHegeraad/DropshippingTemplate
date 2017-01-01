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

add_action('admin_menu', 'aliExpressImporterAdminMenu');
function aliExpressImporterAdminMenu() {
    add_menu_page( 'Aliexpress Importer', 'Aliexpress Importer', 'manage_options', 'test-plugin', 'AliExpressImporterDashboard' );
}

function AliExpressImporterDashboard() {
    include_once dirname( __FILE__ ) . '/views/AliExpressImporterDashboard.php';
}

add_action('wp_ajax_AliExpressImporterGetProduct', 'AliExpressImporterGetProduct');
function AliExpressImporterGetProduct() {
    $aliExpressProductId = $_REQUEST['id'];

    return json_encode(array('product id' => 'id: ' . $aliExpressProductId));
}

/* ---------------------------------------------------------------------------------------------------------------------------- */

$dashboard = new Dashboard();