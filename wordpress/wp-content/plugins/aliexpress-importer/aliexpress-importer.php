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

add_action('admin_menu', 'aliexpress_importer_admin_menu');
function aliexpress_importer_admin_menu() {
    add_menu_page( 'Aliexpress Importer', 'Aliexpress importer', 'manage_options', 'test-plugin', 'aliexpress_importer_admin_page' );
}

function aliexpress_importer_admin_page() {

    aliexpress_importer_import_product();

    include_once dirname( __FILE__ ) . '/aliexpress-importer-admin.php';
}

add_action('wp_ajax_aliexpress_importer_get_product', 'aliexpress_importer_get_product');
function aliexpress_importer_get_product() {
    $productUrl = $_REQUEST['url'];

    if($productUrl == null) {
        echo json_encode(array('error' => 'Incorrect URL'));
        wp_die();
    }

    $result = getProduct($productUrl);

    if($result['errno'] != 0 || $result['http_code'] != 200) {
        echo json_encode(array('error' => 'An error occurred with error number ' . $result['errno'] . ' and http code ' . $result['http_code']));
        wp_die();
    }

    echo json_encode($result['content']);
    wp_die();
}

function aliexpress_importer_import_product() {
    $product = $_REQUEST;

    if(is_null($product['import-product'])) {
        return;
    }

    $post = array(
        'post_author' => get_current_user_id(),
        'post_content' => '',
        'post_status' => "publish",
        'post_title' => $product['product-title'],
        'post_parent' => '',
        'post_type' => "product",
    );

    $post_id = wp_insert_post($post);

    if(!$post_id) {
        echo 'Could not create post';
        return;
    }

    $productImageId = uploadThumbnail($product['product-image'], $post_id, "main");
    set_post_thumbnail($post_id, $productImageId);
    unset($product['product-images'][0]);

    uploadGallery($product['product-images'], $post_id);

    wp_set_object_terms($post_id, 'simple', 'product_type');

    if(!is_null($product['product-categories'])) {
        $productCategories = array();
        foreach ($product['product-categories'] as $productCategory) {
            $term = get_term_by('id', $productCategory, 'product_cat');
            $productCategories[] = $term->name;
        }
        wp_set_object_terms($post_id, $productCategories, 'product_cat');
    }

    if($product['product-tags'] != "") {
        $productTags = explode(',', $product['product-tags']);
        wp_set_object_terms($post_id, array_filter($productTags), 'product_tag');
    }

    update_post_meta( $post_id, '_visibility', 'visible' );
    update_post_meta( $post_id, '_stock_status', 'instock');
    update_post_meta( $post_id, 'total_sales', '0');
    update_post_meta( $post_id, '_downloadable', 'no');
    update_post_meta( $post_id, '_virtual', 'no');
    update_post_meta( $post_id, '_regular_price', $product['product-price'] );
    update_post_meta( $post_id, '_sale_price', $product['product-price'] );
    update_post_meta( $post_id, '_purchase_note', "" );
    update_post_meta( $post_id, '_featured', "no" );
    update_post_meta( $post_id, '_weight', "" );
    update_post_meta( $post_id, '_length', "" );
    update_post_meta( $post_id, '_width', "" );
    update_post_meta( $post_id, '_height', "" );
    update_post_meta( $post_id, '_sku', "");
    update_post_meta( $post_id, '_product_attributes', array());
    update_post_meta( $post_id, '_sale_price_dates_from', "" );
    update_post_meta( $post_id, '_sale_price_dates_to', "" );
    update_post_meta( $post_id, '_price', $product['product-price'] );
    update_post_meta( $post_id, '_sold_individually', "" );
    update_post_meta( $post_id, '_manage_stock', "no" );
    update_post_meta( $post_id, '_backorders', "no" );
    update_post_meta( $post_id, '_stock', "" );
}

function getProductImagesBy640x640($productUrl) {
    $extension = ".jpg_640x640.jpg";

    $productName = basename($productUrl);

    $parsed = parse_url($productUrl);

    $uri = explode($productName, $parsed['path'])[0];

    $file = $parsed['scheme'] . "://" . $parsed['host'] . $uri . basename($productUrl, '.jpg_50x50.jpg') . $extension;

    return $file;
}

function uploadThumbnail($productImage, $post_id, $seed) {
    $upload_file = wp_upload_bits("temp-" . $seed . ".jpg", null, file_get_contents($productImage));

    // $filename should be the path to a file in the upload directory.
    $filename = $upload_file['file'];

    // The ID of the post this attachment is for.
    $parent_post_id = $post_id;

    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $filename ), null );

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();

    // Prepare an array of post data for the attachment.
    $attachment = array(
        'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Insert the attachment.
    $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    // Delete the temp image
    unlink($upload_file['file']);

    return $attach_id;
}

function uploadGallery($productImages, $post_id) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $productImagesIds = array();
    foreach($productImages as $productImage) {
        $resizedUrl = getProductImagesBy640x640($productImage);
        $tmp = download_url($resizedUrl);
        $file_array['name'] = basename($resizedUrl);
        $file_array['tmp_name'] = $tmp;
        $productImagesIds[] = media_handle_sideload( $file_array, $post_id, 'desc' );
    }
    update_post_meta($post_id, '_product_image_gallery', implode(",", $productImagesIds));
}

/**
 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 * array containing the HTTP server response header fields and content.
 */
function getProduct($url) {
    $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

    $options = array(
        CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
        CURLOPT_POST           =>false,        //set to GET
        CURLOPT_USERAGENT      => $user_agent, //set user agent
        CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
        CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err     = curl_errno($ch);
    $errmsg  = curl_error($ch);
    $header  = curl_getinfo($ch);
    curl_close($ch);

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}