<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 30-12-2016
 * Time: 15:16
 */

class AliExpressImporter {

    private $aliExpressImage;

    public function __construct() {
        $this->aliExpressImage = new AliExpressImage();
    }

    public function ImportProduct($product) {

        $post_id = $this->InsertProduct($product);

        if(!$post_id) {

            // TODO: Show notification

            return;
        }

        if(count($product['skuProductTitles']) > 1) {
            wp_set_object_terms($post_id, 'variable', 'product_type');
        } else {
            wp_set_object_terms($post_id, 'simple', 'product_type');
        }

        // Affiliate short key
        $this->GetAffiliateShortKeyFromPromotionUrl($product);

        // Images
        $this->ImportImages($post_id, $product);

        // Categories
        $this->ImportCategories($post_id, $product);

        // Tags
        $this->ImportTags($post_id, $product);

        // Price
        $this->ImportPrice($post_id, $product);

        // Product attributes (Promotion Url etc)
        $this->ImportProductAttributes($post_id, $product);

        // Product variations
        $this->ImportProductVariations($post_id, $product);

        // Meta
        $this->ImportMeta($post_id, $product);
    }

    public function ImportProductAjax($product) {
        $this->ImportProduct($product);
    }

    public function ImportProducts($products) {
        // TODO: Bulk import via Excel
    }

    private function InsertProduct($product) {
        return wp_insert_post(array(
            'post_author' => get_current_user_id(),
            'post_content' => $product['product-description'],
            'post_status' => $product['product-active'] == 'on' ? 'publish' : 'draft',
            'post_title' => $product['product-title'],
            'post_parent' => '',
            'post_type' => "product",
        ));
    }

    private function ImportImages($post_id, $product) {
        $this->aliExpressImage->UploadProductThumbnail($product['product-images'][$product['product-thumbnail-index']], $post_id, $product['product-title']);
        unset($product['product-images'][$product['product-thumbnail-index']]);
        $this->aliExpressImage->UploadProductGallery($product['product-images'], $post_id, $product['product-title']);
    }

    private function ImportCategories($post_id, $product) {
        if(!is_null($product['product-categories'])) {
            $productCategories = array();
            foreach ($product['product-categories'] as $productCategory) {
                $term = get_term_by('id', $productCategory, 'product_cat');
                $productCategories[] = $term->name;
            }
            wp_set_object_terms($post_id, $productCategories, 'product_cat');
        }
    }

    private function ImportTags($post_id, $product) {
        if($product['product-tags'] != "") {
            $productTags = explode(',', $product['product-tags']);
            wp_set_object_terms($post_id, array_filter($productTags), 'product_tag');
        }
    }

    private function ImportPrice($post_id, $product) {
        update_post_meta( $post_id, '_regular_price', $product['product-price'] );
        update_post_meta( $post_id, '_sale_price', ($product['product-sale-price'] == "") ? $product['product-price'] : $product['product-sale-price'] );
    }

    private function ImportProductAttributes($post_id, $product) {
        $attributes = array(
            'pa_product_id' => $product['product-id'],
            'pa_product_url' => $product['product-url'],
            'pa_affiliate_short_key' => $product['affiliate_short_key'],
            'pa_product_store_name' => $product['product-store-name'],
            'pa_product_store_url' => $product['product-store-url']
        );

        $product_attributes = array();

        foreach($attributes as $key => $value) {

            wp_set_object_terms($post_id, array($value), $key);

            $product_attributes[$key] = array(
                'name'         => $key,
                'value'        => $value,
                'is_visible'   => '0',
                'is_variation' => '0',
                'is_taxonomy'  => '1'
            );
        }

        // TODO: Dynamically add other attributes

        wp_set_object_terms($post_id, $product['skuProductTitles'], 'pa_color');

        $product_attributes['pa_color'] = array(
            'name'=> 'pa_color',
            'value'=> '',
            'is_visible' => '1',
            'is_variation' => '1',
            'is_taxonomy' => '1'
        );

//        wp_set_object_terms($post_id, array('small', 'medium', 'big'), 'pa_size');
//
//        $product_attributes['pa_size'] = array(
//            'name'=> 'pa_size',
//            'value'=> '',
//            'is_visible' => '1',
//            'is_variation' => '1',
//            'is_taxonomy' => '1'
//        );

        update_post_meta($post_id, '_product_attributes', $product_attributes);
    }

    private function ImportProductVariations($post_id, $product) {

        for($i=0; $i<count($product['skuProductTitles']); $i++) {

            $variation_id = wp_insert_post(array(
                'post_author' => get_current_user_id(),
                'post_status' => "publish",
                'post_name' => '',
                'post_title' => '',
                'post_parent' => $post_id,
                'post_type' => "product_variation",
            ));

            update_post_meta($variation_id, '_manage_stock', "yes");
            update_post_meta($variation_id, $product['skuProductAttribute'][$i], $string = preg_replace("/[\s_]/", "-", strtolower($product['skuProductTitles'][$i]))); // Convert title to slug
//            update_post_meta($variation_id, "attribute_pa_size", "big");
            update_post_meta($variation_id, '_price', $product['skuProductPrices'][$i]);
            update_post_meta($variation_id, '_regular_price', $product['skuProductPrices'][$i]);
            update_post_meta($variation_id, '_sku', $product['skuProductSkus'][$i]);
            wc_update_product_stock($variation_id, $product['skuProductStocks'][$i]);

            if($product['skuProductImages'][$i] != "undefined") {
                $this->aliExpressImage->UploadProductThumbnail($product['skuProductImages'][$i], $variation_id, $product['product-title']);
            }
        }
    }

    private function ImportMeta($post_id, $product) {
        update_post_meta( $post_id, '_visibility', 'visible');
        update_post_meta( $post_id, '_stock_status', 'instock');
        update_post_meta( $post_id, 'total_sales', '0');
        update_post_meta( $post_id, '_downloadable', 'no');
        update_post_meta( $post_id, '_virtual', 'no');
        update_post_meta( $post_id, '_purchase_note', "" );
        update_post_meta( $post_id, '_featured', "no" );
        update_post_meta( $post_id, '_weight', "" );
        update_post_meta( $post_id, '_length', "" );
        update_post_meta( $post_id, '_width', "" );
        update_post_meta( $post_id, '_height', "" );
        update_post_meta( $post_id, '_sku', "");
        update_post_meta( $post_id, '_sale_price_dates_from', "" );
        update_post_meta( $post_id, '_sale_price_dates_to', "" );
        update_post_meta( $post_id, '_price', $product['product-price'] );
        update_post_meta( $post_id, '_sold_individually', "" );
        update_post_meta( $post_id, '_manage_stock', "yes" );
        update_post_meta( $post_id, '_backorders', "no" );
        update_post_meta( $post_id, '_stock', 1 );
    }

    private function GetAffiliateShortKeyFromPromotionUrl(&$product) {
        if($product['product-promotion-url'] != "") {
            parse_str(parse_url($product['product-promotion-url'])['query'], $query);
            $product['affiliate_short_key'] = $query['aff_short_key'];
        } else {
            $product['affiliate_short_key'] = "";
        }
    }

    public function UpdateStockForProduct($data) {
        if(!is_null($data['product']['variations']) && count($data['product']['variations']) > 0) {
            foreach($data['product']['variations'] as $variation) {
                wc_update_product_stock($variation['variation_id'], $variation['stock']);
            }
        }

        wc_update_product_stock($data['product']['product_id'], $data['product']['product_stock']);

    }
}